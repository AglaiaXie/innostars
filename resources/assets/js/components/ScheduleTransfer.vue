<template>
  <b-tooltip label="Move Schedule" type="is-success">
    <button
      @click="createModal()"
      class="button is-rounded is-small has-text-success">
      <b-icon icon="share" size="is-small"></b-icon>
    </button>
  </b-tooltip>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import JudgeDetail from "./JudgeDetail";
    import InvestorDetail from "./InvestorDetail";

    const ModalForm = {
        props: ['permission', 'schedule', 'event'],
        components: {ParticipantDetail, JudgeDetail, InvestorDetail},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Schedule Detail</p>
  </header>
  <section class="modal-card-body">
    <b-field
      label="Available Time Slots"
      :type="errors.has('time_slot_id') ? 'is-danger': ''"
      :message="errors.has('time_slot_id') ? errors.first('time_slot_id') : ''">
      <b-select
        name='time_slot_id'
        placeholder="Select a Time Slot"
        v-validate="'required'"
        v-model="time_slot_id">
        <option v-for="time_slot in time_slots" :value="time_slot.id" :key="time_slot.id">
          Table {{ time_slot.table_number }}: {{ time_slot.start }} - {{ time_slot.end }}
        </option>
      </b-select>
    </b-field>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
    <button class="button is-primary" @click="save()">Transfer</button>
  </footer>
</div>
        `,
        data() {
            return {
                time_slot_id: null,
                time_slots: []
            }
        },
        methods: {
            save() {
                var instance = this;
                axios.put('/api/v1/events/' + this.event.id + '/schedules/' + this.schedule.id, {time_slot_id: instance.time_slot_id}).then(function () {
                    bus.$emit('reload-days');
                    instance.$snackbar.open('Schedule transferred successfully.');
                    instance.$parent.close();
                }).catch(function (error) {
                    instance.$snackbar.open({
                        message: error.response.data,
                        type: 'is-danger',
                        position: 'is-bottom-right',
                        indefinite: true
                    })
                });
            },
            loadTimeSlots() {
                const instance = this;
                axios.get('/api/v1/events/' + this.event.id + '/time-slots?type=available&schedule=' + this.schedule.id).then(function (response) {
                    instance.time_slots = response.data;
                });
            }
        },
        mounted: function () {
            this.loadTimeSlots();
        }
    };

    export default {
        props: ['schedule', 'permission', 'event'],
        methods: {
            createModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        schedule: this.schedule,
                        permission: this.permission,
                        event: this.event
                    }
                })
            }
        }
    }
</script>