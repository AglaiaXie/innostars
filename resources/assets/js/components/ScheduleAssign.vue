<template>
  <b-tooltip label="Assign Schedule" type="is-success">
    <button class="button is-rounded is-small has-text-success" @click="assignModal()">
      <b-icon icon="plus" size="is-small"></b-icon>
    </button>
  </b-tooltip>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import JudgeDetail from "./JudgeDetail";
    import InvestorDetail from "./InvestorDetail";

    const ModalForm = {
        props: ['permission', 'time_slot', 'users'],
        components: {ParticipantDetail, JudgeDetail, InvestorDetail},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Assign Schedule</p>
  </header>
  <section class="modal-card-body">
    <b-field
      label="Meeting Topic" :type="errors.has('topic') ? 'is-danger': ''"
      :message="errors.has('topic') ? errors.first('topic') : ''">
      <b-input
        name="topic"
        type="textarea"
        maxlength="2000"
        v-validate="'required'"
        v-model="schedule.topic"></b-input>
    </b-field>
    <h5 class="title is-5">Select 2 attendants</h5>
    <b-table
      checkable
      :checked-rows.sync="schedule.users"
      :data="users">
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
      </template>
    </b-table>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
    <button class="button is-primary" type="button" @click="save()">Save</button>
  </footer>
</div>`,
        data() {
            return {
                schedule: {
                    topic: '',
                    users: []
                }
            }
        },
        methods: {
            save() {
                var instance = this;
                this.$validator.validate().then(result => {
                    if (!result) {
                        return false;
                    }

                    if (this.schedule.users.length !== 2) {
                        instance.$toast.open({
                            message: 'Please select exactly 2 attendants',
                            type: 'is-danger',
                            duration: 5000
                        });

                        return false;
                    }
                    instance.loadingComponment = this.$loading.open();
                    axios.post('/api/v1/time-slots/' + this.time_slot.id + '/schedules', instance.schedule)
                        .then(function () {
                            instance.loadingComponment.close();
                            instance.$parent.close();
                            bus.$emit('reload-days');
                        });
                });
            }
        }
    };

    export default {
        props: ['time_slot', 'users', 'permission', 'event'],
        methods: {
            assignModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        users: this.event.users,
                        time_slot: this.time_slot,
                        permission: this.permission
                    }
                })
            }
        }
    }
</script>