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
    <p class="modal-card-title">Information for Final Competition in China</p>
  </header>
  <section class="modal-card-body">
    <h4 class="title is-4">
      1. Flight Ticket Receipt:
    </h4>
    <attachment-control
      :label="'Upload Flight Ticket Receipt'"
      :model="form.flight_ticket_receipt"
      :property_key="'flight_ticket_receipt'"
      :upload_event="'final-file-uploaded'" :delete_event="'final-file-deleted'"></attachment-control>
    <hr>
    <h4 class="title is-4">2. Supporting documents:</h4>
    <attachment-control
      :label="'Upload Pitch Deck'"
      :model="form.pitch_deck"
      :property_key="'pitch_deck'"
      :upload_event="'final-file-uploaded'" :delete_event="'final-file-deleted'"></attachment-control>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
  </footer>
</div>`,
        methods: {
            updateFormFile(file, relationship) {
                var instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.put('/api/v1/users/' + instance.id + '/final_forms/' + instance.form.id, {
                    file: file,
                    relationship: relationship
                }).then(function () {
                    instance.loadingComponment.close();
                });
            },
            reload() {
                var instance = this;
                axios.get('/api/v1/users/' + instance.id + '/final_forms/' + instance.form.id).then(function (response) {
                    instance.form = response.data;
                });
            }
        },
        mounted: function () {
            var instance = this;
            bus.$on('final-file-deleted', function (key) {
                instance.form[key] = null;
                instance.$forceUpdate();
            });
            bus.$on('final-file-uploaded', function (key, file) {
                instance.form[key] = file;
                instance.updateFormFile(file, key);
                instance.$forceUpdate();
            });
        },
        destroyed: function () {
            bus.$off('final-file-deleted');
            bus.$off('final-file-uploaded');
        }
    };

    export default {
        props: ['permission', 'id', 'form_id'],
        methods: {
            formModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/users/' + instance.id + '/final_forms/' + instance.form_id).then(function (response) {
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