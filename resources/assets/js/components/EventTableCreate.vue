<template>
  <button @click="createModal()" class="button is-primary">
    <span>Add Day & Tables</span>
    <b-icon pack="fa" icon="plus"></b-icon>
  </button>
</template>

<script>
    const ModalForm = {
        props: ['permission', 'event'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Add Day & Tables</p>
  </header>
  <section class="modal-card-body">
    <b-field grouped>
      <b-field
        expanded
        label="Day">
        <b-datepicker
          name="date"
          v-model="date"
          placeholder="Click to select..."
          icon="calendar-today">
        </b-datepicker>
      </b-field>
      <b-field
        expanded
        label="Start At">
        <b-timepicker
          placeholder="Click to select..."
          icon="clock"
          v-model="start">
        </b-timepicker>
      </b-field>
    </b-field>
    <b-field
      expanded
      label="Total Number of Tables" :type="errors.has('tables') ? 'is-danger': ''"
      :message="errors.has('tables') ? errors.first('tables') : ''">
      <b-input
        name="tables"
        type="number"
        min="1"
        v-model="tables"></b-input>
    </b-field>
    <b-field grouped>
      <b-field
        expanded
        label="Total Meetings each Table" :type="errors.has('meetings') ? 'is-danger': ''"
        :message="errors.has('meetings') ? errors.first('meetings') : ''">
        <b-input
          name="meetings"
          type="number"
          min="1"
          v-model="meetings"></b-input>
      </b-field>
      <b-field
        expanded
        label="Meeting length (minutes)" :type="errors.has('interval') ? 'is-danger': ''"
        :message="errors.has('interval') ? errors.first('interval') : ''">
        <b-input
          name="interval"
          type="number"
          min="1"
          v-model="interval"></b-input>
      </b-field>
    </b-field>
    <b-field grouped>
      <b-field label="Lunch Break">
        <b-switch v-model="lunchBreak"></b-switch>
      </b-field>
      <b-field
        expanded
        v-if="lunchBreak"
        label="Lunch Break After Meetings">
        <b-input
          name="meetings"
          type="number"
          v-model="breakAfter"></b-input>
      </b-field>
      <b-field
        expanded
        v-if="lunchBreak"
        label="Lunch Break Length (minutes)">
        <b-input
          name="interval"
          type="number"
          v-validate="'required'"
          v-model="breakPeriod"></b-input>
      </b-field>
    </b-field>
    <b-field grouped>
      <b-field label="Repeat Schedule">
        <b-switch v-model="repeatSchedule"></b-switch>
      </b-field>
      <b-field
        v-if="repeatSchedule"
        label="Following Day(s)">
        <b-input
          name="repeatDays"
          type="number"
          v-model="repeatDays"></b-input>
      </b-field>
      <b-field label="Weekday Only" v-if="repeatSchedule">
        <b-switch v-model="weekdayOnly"></b-switch>
      </b-field>
    </b-field>
    <hr>
    <h4 class="title is-4">Summary</h4>
    <section class="is-small">
      <p>The event will start at <b-tag>{{ startDate.format('LL, LT') }}</b-tag>.</p>
      <p>There will be <b-tag>{{ tables }}</b-tag> table(s) and <b-tag>{{ totalMeetings }}</b-tag> meetings in total.</p>
      <p v-if="lunchBreak">Lunch break start at <b-tag>{{ breakDate.format('LT') }}</b-tag> for {{ breakPeriod}} minutes.</p>
      <p>The event will end at <b-tag>{{ endDate.format('LL, LT') }}</b-tag>.</p>
      <p v-if="repeatSchedule">The schedule will be repeated in the following {{ repeatDays}} {{ weekdayOnly ? 'weekday' : 'day'}}(s).</p>
    </section>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Cancel</button>
    <button class="button is-primary" @click="save()">Insert & Save</button>
  </footer>
</div>
        `,
        data() {
            return {
                date: new Date(),
                start: new Date(),
                tables: 1,
                meetings: 1,
                interval: 15,
                lunchBreak: false,
                breakAfter: 1,
                breakPeriod: 60,
                repeatSchedule: false,
                repeatDays: 3,
                weekdayOnly: false
            }
        },
        computed: {
            startDate: function () {
                return moment(this.date).startOf('day').add(moment(this.start).hours(), 'hours').add(moment(this.start).minutes(), 'minutes');
            },
            breakDate: function () {
                return this.startDate.clone().add(this.breakAfter * this.breakPeriod, 'minutes');
            },
            endDate: function () {
                return this.startDate.clone().add(this.meetings * this.interval +  Number(this.lunchBreak) * this.breakPeriod, 'minutes');
            },
            totalMeetings: function () {
                return this.tables * this.meetings;
            }
        },
        methods: {
            save() {
                var instance = this;
                this.$validator.validate().then(result => {
                    if (!result) {
                        return false;
                    }
                    instance.loadingComponment = this.$loading.open();
                    axios.post('/api/v1/events/' + instance.event.id +'/time-slots', Object.assign({}, instance.$data, {
                        start: instance.startDate,
                        date: null
                    })).then(function () {
                        instance.loadingComponment.close();
                        instance.$parent.close();
                        bus.$emit('reload-days');
                    });
                });
            }
        }
    };

    export default {
        props: ['permission', 'event'],
        methods: {
            createModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        permission: this.permission,
                        event: this.event
                    }
                })
            },
        },
    }
</script>