<template>
  <button @click="createModal()" class="button is-primary is-small">
    <b-icon icon="plus"></b-icon>
    <span>Meeting Request</span>
  </button>
</template>

<script>
    const ModalForm = {
        props: ['permission', 'event', 'user'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Schedule New Meeting</p>
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
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Cancel</button>
    <button class="button is-primary" @click="save()">Save</button>
  </footer>
</div>
        `,
        data() {
            return {
                time_slot_id: null,
                schedule: {
                    topic: '',
                    note: '',
                    user_id: this.user.id
                },
                time_slots: []
            }
        },
        methods: {
            save() {
                var instance = this;
                this.$validator.validate().then(result => {
                    if (!result) {
                        return false;
                    }
                    axios.post('/api/v1/time-slots/' + this.time_slot_id + '/schedules', instance.schedule).then(function (response) {
                        bus.$emit('schedule-created');
                        instance.$snackbar.open('Schedule created successfully.');
                        instance.$parent.close();
                    }).catch(function (error) {
                        instance.$snackbar.open({
                            message: error.response.data,
                            type: 'is-danger',
                            position: 'is-bottom-right',
                            indefinite: true
                        })
                    });
                });
            },
            loadTimeSlots() {
                const instance = this;
                axios.get('/api/v1/events/' + this.event.id + '/time-slots?type=available').then(function (response) {
                    instance.time_slots = response.data;
                });
            },
        },
        mounted: function () {
          this.loadTimeSlots();
        }
    };

    export default {
        props: ['permission', 'event', 'user'],
        methods: {
            createModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        event: this.event,
                        user: this.user,
                        permission: this.permission
                    }
                })
            }
        }
    }
</script>