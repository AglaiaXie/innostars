<template>
  <a href="#" @click="editModal()">
    <b-icon icon="edit" type="is-info"></b-icon>
  </a>
</template>

<script>
    const ModalForm = {
        props: ['permission', 'company'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Edit Company Status for {{ company.company.name }}</p>
  </header>
  <section class="modal-card-body">
    <b-field label="Average Score">
      <b-input
        name="score_average"
        type="number"
        step="0.01"
        v-model="form.score_average"
      ></b-input>
    </b-field>
    <b-field label="Promoted">
      <b-switch v-model="form.promoted"
                :true-value="true"
                :false-value="false">
        {{ form.promoted ? 'Yes' : 'No'}}
      </b-switch>
    </b-field>
    <b-field v-if="next_competitions.length > 0"
             label="Assign to next Stage">
      <b-select
        v-model="form.next_competition_id">
        <option v-for="competition in next_competitions" :value="competition.id" :key="competition.id">
          {{ competition.name }} - {{ competition.city }}
        </option>
      </b-select>
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
                form: {
                    score_average: this.company.score_average,
                    promoted: this.company.promoted,
                    next_competition_id: null,
                },
                next_competitions: [],
            }
        },
        methods: {
            save() {
                var instance = this;
                axios.put('/api/v1/companies/' + instance.company.id, instance.form).then(function () {
                    bus.$emit('company-reload');
                    instance.$parent.close();
                });
            }
        },
        mounted: function () {
            var instance = this;
            axios.get('/api/v1/companies/' + instance.company.id + '/next-competitions').then(function (response) {
                if (response.data.length) {
                    instance.next_competitions = [{id: 0, city: 'Not Assigned'}];
                    instance.next_competitions = instance.next_competitions.concat(response.data);
                }
            });
            axios.get('/api/v1/companies/' + instance.company.id + '/selected-next-competition').then(function (response) {
                instance.form.next_competition_id = response.data;
            });
        }
    };

    export default {
        props: ['permission', 'company'],
        methods: {
            editModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        permission: this.permission,
                        company: this.company
                    }
                })
            }
        }
    }
</script>