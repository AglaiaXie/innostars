<template>
  <button @click="formModal()" class="button is-primary">
    <span>Addition Info</span>
  </button>
</template>

<script>
    import AttachmentControl from "./AttachmentControl";

    const ModalForm = {
        props: ['permission', 'id', 'form'],
        components: {AttachmentControl},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Information for Semi-final Competition in China</p>
  </header>
  <section class="modal-card-body">
    <h4 class="title is-4">1. Supporting documents:</h4>
    <attachment-control
      :label="'Upload Signed Consent Frm'"
      :model="form.consent_form"
      :property_key="'consent_form'"
      :upload_event="'semifinal-file-uploaded'" :delete_event="'semifinal-file-deleted'"></attachment-control>
    <hr>
    <h4 class="title is-4">
      2. Passport & Flight Ticket Receipt:
      <button class="button is-small is-info is-rounded" @click="addPerson">
        <b-icon icon="plus"></b-icon>
        <span>Add Person</span>
      </button>
    </h4>
    <div class="card" v-for="semifinal_form_person in form.semifinal_form_people">
      <header class="card-header">
        <p class="card-header-title">
          Name: {{ semifinal_form_person.name }}
        </p>
        <a href="#" class="card-header-icon has-text-danger" @click="deletePerson(semifinal_form_person)" title="Delete Person">x</a>
      </header>
      <div class="card-content">
        <div class="content">
          <attachment-control
            :label="'Upload Passport Copy'"
            :model="semifinal_form_person.passport"
            :property_key="semifinal_form_person.id"
            :upload_event="'person-file-uploaded'"
            :delete_event="'person-file-deleted'"></attachment-control>
        </div>
      </div>
    </div>
    <br>
    <attachment-control
      :label="'Upload Flight Ticket Receipt'"
      :model="form.flight_ticket_receipt"
      :property_key="'flight_ticket_receipt'"
      :upload_event="'semifinal-file-uploaded'" :delete_event="'semifinal-file-deleted'"></attachment-control>
    <hr>
    <h4 class="title is-4">3. Semifinal Registration Form:</h4>
    <attachment-control
      :label="'Upload Completed Registration Form'"
      :model="form.registration_form"
      :property_key="'registration_form'"
      :upload_event="'semifinal-file-uploaded'" :delete_event="'semifinal-file-deleted'"></attachment-control>
    <p>Click <a href="/files/InnoSTARS_semi-finalist_registration_form.docx" target="_blank">here</a> to download the
      Registration Form</p>
    <hr>
    <h4 class="title is-4">4. Supporting documents:</h4>
    <attachment-control
      :label="'Upload Pitch Deck'"
      :model="form.pitch_deck"
      :property_key="'pitch_deck'"
      :upload_event="'semifinal-file-uploaded'" :delete_event="'semifinal-file-deleted'"></attachment-control>
    <attachment-control
      :label="'Upload Executive Summary'"
      :model="form.executive_summary"
      :property_key="'executive_summary'"
      :upload_event="'semifinal-file-uploaded'" :delete_event="'semifinal-file-deleted'"></attachment-control>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
  </footer>
</div>`,
        methods: {
            updateFormFile(file, relationship) {
                var instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.put('/api/v1/users/' + instance.id + '/semifinal_forms/' + instance.form.id, {
                    file: file,
                    relationship: relationship
                }).then(function () {
                    instance.loadingComponment.close();
                });
            },
            updatePersonFile(file, id) {
                var instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.put('/api/v1/semifinal_form_people/' + id, {
                    file: file,
                }).then(function () {
                    instance.loadingComponment.close();
                    instance.reload();
                });
            },
            addPerson() {
                var instance = this;
                this.$dialog.prompt({
                    message: `Full name?`,
                    inputAttrs: {
                        maxlength: 100
                    },
                    onConfirm: function (name) {
                        axios.post('/api/v1/semifinal_forms/' + instance.form.id + '/semifinal_form_persons', {name: name}).then(function () {
                            instance.$toast.open({
                                message: 'Person added successfully',
                                type: 'is-success'
                            });
                            instance.reload();
                        });
                    }
                })
            },
            reload() {
                var instance = this;
                axios.get('/api/v1/users/' + instance.id + '/semifinal_forms/' + instance.form.id).then(function (response) {
                    instance.form = response.data;
                });
            },
            deletePerson(person) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting person',
                    message: 'Are you sure that you want to delete this person?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/semifinal_forms/' + instance.form.id + '/semifinal_form_people/' + person.id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'Person deleted!',
                                    type: 'is-success'
                                });
                                instance.reload();
                            })
                            .catch(function (error) {
                                instance.$toast.open({
                                    message: 'Error, unable to delete person: ' + error.response.data,
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                })
            }
        },
        mounted: function () {
            var instance = this;
            bus.$on('semifinal-file-deleted', function (key) {
                instance.form[key] = null;
                instance.$forceUpdate();
            });
            bus.$on('semifinal-file-uploaded', function (key, file) {
                instance.form[key] = file;
                instance.updateFormFile(file, key);
                instance.$forceUpdate();
            });
            bus.$on('person-file-deleted', function () {
                instance.reload();
            });
            bus.$on('person-file-uploaded', function (key, file) {
                instance.updatePersonFile(file, key);
            });
        },
        destroyed: function () {
            bus.$off('semifinal-file-deleted');
            bus.$off('semifinal-file-uploaded');
            bus.$off('person-file-deleted');
            bus.$off('person-file-uploaded');
        }
    };

    export default {
        props: ['permission', 'id', 'form_id'],
        methods: {
            formModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/users/' + instance.id + '/semifinal_forms/' + instance.form_id).then(function (response) {
                    instance.loadingComponment.close();
                    instance.openModal(response.data);
                });
            },
            openModal(form) {
                this.$modal.open({
                    parent: this,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        permission: this.permission,
                        id: this.id,
                        form: form
                    }
                })
            }
        },
    }
</script>