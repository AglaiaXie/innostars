<template>
  <section>
    <b-field grouped group-multiline>
      <div class="control">
        <b-input v-model="searchKeyword" @keyup.enter.native="search" placeholder="Search Name/Company/Email">
        </b-input>
      </div>
      <div class="control">
        <b-select v-model="searchCompetition">
          <option value="">Search Competitions</option>
          <option v-for="competition in competitions" :value="competition.id">{{ competition.name }} - {{ competition.city}}</option>
        </b-select>
      </div>
      <div class="control" v-if="permission.private">
        <b-switch v-model="searchSxsw">SXSW</b-switch>
      </div>
      <div class="control" v-if="permission.private">
        <b-switch v-model="searchSubmitted">Submitted</b-switch>
      </div>
      <div class="control" v-if="permission.private">
        <b-switch v-model="searchApproved">Approved</b-switch>
      </div>
      <div class="control">
        <button class="button is-primary" v-on:click="search">Search</button>
      </div>
      <div class="control">
        <button class="button" v-on:click="reset">Reset</button>
      </div>
    </b-field>


    <b-table
      :data="partners"
      :loading="loading"
      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      :current-page.sync="page"
      @page-change="onPageChange"
      backend-sorting
      default-sort-direction="desc"
      :default-sort="[sortField, sortOrder]"
      @sort="onSort">
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>

        <b-table-column field="name" label="Name">
          <partner-detail :user="props.row" :permission="permission"></partner-detail>
        </b-table-column>

        <b-table-column field="email" label="Email" v-if="permission.private">
          {{ props.row.email }}
        </b-table-column>

        <b-table-column field="partner_profile.company" label="Company">
          {{ props.row.partner_profile.company_name }}
        </b-table-column>

        <b-table-column field="partner_profile.title" label="Position">
          {{ props.row.partner_profile.title }}
        </b-table-column>

        <b-table-column field="created_at" label="Register Date" centered sortable>
          <span class="tag">
            {{ new Date(props.row.created_at).toLocaleDateString() }}
          </span>
        </b-table-column>

        <b-table-column label="Status" v-if="permission.private">
          <span
            class="icon"
            v-bind:class="props.row.partner_profile.submit ? 'has-text-success' : 'has-text-warning'"
            title="Submit Status">
            <i class="fa fa-upload"></i>
          </span>
          <span
            class="icon"
            v-bind:class="props.row.partner_profile.approval ? 'has-text-success' : 'has-text-warning'"
            title="Approve Status">
            <i class="fa fa-thumbs-up"></i>
          </span>
        </b-table-column>

        <b-table-column label="Action" v-if="permission.private">
          <span style="cursor: pointer" class="icon is-small">
            <message-create :user="props.row"></message-create>
          </span>
          <span v-if="permission.login" style="cursor: pointer" class="icon is-small has-text-warning">
            <i
              class="fa fa-users"
              title="Masquerade to User"
              v-on:click="marsquerade(props.row.id, props.row.first_name + ' ' + props.row.last_name)"></i>
          </span>
          <span v-if="permission.delete" style="cursor: pointer" class="icon is-small has-text-danger">
            <i
              class="fa fa-remove"
              title="Delete partner"
              v-on:click="deleteAccount(props.row.id, props.row.first_name + ' ' + props.row.last_name)"></i>
          </span>
        </b-table-column>
      </template>
    </b-table>
    <h4 class="title is-4">Total Result: {{ total }}</h4>
    <div v-if="permission.export">
      <a class="button is-primary"
         :href="'/api/v1/partners/download?keyword=' + searchKeyword + '&industry=' + searchIndustry + '&competition=' + searchCompetition + '&submitted=' + searchSubmitted + '&approved=' + searchApproved">
        Download CSV
      </a>
    </div>
  </section>
</template>

<script>
    import partnerDetail from "./PartnerDetail";
    import MessageCreate from "./MessageCreate";

    export default {
        props: ['permission'],
        components: {partnerDetail, MessageCreate},
        methods: {
            index() {
                this.loading = true;
                axios.get('/api/v1/partners', {
                    params: {
                        keyword: this.searchKeyword,
                        competition: this.searchCompetition,
                        sxsw: this.searchSxsw,
                        submitted: this.searchSubmitted,
                        approved: this.searchApproved,
                        page: this.page,
                        perPage: this.perPage,
                        sortBy: this.sortField,
                        sortDirection: this.sortOrder
                    }
                }).then(({data}) => {
                    this.partners = data.data;
                    this.total = data.total;
                    this.loading = false;
                });
            },
            onSort(field, order) {
                this.sortField = field;
                this.sortOrder = order;
                this.index()
            },
            onPageChange(page) {
                this.page = page;
                this.index()
            },
            search() {
                this.page = 1;
                this.index();
            },
            reset() {
                this.searchKeyword = '';
                this.searchCompetition = '';
                this.searchSxsw = false;
                this.searchSubmitted = false;
                this.searchApproved = false;
                this.search();
            },
            marsquerade(id, name) {
                this.$dialog.confirm({
                    message: 'Are you sure that you want to masquerade ' + name + '\'s partner account?',
                    onConfirm: function () {
                        window.location = '/admin/masquerade/' + id
                    }
                })
            },
            deleteAccount(id, name) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting partner account',
                    message: 'Are you sure that you want to delete <br/> ' + name + '\'s partner account?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/partners/' + id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'partner account deleted!',
                                    type: 'is-success'
                                });
                                instance.search();
                            })
                            .catch(function (error) {
                                instance.$toast.open({
                                    message: 'Error, unable to delete account: ' + error.response.data,
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                })
            }
        },
        mounted: function () {
            var indstance = this;
            this.index();
            axios.get('/api/v1/competitions?filterBy=allPartners').then(function (response) {
                indstance.competitions = response.data;
            });
        },
        data() {
            return {
                partners: [],
                competitions: [],
                searchCompetition: '',
                loading: false,
                searchKeyword: '',
                searchSxsw: false,
                searchSubmitted: false,
                searchApproved: false,
                sortField: 'created_at',
                sortOrder: 'desc',
                page: 1,
                isPaginationSimple: false,
                defaultSortDirection: 'desc',
                perPage: 20,
                totalPage: 0,
                total: 0
            }
        }
    }
</script>