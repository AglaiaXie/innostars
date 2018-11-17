<template>
  <b-tooltip label="Schedule Detail">
    <button
      class="button is-capitalized is-small is-rounded"
      @click="createModal()"
      :class="{'is-warning': schedule.status==='pending', 'is-success': schedule.status==='confirmed'}" v-if="">
      {{ schedule.status }}
    </button>
  </b-tooltip>
</template>

<script>
    import ParticipantDetail from "./ParticipantDetail";
    import JudgeDetail from "./JudgeDetail";
    import InvestorDetail from "./InvestorDetail";

    const ModalForm = {
        props: ['permission', 'schedule'],
        components: {ParticipantDetail, JudgeDetail, InvestorDetail},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Schedule Detail</p>
  </header>
  <section class="modal-card-body">
    <table class="table is-fullwidth">
      <tbody>
      <tr>
        <th> Topic</th>
        <td>{{ schedule.topic}}</td>
      </tr>
      <tr>
        <th> People</th>
        <td>
          <p v-for="user in schedule.users">
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
          </p>
        </td>
      </tr>
      </tbody>
    </table>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
  </footer>
</div>`
    };

    export default {
        props: ['schedule', 'permission'],
        methods: {
            createModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        schedule: this.schedule,
                        permission: this.permission
                    }
                })
            }
        }
    }
</script>