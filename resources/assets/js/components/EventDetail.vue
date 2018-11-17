<template>
  <section>
    <a @click="detailModal()">
      {{ event.name }}
    </a>
  </section>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import JudgeDetail from "./JudgeDetail";
    import InvestorDetail from "./InvestorDetail";
    import ScheduleCreate from "./ScheduleCreate";

    const ModalForm = {
        props: ['permission', 'event'],
        components: {ParticipantDetail, JudgeDetail, InvestorDetail, ScheduleCreate},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Event Detail</p>
  </header>
  <section class="modal-card-body">
    <b-tabs>
      <b-tab-item label="Information">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th> Title</th>
            <td>{{ event.name }}</td>
          </tr>
          <tr>
            <th>Description</th>
            <td class="content">{{ event.description }}</td>
          </tr>
          <tr>
            <th>Address</th>
            <td class="content">{{ event.address }}</td>
          </tr>
          <tr>
            <th>Competition</th>
            <td> {{ event.competition.name }} - {{ event.competition.city }}</td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Attendants">
        <b-field grouped group-multiline>
          <div class="control">
            <b-select v-model="attendant_filter">
              <option value="">All Type</option>
              <option value="participant">Contestant</option>
              <option value="judge">Judge</option>
              <option value="investor">Investor</option>
            </b-select>
          </div>
        </b-field>
        <b-table
          :data="attendants">
          <template slot-scope="props">
            <b-table-column field="name" label="Name/Company">
              <participant-detail
                :user="props.row"
                :permission="permission"
                v-if="props.row.type === 'participant'"></participant-detail>
              <judge-detail
                :user="props.row"
                :permission="permission"
                v-if="props.row.type === 'judge'"></judge-detail>
              <investor-detail
                :user="props.row" :permission="permission"
                v-if="props.row.type === 'investor'"></investor-detail>
            </b-table-column>
            <b-table-column label="Type" class="is-capitalized">
              {{ props.row.type == 'participant' ? 'contestant' : props.row.type }}
            </b-table-column>
            <b-table-column label="Industry">
              <span v-if="props.row.type === 'participant'">
                <b-tag type="is-info">{{ props.row.company.industry.abbr }}</b-tag>
              </span>
              <span v-if="props.row.type === 'judge'">
                <b-taglist>
                  <b-tag
                    type="is-info"
                    :title="industry.name"
                    v-for="industry in props.row.judge_profile.judging_industries"
                    :key="industry.id">
                    {{ industry.abbr }}
                  </b-tag>
                </b-taglist>
              </span>
              <span v-if="props.row.type === 'investor'">
                <b-taglist>
                  <b-tag
                    type="is-info"
                    :title="industry.name"
                    v-for="industry in props.row.investor_profile.interested_industries"
                    :key="industry.id">
                    {{ industry.abbr }}
                  </b-tag>
                </b-taglist>
              </span>
            </b-table-column>
            <b-table-column label="Action">
              <schedule-create :permission="permission" :event="event" :user="props.row"></schedule-create>
            </b-table-column>
          </template>
        </b-table>
      </b-tab-item>
      <b-tab-item label="My Schedules" ref="scheduleTab">
        <table class="table is-fullwidth is-narrow is-bordered">
          <template v-for="schedule in schedules">
            <tr>
              <th>Table #{{ schedule.time_slot.table_number }}</th>
              <td>
                <b>Date</b>:
                <b-tag class="is-small">{{ new Date(schedule.time_slot.start).toLocaleString() }}</b-tag>
                ~
                <b-tag class="is-small">{{ new Date(schedule.time_slot.end).toLocaleString() }}</b-tag>
                <template v-if="schedule.status==='pending'">
                  <template v-for="user in schedule.users">
                    <button
                      class="button is-small is-danger"
                      v-if="user.pivot.status==='requested' && permission.id === user.id"
                      @click="updateSchedule(schedule.id, 'canceled', 'cancel', 'Cancel Schedule')">Cancel
                    </button>
                    <button
                      class="button is-small is-success"
                      v-if="user.pivot.status==='pending' && permission.id === user.id"
                      @click="updateSchedule(schedule.id, 'confirmed', 'confirm', 'Confirm Schedule')">Confirm
                    </button>
                    <button
                      class="button is-small is-danger"
                      v-if="user.pivot.status==='pending' && permission.id === user.id"
                      @click="updateSchedule(schedule.id, 'denied', 'deny', 'Deny Schedule')">Deny
                    </button>
                  </template>
                </template>
              </td>
            </tr>
            <tr>
              <td
                rowspan="2">
                <b-tag
                  class="is-capitalized"
                  is-rounded
                  v-bind:class="{
                    'is-success': schedule.status === 'confirmed',
                    'is-warning':  schedule.status === 'pending',
                    'is-danger':  schedule.status === 'canceled',
                    'is-grey-lighter':  schedule.status === 'denied',
                  }">
                  {{ schedule.status }}
                </b-tag>
              </td>
              <td>
                <b>Topic</b>:
                {{ schedule.topic }}
              </td>
            </tr>
            <tr>
              <td>
                <div class="columns is-gapless is-0">
                  <template v-for="user in schedule.users">
                    <div class="column is-narrow">
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
                    </div>
                    <div class="column is-narrow">:</div>
                    <div class="column">
                      <b-tag class="is-small">{{ user.pivot.status }}</b-tag>
                    </div>
                  </template>
                </div>
              </td>
            </tr>
          </template>
          <tbody>
          <tr>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
    </b-tabs>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
  </footer>
</div>
        `,
        data() {
            return {
                schedules: [],
                attendant_filter: ''
            }
        },
        computed: {
            attendants: function () {
                var instance = this;
                return instance.event.users.filter(function (user) {
                    if (instance.attendant_filter !== '') {
                        return user.id !== instance.permission.id && user.type === instance.attendant_filter;
                    } else {
                        return user.id !== instance.permission.id;
                    }
                })
            }
        },
        methods: {
            loadSchedules() {
                const instance = this;
                const loadingComponent = instance.$loading.open({container: instance.$refs.scheduleTab.$el});
                axios.get('/api/v1/events/' + this.event.id + '/schedules').then(function (response) {
                    instance.schedules = response.data;
                    loadingComponent.close();
                });
            },
            updateSchedule(id, status, label, labelUc) {
                const instance = this;
                instance.$dialog.confirm({
                    title: labelUc + ' meeting request',
                    message: 'Are you sure that you want to ' + label + ' meeting request?',
                    confirmText: labelUc,
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.put('/api/v1/events/' + instance.event.id + '/schedules/' + id, {status: status})
                            .then(function () {
                                instance.$toast.open({
                                    message: 'Request ' + status + '!',
                                    type: 'is-success'
                                });
                                instance.loadSchedules();
                            })
                            .catch(function () {
                                instance.$toast.open({
                                    message: 'Error, unable to ' + label + ' request',
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                })
            }
        },
        mounted: function () {
            var instance = this;
            instance.loadSchedules();
            bus.$on('schedule-created', function () {
                instance.loadSchedules();
            });
        }
    };

    export default {
        props: ['event', 'permission'],
        methods: {
            detailModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/events/' + this.event.id).then(function (response) {
                    instance.loadingComponment.close();
                    instance.openModal(response.data);
                });
            },
            openModal(event) {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        event: event,
                        permission: this.permission
                    }
                })
            }
        }
    }
</script>