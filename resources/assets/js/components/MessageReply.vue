<template>
  <span>
    <a href="#" @click="detailModal" v-if="!unread">{{ subject }}</a>
    <a href="#" @click="detailModal" v-if="unread"><b>{{ subject }}</b></a>
  </span>
</template>

<script>
    const ModalForm = {
        props: ['permission', 'messages', 'thread_id'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Message Detail</p>
  </header>
  <section class="modal-card-body">
    <b-field
      label="Reply"
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
    <button class="button" type="button" @click="$parent.close()">Cancel</button>
    <button class="button is-primary" @click="reply()">Send</button>
    <hr>
    <template v-for="message in messages">
      <article class="media" id="template">
        <div class="media-content">
          <div class="content">
            <p>
              <strong data-name="">{{ message.user.first_name }} {{ message.user.last_name }}</strong>
              <small data-date="">{{ message.created_at }}</small>
            </p>
            <p data-message="">{{ message.body }}</p>
          </div>
        </div>
      </article>
    </template>

  </section>
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
            reply() {
                const instance = this;
                this.$validator.validate().then(result => {
                    if (!result) {
                        return false;
                    }
                    instance.loadingComponent = instance.$loading.open();
                    axios.post('/api/v1/threads/' + this.thread_id + '/messages', {
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
        props: ['permission', 'subject', 'thread_id', 'unread'],
        data() {
            return {
              copy: []
            }
        },
        methods: {
            detailModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/threads/' + this.thread_id + '/messages').then(function (response) {
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
                        messages: this.copy,
                        thread_id: this.thread_id,
                        permission: this.permission
                    }
                })
            }
        }
    }
</script>