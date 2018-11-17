<template>
  <section>
    <div class="level">
      <b-field grouped group-multiline class="level-left">
        <div class="control">
          <b-input v-model="searchKeyword" @keyup.enter.native="search" placeholder="Search Event Name/Schedule Topic">
          </b-input>
        </div>
        <div class="control">
          <button class="button is-primary" v-on:click="search">Search搜索</button>
        </div>
        <div class="control">
          <button class="button" v-on:click="reset">Reset重置</button>
        </div>
      </b-field>
    </div>

    <b-tabs>
      <b-tab-item :label="'Confirmed已确认会议 (' + confirmed.total + ')'">
        <b-table
          :data="confirmed.schedules"
          :loading="confirmed.loading"
          paginated
          backend-pagination
          :total="confirmed.total"
          :per-page="perPage"
          @page-change="onConfirmedPageChange"
          :current-page.sync="confirmed.page">
          <template slot-scope="props">
            <b-table-column label="#" width="40" numeric>
              {{ props.index + 1 }}
            </b-table-column>
            <b-table-column label="Event活动名">
              {{ props.row.time_slot.event.name }}
            </b-table-column>
            <b-table-column label="People对方公司">
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
            <b-table-column label="Table对接桌号">
              {{ props.row.time_slot.table_number }}
            </b-table-column>
            <b-table-column label="Time对接时间">
              <b-tag class="is-small">{{ new Date(props.row.time_slot.start).toLocaleString() }}</b-tag>
              ~
              <b-tag class="is-small">{{ new Date(props.row.time_slot.end).toLocaleString() }}</b-tag>
            </b-table-column>
            <b-table-column label="Topic信息">
              {{ props.row.topic}}
            </b-table-column>
            <b-table-column label="Action操作">
              <button
                class="button is-small is-danger"
                @click="updateSchedule(props.row.id, 'canceled', 'cancel', 'Cancel Schedule', 'warning')">Cancel</button>
            </b-table-column>
          </template>
        </b-table>
      </b-tab-item>
      <b-tab-item :label="'Received已接收会议请求 (' + received.total + ')'">
        <b-table
          :data="received.schedules"
          :loading="received.loading"
          paginated
          backend-pagination
          :total="received.total"
          :per-page="perPage"
          @page-change="onReceivedPageChange"
          :current-page.sync="received.page">
          <template slot-scope="props">
            <b-table-column label="#" width="40" numeric>
              {{ props.index + 1 }}
            </b-table-column>
            <b-table-column label="Event活动名">
              {{ props.row.time_slot.event.name }}
            </b-table-column>
            <b-table-column label="People对方公司">
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
            <b-table-column label="Table对接桌号">
              {{ props.row.time_slot.table_number }}
            </b-table-column>
            <b-table-column label="Time对接时间">
              <b-tag class="is-small">{{ new Date(props.row.time_slot.start).toLocaleString() }}</b-tag>
              ~
              <b-tag class="is-small">{{ new Date(props.row.time_slot.end).toLocaleString() }}</b-tag>
            </b-table-column>
            <b-table-column label="Topic信息">
              {{ props.row.topic}}
            </b-table-column>
            <b-table-column label="Action操作">
              <button
                class="button is-small is-success"
                @click="updateSchedule(props.row.id, 'confirmed', 'confirm', 'Confirm Schedule', 'success')">Confirm</button>
              <button
                class="button is-small is-danger"
                @click="updateSchedule(props.row.id, 'denied', 'deny', 'Deny Schedule', 'warning')">Deny</button>
            </b-table-column>
          </template>
        </b-table>
      </b-tab-item>
      <b-tab-item :label="'Sent已发送会议请求 (' + sent.total + ')'">
        <b-table
          :data="sent.schedules"
          :loading="sent.loading"
          paginated
          backend-pagination
          :total="sent.total"
          :per-page="perPage"
          @page-change="onSentPageChange"
          :current-page.sync="sent.page">
          <template slot-scope="props">
            <b-table-column label="#" width="40" numeric>
              {{ props.index + 1 }}
            </b-table-column>
            <b-table-column label="Event活动名">
              {{ props.row.time_slot.event.name }}
            </b-table-column>
            <b-table-column label="People对方公司">
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
            <b-table-column label="Table对接桌号">
              {{ props.row.time_slot.table_number }}
            </b-table-column>
            <b-table-column label="Time对接时间">
              <b-tag class="is-small">{{ new Date(props.row.time_slot.start).toLocaleString() }}</b-tag>
              ~
              <b-tag class="is-small">{{ new Date(props.row.time_slot.end).toLocaleString() }}</b-tag>
            </b-table-column>
            <b-table-column label="Topic信息">
              {{ props.row.topic}}
            </b-table-column>
            <b-table-column label="Action操作">
              <button
                class="button is-small is-danger"
                @click="updateSchedule(props.row.id, 'canceled', 'cancel', 'Cancel Schedule', 'warning')">Cancel</button>
            </b-table-column>
          </template>
        </b-table>
      </b-tab-item>
      <b-tab-item :label="'Voided被取消会议 (' + voided.total + ')'">
        <b-table
          :data="voided.schedules"
          :loading="voided.loading"
          paginated
          backend-pagination
          :total="voided.total"
          :per-page="perPage"
          @page-change="onVoidedPageChange"
          :current-page.sync="voided.page">
          <template slot-scope="props">
            <b-table-column label="#" width="40" numeric>
              {{ props.index + 1 }}
            </b-table-column>
            <b-table-column label="Event活动名">
              {{ props.row.time_slot.event.name }}
            </b-table-column>
            <b-table-column label="People对方公司">
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
            <b-table-column label="Table对接桌号">
              {{ props.row.time_slot.table_number }}
            </b-table-column>
            <b-table-column label="Time对接时间">
              <b-tag class="is-small">{{ new Date(props.row.time_slot.start).toLocaleString() }}</b-tag>
              ~
              <b-tag class="is-small">{{ new Date(props.row.time_slot.end).toLocaleString() }}</b-tag>
            </b-table-column>
            <b-table-column label="Topic信息">
              {{ props.row.topic }}
            </b-table-column>
            <b-table-column label="Status状态">
              {{ props.row.status }}
            </b-table-column>
          </template>
        </b-table>
      </b-tab-item>
    </b-tabs>


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
            index(type, typeParam) {
                this.confirmed.loading = true;
                return axios.get('/api/v1/schedules', {
                    params: {
                        keyword: this.searchKeyword,
                        page: this.page,
                        perPage: this.perPage,
                        sortBy: this.sortField,
                        sortDirection: this.sortOrder,
                        type: typeParam
                    }
                }).then(({data}) => {
                    type.schedules = data.data;
                    type.total = data.total;
                    type.loading = false;
                });
            },
            onConfirmedPageChange(page) {
                this.confirmed.page = page;
                this.index(this.confirmed, 'confirmed');
            },
            onReceivedPageChange(page) {
                this.received.page = page;
                this.index(this.received, 'received');
            },
            onSentPageChange(page) {
                this.sent.page = page;
                this.index(this.sent, 'sent');
            },
            onVoidedPageChange(page) {
                this.voided.page = page;
                this.index(this.voided, 'voided');
            },
            search() {
                this.confirmed.page = 1;
                this.index(this.confirmed, 'confirmed');
                this.received.page = 1;
                this.index(this.received, 'received');
                this.sent.page = 1;
                this.index(this.sent, 'sent');
                this.voided.page = 1;
                this.index(this.voided, 'voided');
            },
            reset() {
                this.searchKeyword = '';
                this.search();
            },
            updateSchedule(id, status, label, labelUc, type) {
                const instance = this;
                instance.$dialog.confirm({
                    title: labelUc + ' meeting request',
                    message: 'Are you sure that you want to ' + label + ' this?',
                    confirmText: labelUc,
                    type: 'is-' + type,
                    hasIcon: true,
                    onConfirm: function () {
                        axios.put('/api/v1/schedules/' + id, {status: status})
                            .then(function () {
                                instance.$toast.open({
                                    message: 'Request ' + status + '!',
                                    type: 'is-success'
                                });
                                instance.search();
                            })
                            .catch(function () {
                                instance.$toast.open({
                                    message: 'Error, unable to ' + label,
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                })
            }
        },
        mounted: function () {
            this.search();
        },
        data() {
            return {
                confirmed : {
                    schedules: [],
                    loading: false,
                    page: 1,
                    totalPage: 0,
                    total: 0
                },
                received : {
                    schedules: [],
                    loading: false,
                    page: 1,
                    totalPage: 0,
                    total: 0
                },
                sent : {
                    schedules: [],
                    loading: false,
                    page: 1,
                    totalPage: 0,
                    total: 0
                },
                voided : {
                    schedules: [],
                    loading: false,
                    page: 1,
                    totalPage: 0,
                    total: 0
                },
                searchKeyword: '',
                perPage: 20
            }
        }
    }
</script>