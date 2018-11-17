<template>
  <section>
    <a @click="detailModal()">
      {{ user.first_name }} {{ user.last_name }}
    </a>
  </section>
</template>

<script>
    const ModalForm = {
        props: ['user', 'permission'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">partner Detail</p>
  </header>
  <section class="modal-card-body">
    <b-tabs>
      <b-tab-item label="Account" v-if="permission.private">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Name</th>
            <td>{{ user.first_name}} {{ user.last_name}}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Email</th>
            <td>{{ user.email }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Referred by SXSW</th>
            <td>{{ user.sxsw ? 'Yes' : 'No' }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>How did you hear about us?</th>
            <td>{{ user.partner_profile.refer }}</td>
          </tr>
          <tr v-if="permission.update">
            <th>Submitted</th>
            <td>
              <b-field>
                <b-radio-button
                  v-model="user.partner_profile.submit"
                  :native-value="1"
                  type="is-success">
                  <b-icon icon="check"></b-icon>
                  <span>Yes</span>
                </b-radio-button>
                <b-radio-button
                  v-model="user.partner_profile.submit"
                  :native-value="0"
                  type="is-danger">
                  <b-icon icon="close"></b-icon>
                  <span>No</span>
                </b-radio-button>
              </b-field>
            </td>
          </tr>
          <tr v-if="permission.update">
            <th>Approved</th>
            <td>
              <b-field>
                <b-radio-button
                  v-model="user.partner_profile.approval"
                  :native-value="1"
                  type="is-success">
                  <b-icon icon="check"></b-icon>
                  <span>Yes</span>
                </b-radio-button>
                <b-radio-button
                  v-model="user.partner_profile.approval"
                  :native-value="0"
                  type="is-danger">
                  <b-icon icon="close"></b-icon>
                  <span>No</span>
                </b-radio-button>
              </b-field>
            </td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Contact Information">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Contact Person</th>
            <td>{{ user.partner_profile.contact_person }}</td>
          </tr>
          <tr>
            <th>Portrait</th>
            <td v-if="user.partner_profile.logo">
              <figure class="image" style="width:400px">
                <img :src="'/file/' + user.partner_profile.logo.disk_name" style="display:block;width:100%;height:auto">
              </figure>
            </td>
          </tr>
          <tr>
            <th>Job Title</th>
            <td>{{ user.partner_profile.title }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Email</th>
            <td>{{ user.partner_profile.email }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Phone</th>
            <td>{{ user.partner_profile.phone }}</td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Partner Information" v-if="permission.private">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Organization Name</th>
            <td>{{ user.partner_profile.company_name}}</td>
          </tr>
          <tr>
            <th>Organization Description</th>
            <td>{{ user.partner_profile.company_description}}</td>
          </tr>
          <tr>
            <th>Organization Logo</th>
            <td v-if="user.partner_profile.real_logo">
              <figure class="image" style="width:400px">
                <img :src="'/file/' + user.partner_profile.real_logo.disk_name" style="display:block;width:100%;height:auto">
              </figure>
            </td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Preference" v-if="permission.private">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Participating Competitions</th>
            <td>
              <ul>
                <li v-for="competition in user.partner_profile.participating_competitions">
                  {{ competition.competition.name }} - {{ competition.competition.city }}
                </li>
              </ul>
            </td>
          </tr>
          <tr>
            <th>Reason to be a Partner</th>
            <td>{{ user.partner_profile.reason}}</td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Uploads" v-if="permission.private">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Resume</th>
            <td v-if="user.partner_profile.document">
              <span class="fa fa-file"></span> {{ user.partner_profile.document.filename }}
              <a class="button is-link is-small" :href="'/file/' + user.partner_profile.document.disk_name">Download</a>
              <a
                class="button is-small"
                target="_blank"
                :href="'/file/' + user.partner_profile.document.disk_name + '?preview'">
                Preview <i class="fa fa-external-link"></i>
              </a>
            </td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
    </b-tabs>
  </section>
  <footer class="modal-card-foot">
    <button class="button" type="button" @click="$parent.close()">Close</button>
  </footer>
</div>
        `,
        mounted() {

        },
        watch: {
            'user.partner_profile.submit': function (value) {
                const vm = this;
                axios.put('/api/v1/partners/' + this.user.id, {submit: value}).then(function () {
                    vm.$toast.open({
                        message: 'partner account ' + (value === 1 ? 'submitted' : 'unsubmitted') + '!',
                        type: 'is-success'
                    });
                });
            },
            'user.partner_profile.approval': function (value) {
                const vm = this;
                axios.put('/api/v1/partners/' + this.user.id, {approval: value}).then(function () {
                    vm.$toast.open({
                        message: 'partner account ' + (value === 1 ? 'approved' : 'unapproved') + '!',
                        type: 'is-success'
                    });
                });
            }
        }
    };

    export default {
        props: ['user', 'copy', 'permission'],
        methods: {
            detailModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/partners/' + this.user.id).then(function (response) {
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
                        user: this.copy,
                        permission: this.permission
                    }
                })
            }
        }
    }
</script>