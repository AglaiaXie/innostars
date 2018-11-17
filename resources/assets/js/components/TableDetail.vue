<template>
  <div class="card">
    <header class="card-header">
      <p class="card-header-title">
        <b-tag>{{ time_slot.from }} - {{ time_slot.to }}</b-tag>
      </p>
      <a href="#" class="card-header-icon has-text-danger" @click="deleteTimeSlot(time_slot)" title="Delete Time Slot">x</a>
    </header>
    <div class="card-content schedule-content">
      <template v-if="schedule">
        <schedule-detail :schedule="schedule" :permission="permission"></schedule-detail>
        <schedule-transfer :schedule="schedule" :permission="permission" :event="event"></schedule-transfer>
        <b-tooltip label="Delete Schedule" type="is-danger">
          <button class="button is-rounded is-small has-text-danger" @click="deleteSchedule(schedule.id)">
            <b-icon icon="trash" size="is-small"></b-icon>
          </button>
        </b-tooltip>
      </template>
      <schedule-assign :time_slot="time_slot" :permission="permission" :event="event" v-if="!schedule">
      </schedule-assign>
    </div>
  </div>
</template>

<script>
    import ScheduleDetail from "./ScheduleDetail";
    import ScheduleAssign from "./ScheduleAssign";
    import ScheduleTransfer from "./ScheduleTransfer";

    export default {
        props: ['time_slot', 'permission', 'event'],
        components: {ScheduleDetail, ScheduleAssign, ScheduleTransfer},
        computed: {
            schedule: function () {
                return this.time_slot.schedules.filter(function (schedule) {
                    return schedule.status === 'confirmed' || schedule.status === 'pending';
                }).shift();
            }
        },
        methods: {
            deleteSchedule(id) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting schedule',
                    message: 'Are you sure that you want to delete schedule?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/schedules/' + id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'Schedule deleted!',
                                    type: 'is-success'
                                });
                                bus.$emit('reload-days');
                            })
                            .catch(function (error) {
                                instance.$toast.open({
                                    message: 'Error, unable to delete schedule: ' + error.response.data,
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                })
            },
            deleteTimeSlot(time_slot) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting time slot',
                    message: 'Are you sure that you want to delete time slot?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/events/' + time_slot.event_id + '/time-slots/' + time_slot.id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'Time slot deleted!',
                                    type: 'is-success'
                                });
                                bus.$emit('reload-days');
                            })
                            .catch(function (error) {
                                instance.$toast.open({
                                    message: 'Error, unable to delete time slot: ' + error.response.data,
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                })
            }
        }
    }
</script>