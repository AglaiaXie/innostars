<template>
  <span style="cursor: pointer" class="icon is-small has-text-info">
    <i
      class="fa fa-comment"
      @click="openModal()"
      title="Send Message"></i>
  </span>
</template>

<script>
    const ModalForm = {
        props: ['permission', 'user'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Send Message</p>
  </header>
  <section class="modal-card-body">
    <b-field
      label="Title"
      :type="errors.has('title') ? 'is-danger': ''"
      :message="errors.has('title') ? errors.first('title') : ''">
      <b-input
        name="title"
        v-model="message.title"
        placeholder="Message Title"
        maxlength="255"
        v-validate="'required'"
        message="Name is required"></b-input>
    </b-field>
    <b-field
      label="Message"
      :type="errors.has('body') ? 'is-danger': ''"
      :message="errors.has('body') ? errors.first('body') : ''">
      <b-input
        type="textarea"
        name="body"
        maxlength="2000"
        v-validate="'required'"
        v-model="message.body"
        message="Message body is required"></b-input>
    </b-field>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Cancel</button>
    <button class="button is-primary" @click="save()">Send</button>

  </footer>
</div>`,
        data() {
            return {
                message: {
                    title: '',
                    body: ''
                }
            }
        },
        methods: {
            save() {
                const instance = this;
                this.$validator.validate().then(result => {
                    if (!result) {
                        return false;
                    }
                    instance.loadingComponent = instance.$loading.open();
                    axios.post('/api/v1/messages', {
                        uid: instance.user.id,
                        title: instance.message.title,
                        body: instance.message.body
                    }).then(function () {
                        instance.loadingComponent.close();
                        instance.$snackbar.open({
                            message: 'Message sent successfully.',
                            type: 'is-success',
                            position: 'is-bottom-right',
                            indefinite: true
                        });
                        instance.$parent.close();
                    }).catch(function () {
                        instance.loadingComponent.close();
                        instance.$snackbar.open({
                            message: 'Message sent failed.',
                            type: 'is-error',
                            position: 'is-bottom-right',
                            indefinite: true
                        });
                    });
                });
            }
        }
    };

    export default {
        props: ['permission', 'user'],
        methods: {
            openModal() {
                const instance = this;
                instance.$modal.open({
                    parent: instance,
                    component: ModalForm,
                    hasModalCard: true,
                    props: {
                        user: instance.user,
                        permission: instance.permission
                    }
                })
            }
        }
    }
</script>