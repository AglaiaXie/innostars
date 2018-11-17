<template>
  <section>
    <b-field grouped group-multiline>
      <div class="control">
        <b-input v-model="searchKeyword" @keyup.enter.native="search" placeholder="Search Name/Company/Email">
        </b-input>
      </div>
      <div class="control">
        <b-select v-model="searchIndustry">
          <option value="">Search Industry</option>
          <option v-for="industry in industries" :value="industry.id">{{ industry.name }}</option>
        </b-select>
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
        <b-switch v-model="searchUnsubmitted">Unsubmitted</b-switch>
      </div>
      <div class="control" v-if="permission.private">
        <b-switch v-model="searchApproved">Approved</b-switch>
      </div>
      <div class="control">
        <button class="button is-primary" v-on:click="search">Search搜索</button>
      </div>
      <div class="control">
        <button class="button" v-on:click="reset">Reset重置</button>
      </div>
    </b-field>


    <b-table
      :data="judges"
      :loading="loading"
      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      @page-change="onPageChange"
      :current-page.sync="page"
      backend-sorting
      default-sort-direction="desc"
      :default-sort="[sortField, sortOrder]"
      @sort="onSort">
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>

        <b-table-column field="name" label="Name姓名">
          <judge-detail :user="props.row" :permission="permission"></judge-detail>
        </b-table-column>

        <b-table-column field="email" label="Email" v-if="permission.private">
          {{ props.row.email }}
        </b-table-column>

        <b-table-column field="judge_profile.company" label="Company公司名">
          {{ props.row.judge_profile.company_name }}
        </b-table-column>

        <b-table-column field="judge_profile.title" label="Position职位">
          {{ props.row.judge_profile.title }}
        </b-table-column>

        <b-table-column label="Industry领域">
          <b-taglist>
            <b-tag
              type="is-info"
              :title="industry.name"
              v-for="industry in props.row.judge_profile.judging_industries"
              :key="industry.id">
              {{ industry.abbr }}
            </b-tag>
          </b-taglist>
        </b-table-column>

        <b-table-column label="Status" v-if="permission.private">
          <span
            class="icon"
            v-bind:class="props.row.judge_profile.submit ? 'has-text-success' : 'has-text-warning'"
            title="Submit Status">
            <i class="fa fa-upload"></i>
          </span>
          <span
            class="icon"
            v-bind:class="props.row.judge_profile.approval ? 'has-text-success' : 'has-text-warning'"
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
              title="Delete judge"
              v-on:click="deleteAccount(props.row.id, props.row.first_name + ' ' + props.row.last_name)"></i>
          </span>
        </b-table-column>
      </template>
    </b-table>
    <h4 class="title is-4">Total Result: {{ total }}</h4>
    <div v-if="permission.export">
      <a class="button is-primary"
         :href="'/api/v1/judges/download?keyword=' + searchKeyword + '&industry=' + searchIndustry + '&competition=' + searchCompetition + '&submitted=' + searchSubmitted + '&approved=' + searchApproved">
        Download CSV
      </a>
    </div>
  </section>
</template>

<script>
    import JudgeDetail from "./JudgeDetail";
    import MessageCreate from "./MessageCreate";

    export default {
        props: ['permission'],
        components: {JudgeDetail, MessageCreate},
        methods: {
            index() {
                this.loading = true;
                axios.get('/api/v1/judges', {
                    params: {
                        keyword: this.searchKeyword,
                        industry: this.searchIndustry,
                        competition: this.searchCompetition,
                        sxsw: this.searchSxsw,
                        submitted: this.searchSubmitted,
                        unsubmitted: this.searchUnsubmitted,
                        approved: this.searchApproved,
                        page: this.page,
                        perPage: this.perPage,
                        sortBy: this.sortField,
                        sortDirection: this.sortOrder
                    }
                }).then(({data}) => {
                    this.judges = data.data;
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
                this.searchIndustry = '';
                this.searchCompetition = '';
                this.searchSxsw = false;
                this.searchSubmitted = false;
                this.searchUnsubmitted = false;
                this.searchApproved = false;
                this.search();
            },
            marsquerade(id, name) {
                this.$dialog.confirm({
                    message: 'Are you sure that you want to masquerade ' + name + '\'s judge account?',
                    onConfirm: function () {
                        window.location = '/admin/masquerade/' + id
                    }
                })
            },
            deleteAccount(id, name) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting judge account',
                    message: 'Are you sure that you want to delete <br/> ' + name + '\'s judge account?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/judges/' + id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'judge account deleted!',
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
            axios.get('/api/v1/industries').then(function (response) {
                indstance.industries = response.data;
            });
            axios.get('/api/v1/competitions?filterBy=allJudges').then(function (response) {
                indstance.competitions = response.data;
            });
        },
        data() {
            return {
                judges: [],
                industries: [],
                competitions: [],
                loading: false,
                searchKeyword: '',
                searchIndustry: '',
                searchCompetition: '',
                searchSxsw: false,
                searchSubmitted: false,
                searchUnsubmitted: false,
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