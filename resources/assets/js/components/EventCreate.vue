<template>
  <button @click="createModal()" class="button is-primary">
    <span>Add Event</span>
    <b-icon pack="fa" icon="plus"></b-icon>
  </button>
</template>

<script>
    const ModalForm = {
        props: ['permission', 'competitions'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Add New Event</p>
  </header>
  <section class="modal-card-body">
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
    <b-field
      label="Competition"
      :type="errors.has('description_id') ? 'is-danger': ''"
      :message="errors.has('description_id') ? errors.first('description_id') : ''">
      <b-select
        name='description_id'
        placeholder="Select a competition"
        v-validate="'required'"
        v-model="event.competition_id">
        <option v-for="competition in competitions" :value="competition.id" :key="competition.id">
          {{ competition.name }} - {{ competition.city }}
        </option>
      </b-select>
    </b-field>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Cancel</button>
    <button class="button is-primary" @click="save()">Save & Next Step</button>
  </footer>
</div>
        `,
        data() {
            return {
                event: {
                    name: '',
                    description: '',
                    address: '',
                    competition_id: null,
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
                    axios.post('/api/v1/events', instance.event).then(function () {
                        bus.$emit('event-saved');
                        instance.$parent.close();
                    });
                });
            }
        }
    };

    export default {
        props: ['permission', 'competitions'],
        methods: {
            createModal() {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        permission: this.permission,
                        competitions: this.competitions
                    }
                })
            }
        }
    }
</script>