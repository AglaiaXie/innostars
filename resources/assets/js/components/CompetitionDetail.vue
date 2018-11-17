<template>
  <section>
    <a @click="detailModal()">
      {{ competition.city }}
    </a>
  </section>
</template>

<script>
    import CompetitionParticipant from "./CompetitionParticipant";
    import CompetitionJudge from "./CompetitionJudge";

    const ModalForm = {
        props: ['competition', 'permission'],
        components: {CompetitionParticipant, CompetitionJudge},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Competition Detail: {{ competition.name }} - {{ competition.city }}</p>
  </header>
  <section class="modal-card-body">
    <b-tabs>
      <b-tab-item label="Contestants">
        <competition-participant :competition="competition" :permission="permission"></competition-participant>
      </b-tab-item>
      <b-tab-item label="Judges">
        <competition-judge :competition="competition" :permission="permission"></competition-judge>
      </b-tab-item>
      <b-tab-item label="Locations" v-if="competition.sub_competitions">
        <table class="table is-fullwidth">
          <thead>
          <th>Location</th>
          <th>Start</th>
          <th>End</th>
          </thead>
          <tbody>
          <tr v-for="sub_competition in competition.sub_competitions">
            <td>{{ sub_competition.city }}</td>
            <td><b-tag>{{ sub_competition.start ? new Date(sub_competition.start).toLocaleDateString() : 'TBD' }}</b-tag></td>
            <td><b-tag>{{ sub_competition.end ? new Date(sub_competition.end).toLocaleDateString() : 'TBD' }}</b-tag></td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
    </b-tabs>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
  </footer>
</div>`
    };

    export default {
        props: ['competition', 'permission'],
        data() {
            return {
                copy: null
            }
        },
        methods: {
            detailModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/competitions/' + this.competition.id).then(function (response) {
                    instance.copy = response.data;
                    instance.loadingComponment.close();
                    instance.openModal();
                });
            },
            openModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        competition: this.copy,
                        permission: this.permission
                    }
                })
            }
        }
    }
</script>