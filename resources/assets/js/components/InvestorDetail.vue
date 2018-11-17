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
    <p class="modal-card-title">Investor Detail</p>
  </header>
  <section class="modal-card-body">
    <b-tabs>
      <b-tab-item label="Personal">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Name</th>
            <td>{{ user.first_name}} {{ user.last_name}}</td>
          </tr>
          <tr>
            <th>Portrait</th>
            <td>
              <figure class="image" style="width:400px" v-if="user.investor_profile.photo">
                <img :src="'/file/' + user.investor_profile.photo.disk_name" style="display:block;width:100%;height:auto">
              </figure>
            </td>
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
            <td>{{ user.investor_profile.refer }}</td>
          </tr>
          <tr v-if="permission.update">
            <th>Submitted</th>
            <td>
              <b-field>
                <b-radio-button
                  v-model="user.investor_profile.submit"
                  :native-value="1"
                  type="is-success">
                  <b-icon icon="check"></b-icon>
                  <span>Yes</span>
                </b-radio-button>
                <b-radio-button
                  v-model="user.investor_profile.submit"
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
                  v-model="user.investor_profile.approval"
                  :native-value="1"
                  type="is-success">
                  <b-icon icon="check"></b-icon>
                  <span>Yes</span>
                </b-radio-button>
                <b-radio-button
                  v-model="user.investor_profile.approval"
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
      <b-tab-item label="Company">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Company Name</th>
            <td>{{ user.investor_profile.company_name}}</td>
          </tr>
          <tr>
            <th>Company Description</th>
            <td>{{ user.investor_profile.company_description}}</td>
          </tr>
          <tr>
            <th>Job Title</th>
            <td>{{ user.investor_profile.title }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Phone</th>
            <td>{{ user.investor_profile.phone}}</td>
          </tr>
          <tr>
            <th>Education</th>
            <td>{{ user.investor_profile.education}}</td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Preference">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Participating Competitions</th>
            <td>
              <ul>
                <li v-for="competition in user.investor_profile.participating_competitions">
                  {{ competition.competition.name }} - {{ competition.competition.city }}
                </li>
              </ul>
            </td>
          </tr>
          <tr>
            <th>Looking tech in Areas</th>
            <td class="content">
              <ul>
                <li v-for="industry in user.investor_profile.interested_industries">
                  {{ industry.name }}
                  <ul>
                    <li v-for="subIndustry in user.investor_profile.interested_sub_industries.filter(subIndustry => {
                      return subIndustry.industry_id === industry.id;
                    })">
                      {{ subIndustry.name }}
                    </li>
                  </ul>
                </li>
              </ul>
            </td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Uploads" v-if="permission.private">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Resume</th>
            <td v-if="user.investor_profile.resume">
              <span class="fa fa-file"></span> {{ user.investor_profile.resume.filename }}
              <a class="button is-link is-small" :href="'/file/' + user.investor_profile.resume.disk_name">Download</a>
              <a
                class="button is-small"
                target="_blank"
                :href="'/file/' + user.investor_profile.resume.disk_name + '?preview'">
                Preview <i class="fa fa-external-link"></i>
              </a>
            </td>
          </tr>
          <tr>
            <th>Linkedin</th>
            <td>{{ user.investor_profile.linkedin}}</td>
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
        watch: {
            'user.investor_profile.submit': function(value) {
                const vm = this;
                axios.put('/api/v1/investors/' + this.user.id, {submit: value}).then(function () {
                    vm.$toast.open({
                        message: 'Investor account ' + (value === 1 ? 'submitted' : 'unsubmitted') + '!',
                        type: 'is-success'
                    });
                });
            },
            'user.investor_profile.approval': function(value) {
                const vm = this;
                axios.put('/api/v1/investors/' + this.user.id, {approval: value}).then(function () {
                    vm.$toast.open({
                        message: 'Investor account ' + (value === 1 ? 'approved' : 'unapproved') + '!',
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
                axios.get('/api/v1/investors/' + this.user.id).then(function (response) {
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