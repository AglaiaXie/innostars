<template>
  <section>
    <b-field grouped group-multiline>
      <div class="control">
        <b-input v-model="searchKeyword" @keyup.enter.native="search" placeholder="Search Subject/Message Content">
        </b-input>
      </div>
      <div class="control">
        <button class="button is-primary" v-on:click="search">Search</button>
      </div>
      <div class="control">
        <button class="button" v-on:click="reset">Reset</button>
      </div>
    </b-field>


    <b-table
      :data="messages"
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

        <b-table-column label="Subject">
          <message-reply :subject="props.row.subject" :thread_id="props.row.id" :unread="props.row.is_unread"></message-reply>
        </b-table-column>

        <b-table-column label="User">
          <template v-for="user in props.row.users" v-if="user.id !== permission.id ">
            <participant-detail
              :user="user"
              :permission="permission"
              v-if="user.type === 'participant'"></participant-detail>
            <judge-detail
              :user="user"
              :permission="permission"
              v-if="user.type === 'judge'"></judge-detail>
            <investor-detail
              :user="user" :permission="permission"
              v-if="user.type === 'investor'"></investor-detail>
            ({{ user.type }})
          </template>
        </b-table-column>

        <b-table-column label="Date">
          <span class="tag">
            {{ new Date(props.row.updated_at).toLocaleDateString() }}
          </span>
        </b-table-column>
        <b-table-column label="Replies">
            {{ props.row.replies }}
        </b-table-column>
      </template>
    </b-table>
    <h4 class="title is-4">Total Messages: {{ total }}</h4>
  </section>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import JudgeDetail from "./JudgeDetail";
    import PartnerDetail from "./PartnerDetail";
    import InvestorDetail from "./InvestorDetail";
    import MessageReply from "./MessageReply";

    export default {
        props: ['permission'],
        components: {ParticipantDetail, JudgeDetail, InvestorDetail, PartnerDetail, MessageReply},
        methods: {
            index() {
                this.loading = true;
                axios.get('/api/v1/threads', {
                    params: {
                        keyword: this.searchKeyword,
                        page: this.page,
                        perPage: this.perPage,
                    }
                }).then(({data}) => {
                    this.messages = data.data;
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
                this.search();
            }
        },
        mounted: function () {
            var indstance = this;
            this.index();
            axios.get('/api/v1/industries').then(function (response) {
                indstance.industries = response.data;
            });
            axios.get('/api/v1/competitions?filterBy=allInvestors').then(function (response) {
                indstance.competitions = response.data;
            });
        },
        data() {
            return {
                messages: [],
                loading: false,
                searchKeyword: '',
                page: 1,
                isPaginationSimple: false,
                perPage: 20,
                totalPage: 0,
                total: 0
            }
        }
    }
</script>