<template>
  <a href="#"
     @click="openModal()"
     title="Edit">{{ event.name }}</a>
</template>

<script>
    import EventTableCreate from "./EventTableCreate";
    import TableRecord from "./TableRecord";
    import ParticipantDetail from "./ParticipantDetail";
    import JudgeDetail from "./JudgeDetail";
    import InvestorDetail from "./InvestorDetail";

    const ModalForm = {
        props: ['permission', 'event'],
        components: {EventTableCreate, TableRecord, ParticipantDetail, JudgeDetail, InvestorDetail},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Edit Event</p>
  </header>
  <section class="modal-card-body">
    <b-tabs>
      <b-tab-item label="Information">
        <b-field
          label="Title" :type="errors.has('name') ? 'is-danger': ''"
          :message="errors.has('name') ? errors.first('name') : ''">
          <b-input
            name="name"
            v-model="event.name"
            maxlength="255"
            v-validate="'required'"
            message="Name is required"
          ></b-input>
        </b-field>
        <b-field
          label="Description" :type="errors.has('description') ? 'is-danger': ''"
          :message="errors.has('description') ? errors.first('description') : ''">
          <b-input
            name="description"
            type="textarea"
            maxlength="2000"
            v-validate="'required'"
            v-model="event.description"></b-input>
        </b-field>
        <b-field
          label="Address" :type="errors.has('address') ? 'is-danger': ''"
          :message="errors.has('address') ? errors.first('address') : ''">
          <b-input
            name="address"
            type="textarea"
            maxlength="1000"
            v-validate="'required'"
            v-model="event.address"></b-input>
        </b-field>
        <b-field label="Competition">
          <div>{{ event.competition.name }} - {{ event.competition.city }}</div>
        </b-field>
        <b-field>
          <b-switch
            v-model="event.published"
            :true-value="1"
            :false-value="0">
            {{ event.published ? 'Save as published event' : 'Save as event draft' }}
          </b-switch>
        </b-field>
      </b-tab-item>
      <b-tab-item label="Tables">
        <event-table-create :event="event" :permission="permission"></event-table-create>
        <b-tabs>
          <b-tab-item v-for="(tables, date, index) in days" :label="date" :key="index">
            <div class="columns">
              <button class="button is-danger is-small is-pulled-right" @click="deleteDay(date)">
                <b-icon icon="trash"></b-icon>
                <span>Delete this day and tables</span>
              </button>
            </div>
            <table-record
              v-for="(timeSlots, tableNumber, index) in tables"
              :timeSlots="timeSlots"
              :tableNumber="tableNumber"
              :event="event"
              :permission="permission"
              :key="index">
            </table-record>
          </b-tab-item>
        </b-tabs>
      </b-tab-item>
      <b-tab-item label="Attendants" @click="loadAttendants">
        <b-tabs>
          <b-tab-item label="Contestant" ref="participantsTab">
            <b-table
              :data="participants"
              :checked-rows.sync="checkedAttendants"
              :custom-is-checked="compareChecked"
              checkable>
              <template slot-scope="props">
                <b-table-column field="name" label="Name">
                  <participant-detail :user="props.row" :permission="permission"></participant-detail>
                </b-table-column>
                <b-table-column field="participant_profile.company" label="Company">
                  {{ props.row.company.name }}
                </b-table-column>
                <b-table-column field="participant_profile.industry.name" label="Industry">
                  <b-tag class="is-info" v-if="props.row.company.industry ">
                    {{ props.row.company.industry.abbr }}
                  </b-tag>
                </b-table-column>
              </template>
            </b-table>
          </b-tab-item>
          <b-tab-item label="Judge" ref="judgesTab">
            <b-table
              :data="judges"
              :checked-rows.sync="checkedAttendants"
              :custom-is-checked="compareChecked"
              checkable>
              <template slot-scope="props">
                <b-table-column field="name" label="Name">
                  <judge-detail :user="props.row" :permission="permission"></judge-detail>
                </b-table-column>
                <b-table-column field="judge_profile.company" label="Company">
                  {{ props.row.judge_profile.company_name }}
                </b-table-column>
                <b-table-column label="Location">
                  <b-tag v-for="sub_competition in props.row.judge_profile.sub_competitions" v-if="sub_competition.competition_id === event.competition_id">
                    {{ sub_competition.city }}
                  </b-tag>
                </b-table-column>
                <b-table-column field="judge_profile.title" label="Position">
                  {{ props.row.judge_profile.title }}
                </b-table-column>
                <b-table-column label="Industries">
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
              </template>
            </b-table>
          </b-tab-item>
          <b-tab-item label="Investor" ref="investorsTab">
            <b-table
              :data="investors"
              :checked-rows.sync="checkedAttendants"
              :custom-is-checked="compareChecked"
              checkable>
              <template slot-scope="props">
                <b-table-column field="name" label="Name">
                  <investor-detail :user="props.row" :permission="permission"></investor-detail>
                </b-table-column>
                <b-table-column field="investor_profile.company" label="Company">
                  {{ props.row.investor_profile.company_name }}
                </b-table-column>
                <b-table-column label="Location">
                  <b-tag v-for="sub_competition in props.row.investor_profile.sub_competitions" v-if="sub_competition.competition_id === event.competition_id">
                    {{ sub_competition.city }}
                  </b-tag>
                </b-table-column>
                <b-table-column field="investor_profile.title" label="Position">
                  {{ props.row.investor_profile.title }}
                </b-table-column>
              </template>
            </b-table>
          </b-tab-item>
        </b-tabs>
      </b-tab-item>
    </b-tabs>

  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Cancel</button>
    <button class="button is-primary" @click="save()">Save</button>

  </footer>
</div>`,
        data() {
            return {
                days: [],
                participants: [],
                judges: [],
                investors: [],
                checkedAttendants: this.event.users
            };
        },
        methods: {
            save() {
                const instance = this;
                this.$validator.validate().then(result => {
                    if (!result) {
                        return false;
                    }
                    axios.put('/api/v1/events/' + instance.event.id, instance.event).then(function () {
                        const checkedIds = instance.checkedAttendants.map(function (attendant) {
                            return attendant.id;
                        });
                        axios.post('/api/v1/events/' + instance.event.id + '/users', {ids: checkedIds}).then(function () {
                            bus.$emit('event-saved');
                            instance.$parent.close();
                        });
                    });
                });
            },
            loadDays() {
                const instance = this;
                axios.get('/api/v1/events/' + this.event.id + '/time-slots-by-day').then(function (response) {
                    instance.days = response.data;
                });
            },
            loadAttendants() {
                this.loadParticipants();
                this.loadJudges();
                this.loadInvestors();
            },
            loadParticipants() {
                const instance = this;
                const loadingComponent = instance.$loading.open({container: instance.$refs.participantsTab.$el});
                axios.get('/api/v1/participants/all?approved=true&competition=' + instance.event.competition_id).then(function (response) {
                    instance.participants = response.data;
                    loadingComponent.close();
                });
            },
            loadJudges() {
                const instance = this;
                const loadingComponent = instance.$loading.open({container: instance.$refs.judgesTab.$el});
                axios.get('/api/v1/judges/all?approved=true&competition=' + instance.event.competition_id).then(function (response) {
                    instance.judges = response.data;
                    loadingComponent.close();
                });
            },
            loadInvestors() {
                const instance = this;
                const loadingComponent = instance.$loading.open({container: instance.$refs.investorsTab.$el});
                axios.get('/api/v1/investors/all?approved=true&competition=' + instance.event.competition_id).then(function (response) {
                    instance.investors = response.data;
                    loadingComponent.close();
                });
            },
            compareChecked(a, b) {
                return a.id === b.id;
            },
            deleteDay(date) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting all time slots for this day',
                    message: 'Are you sure that you want to delete all time slots for this day?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/events/' + instance.event.id + '/delete-time-slots-by-day?date=' + date).then(function () {
                            instance.loadDays();
                        });
                    }
                });
            }
        },
        mounted: function () {
            this.loadDays();
            this.loadAttendants();
            const instance = this;
            bus.$on('reload-days', function () {
                instance.loadDays();
            });
        }
    };

    export default {
        props: ['permission', 'event'],
        data() {
            return {
                copy: null
            }
        },
        methods: {
            openModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/events/' + this.event.id).then(function (response) {
                    instance.copy = response.data;
                    instance.loadingComponment.close();
                    instance.$modal.open({
                        parent: instance,
                        component: ModalForm,
                        hasModalCard: true,
                        props: {
                            event: instance.copy,
                            permission: instance.permission
                        }
                    })
                });
            },
        }
    }
</script>