<template>
  <div>
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
          <option v-for="competition in competitions" :value="competition.id">{{ competition.name }} - {{
            competition.city}}
          </option>
        </b-select>
      </div>
      <div class="control">
        <b-switch v-model="searchOnlinePromoted">Online Promoted海选优胜公司</b-switch>
      </div>
      <div class="control">
        <b-switch v-model="searchPreliminaryPromoted">Preliminary Promoted初赛优胜公司</b-switch>
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
      :data="participants"
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

        <b-table-column field="company.name" label="Company公司名">
          <participant-detail :user="props.row" :permission="permission"></participant-detail>
        </b-table-column>

        <b-table-column field="participant_profile.industry.name" label="Industry领域">
          <b-tag class="is-info" v-if="props.row.company.industry ">
            {{ props.row.company.industry.name }}
          </b-tag>
        </b-table-column>

        <b-table-column label="Results比赛结果">
          <div class="field is-grouped is-grouped-multiline">
            <div class="control" v-for="joined_competition in props.row.company.joined_competitions">
              <div class="tags has-addons" :class="">
                <span class="tag is-dark">
                  <span>{{ joined_competition.competition.city }}</span>
                  <b-icon icon="trophy" v-if="joined_competition.promoted"></b-icon>
                </span>
                <span class="tag is-success" v-if="permission.private">{{ joined_competition.score_average }}</span>
              </div>
            </div>
          </div>
        </b-table-column>

        <b-table-column label="Status" v-if="permission.private">
          <span
            class="icon"
            v-bind:class="props.row.company.submit ? 'has-text-success' : 'has-text-warning'"
            title="Submit Status">
            <i class="fa fa-upload"></i>
          </span>
          <span
            class="icon"
            v-bind:class="props.row.company.approval ? 'has-text-success' : 'has-text-warning'"
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
              title="Delete participant"
              v-on:click="deleteAccount(props.row.id, props.row.first_name + ' ' + props.row.last_name)"></i>
          </span>
        </b-table-column>
      </template>
    </b-table>
    <h4 class="title is-4">Total Result总用户数: {{ total }}</h4>
    <div v-if="permission.export">
      <a class="button is-primary"
         :href="'/api/v1/participants/download?keyword=' + searchKeyword + '&industry=' + searchIndustry + '&competition=' + searchCompetition + '&preliminaryPromoted=' + searchPreliminaryPromoted + '&submitted=' + searchSubmitted + '&approved=' + searchApproved">
        Download CSV
      </a>
    </div>
  </div>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import MessageCreate from "./MessageCreate";

    export default {
        props: ['permission'],
        components: {ParticipantDetail, MessageCreate},
        methods: {
            index() {
                this.loading = true;
                axios.get('/api/v1/participants', {
                    params: {
                        keyword: this.searchKeyword,
                        industry: this.searchIndustry,
                        competition: this.searchCompetition,
                        onlinePromoted: this.searchOnlinePromoted,
                        preliminaryPromoted: this.searchPreliminaryPromoted,
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
                    this.participants = data.data;
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
                this.searchPreliminaryPromoted = false;
                this.searchOnlinePromoted = false;
                this.searchSxsw = false;
                this.searchSubmitted = false;
                this.searchUnsubmitted = false;
                this.searchApproved = false;
                this.search();
            },
            marsquerade(id, name) {
                this.$dialog.confirm({
                    message: 'Are you sure that you want to masquerade ' + name + '\'s contestant account?',
                    onConfirm: function () {
                        window.location = '/admin/masquerade/' + id
                    }
                })
            },
            deleteAccount(id, name) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting participant account',
                    message: 'Are you sure that you want to delete <br/> ' + name + '\'s contestant account?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/participants/' + id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'participant account deleted!',
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
            },
            promoted(items) {
                return items.filter(item => item.promoted);
            },
            sortCompetition(competitions) {
                return competitions.sort(function (a,b) {
                    return a.competition.date > b.competition.date;
                });
            }
        },
        mounted: function () {
            var indstance = this;
            this.index();
            axios.get('/api/v1/industries').then(function (data) {
                indstance.industries = data.data;
            });
            axios.get('/api/v1/competitions?filterBy=allCompanies').then(function (data) {
                indstance.competitions = data.data;
            });
        },
        data() {
            return {
                participants: [],
                industries: [],
                competitions: [],
                loading: false,
                searchKeyword: '',
                searchIndustry: '',
                searchCompetition: '',
                searchPreliminaryPromoted: false,
                searchOnlinePromoted: false,
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