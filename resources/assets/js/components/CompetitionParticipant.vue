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
          <option v-for="industry in competition.industries" :value="industry.id">{{ industry.abbr }}</option>
        </b-select>
      </div>
      <div class="control" v-if="competition.name === 'Online Stage'">
        <b-select v-model="searchCompetition">
          <option value="">Search Competitions</option>
          <option v-for="competition in competitions" :value="competition.id">{{ competition.name }} - {{ competition.city}}</option>
        </b-select>
      </div>
      <div class="control">
        <button class="button is-primary" v-on:click="search">Search</button>
      </div>
      <div class="control">
        <button class="button" v-on:click="reset">Reset</button>
      </div>
    </b-field>

    <b-table
      :data="participants"
      :loading="loading"
      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      @page-change="onPageChange"
      :current-page.sync="page"
      backend-sorting
      :default-sort-direction="defaultSortOrder"
      :default-sort="[sortField, sortOrder]"
      @sort="onSort">
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>

        <b-table-column label="Company">
          <participant-detail :user="props.row.company.user" :permission="permission"></participant-detail>
        </b-table-column>

        <b-table-column label="Industry">
          <b-tag class="is-info" v-if="props.row.company.industry">
            {{ props.row.company.industry.abbr }}
          </b-tag>
        </b-table-column>

        <b-table-column label="Score" field="score_average" sortable>
          {{ props.row.score_average }}
          <b-icon icon="trophy" type="is-success" v-if="props.row.promoted"></b-icon>
        </b-table-column>

        <b-table-column label="商业模式" field="final_sum_1" sortable>
          {{ props.row.final_sum_1 }}
        </b-table-column>

        <b-table-column label="公司团队" field="final_sum_2" sortable>
          {{ props.row.final_sum_2 }}
        </b-table-column>

        <b-table-column label="市场情况" field="final_sum_3" sortable>
          {{ props.row.final_sum_3 }}
        </b-table-column>

        <b-table-column label="Edit" v-if="permission.private">
          <competition-participant-edit :permission="permission" :company="props.row"></competition-participant-edit>
        </b-table-column>

        <b-table-column label="Judges" v-if="permission.private">
          {{ props.row.judges }}
          <competition-participant-assign :permission="permission" :company="props.row"></competition-participant-assign>
        </b-table-column>
      </template>
    </b-table>
    <h4 class="title is-4">Total Result: {{ total }}</h4>
  </section>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import CompetitionParticipantEdit from "./CompetitionParticipantEdit";
    import CompetitionParticipantAssign from "./CompetitionParticipantAssign";

    export default {
        props: ['permission', 'competition'],
        components: {CompetitionParticipantEdit, CompetitionParticipantAssign, ParticipantDetail},
        methods: {
            index() {
                this.loading = true;
                axios.get('/api/v1/competitions/' + this.competition.id + '/companies', {
                    params: {
                        keyword: this.searchKeyword,
                        industry: this.searchIndustry,
                        competition: this.searchCompetition,
                        page: this.page,
                        perPage: this.perPage,
                        sortField: this.sortField,
                        sortOrder: this.sortOrder
                    }
                }).then(({data}) => {
                    this.participants = data.data;
                    this.total = data.total;
                    this.loading = false;
                });
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
                this.search();
            },
            onSort(field, order) {
                this.sortField = field;
                this.sortOrder = order;
                this.index()
            }
        },
        mounted: function () {
            var instance = this;
            instance.index();
            axios.get('/api/v1/competitions?filterBy=allInvestors').then(function (response) {
                instance.competitions = response.data;
            });
            bus.$on('company-reload', function() {
                instance.index();
            });
        },
        destroyed: function () {
            bus.$off('company-reload');
        },
        data() {
            return {
                participants: [],
                competitions: [],
                searchKeyword: '',
                searchIndustry: '',
                searchCompetition: '',
                loading: false,
                page: 1,
                isPaginationSimple: false,
                perPage: 20,
                totalPage: 0,
                sortField: 'score_average',
                sortOrder: 'desc',
                defaultSortOrder: 'desc',
                total: 0
            }
        }
    }
</script>