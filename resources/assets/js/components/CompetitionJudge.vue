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
      <div class="control">
        <button class="button is-primary" v-on:click="search">Search</button>
      </div>
      <div class="control">
        <button class="button" v-on:click="reset">Reset</button>
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
      :current-page.sync="page">
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>

        <b-table-column label="Name">
          <judge-detail :user="props.row.judge.user" :permission="permission"></judge-detail>
        </b-table-column>

        <b-table-column label="Organization">
          {{ props.row.judge.company_name }}
        </b-table-column>

        <b-table-column label="Industries">
          <b-taglist>
            <b-tag
              type="is-info"
              :title="industry.name"
              v-for="industry in props.row.judge.judging_industries"
              :key="industry.id">
              {{ industry.abbr }}
            </b-tag>
          </b-taglist>
        </b-table-column>
      </template>
    </b-table>
    <h4 class="title is-4">Total Result: {{ total }}</h4>
  </section>
</template>

<script>
    import JudgeDetail from "./JudgeDetail";

    export default {
        props: ['permission', 'competition'],
        components: {JudgeDetail},
        methods: {
            index() {
                this.loading = true;
                axios.get('/api/v1/competitions/' + this.competition.id + '/judgings', {
                    params: {
                        keyword: this.searchKeyword,
                        industry: this.searchIndustry,
                        page: this.page,
                        perPage: this.perPage,
                    }
                }).then(({data}) => {
                    this.judges = data.data;
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
                this.search();
            }
        },
        mounted: function () {
            var instance = this;
            instance.index();
            axios.get('/api/v1/competitions?filterBy=allInvestors').then(function (response) {
                instance.competitions = response.data;
            });
        },
        data() {
            return {
                judges: [],
                searchKeyword: '',
                searchIndustry: '',
                loading: false,
                page: 1,
                isPaginationSimple: false,
                perPage: 20,
                totalPage: 0,
                total: 0
            }
        }
    }
</script>