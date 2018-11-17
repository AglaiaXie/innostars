<template>
  <section>
    <div class="level">
      <b-field grouped group-multiline class="level-left">
        <div class="control">
          <b-input v-model="adminKeyword" @keyup.enter.native="search" placeholder="Search Attendant/Topic">
          </b-input>
        </div>
        <div class="control">
          <b-select v-model="status">
            <option value="">Search Status</option>
            <option value="confirmed">Confirmed</option>
            <option value="pending">Pending</option>
            <option value="canceled">Canceled</option>
            <option value="Denied">Denied</option>
          </b-select>
        </div>
        <div class="control">
          <b-select v-model="searchEvent">
            <option value="">Search Event</option>
            <option v-for="event in events" :value="event.id">{{ event.name }}
            </option>
          </b-select>
        </div>
        <div class="control">
          <button class="button is-primary" v-on:click="search">Search</button>
        </div>
        <div class="control">
          <button class="button" v-on:click="reset">Reset</button>
        </div>
      </b-field>
    </div>

    <b-table
      :data="schedules"
      :loading="loading"
      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      @page-change="onPageChange"
      :current-page.sync="page">
      <template slot-scope="props">
        <b-table-column label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>
        <b-table-column label="Event">
          {{ props.row.time_slot.event.name }}
        </b-table-column>
        <b-table-column label="People">
          <template v-for="user in props.row.users" v-if="user.id !== permission.id">
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
          </template>
        </b-table-column>
        <b-table-column label="Table">
          {{ props.row.time_slot.table_number }}
        </b-table-column>
        <b-table-column label="Time">
          <b-tag class="is-small">{{ new Date(props.row.time_slot.start).toLocaleString() }}</b-tag>
          ~
          <b-tag class="is-small">{{ new Date(props.row.time_slot.end).toLocaleString() }}</b-tag>
        </b-table-column>
        <b-table-column label="Topic">
          {{ props.row.topic}}
        </b-table-column>
        <b-table-column label="Status">
          <b-tag
            class="is-capitalized"
            is-rounded
            v-bind:class="{
                    'is-success': props.row.status === 'confirmed',
                    'is-warning':  props.row.status === 'pending',
                    'is-danger':  props.row.status === 'canceled',
                    'is-grey-lighter':  props.row.status === 'denied',
                  }">
            {{ props.row.status }}
          </b-tag>
        </b-table-column>
      </template>
    </b-table>
  </section>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import JudgeDetail from "./JudgeDetail";
    import InvestorDetail from "./InvestorDetail";

    export default {
        props: ['permission'],
        components: {ParticipantDetail, JudgeDetail, InvestorDetail},
        methods: {
            index() {
                var instance = this;
                this.loading = true;
                return axios.get('/api/v1/schedules', {
                    params: {
                        adminKeyword: this.adminKeyword,
                        page: this.page,
                        perPage: this.perPage,
                        status: this.status,
                        event: this.searchEvent
                    }
                }).then(({data}) => {
                    instance.schedules = data.data;
                    instance.total = data.total;
                    instance.loading = false;
                });
            },
            onPageChange(page) {
                this.page = page;
                this.index();
            },
            search() {
                this.page = 1;
                this.index();
            },
            reset() {
                this.adminKeyword = '';
                this.status = '';
                this.searchEvent = '';
                this.search();
            }
        },
        mounted: function () {
            this.search();
            var instance = this;
            axios.get('/api/v1/events?filterBy=allEvents').then(function (data) {
                instance.events = data.data;
            });
        },
        data() {
            return {
                schedules: [],
                events: [],
                loading: false,
                page: 1,
                totalPage: 0,
                total: 0,
                adminKeyword: '',
                status: '',
                searchEvent: '',
                perPage: 20
            }
        }
    }
</script>