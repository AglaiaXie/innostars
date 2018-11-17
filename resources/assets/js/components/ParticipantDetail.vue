<template>
  <section>
    <a @click="detailModal()">
      {{ user.company.name ? user.company.name : '--Not Available--' }}
    </a>
  </section>
</template>

<script>
    const ModalForm = {
        props: ['user', 'permission'],
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Personal Detail</p>
  </header>
  <section class="modal-card-body">
    <b-tabs>
      <b-tab-item label="Personal Info">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th> Name</th>
            <td>{{ user.first_name }} {{ user.last_name }}</td>
          </tr>
          <tr>
            <th> Contact Name</th>
            <td>{{ user.company.contact_name}}</td>
          </tr>
          <tr>
            <th>Portrait</th>
            <td>
              <figure v-if="user.company.contact_photo" class="image" style="width:400px">
                <img :src="'/file/' + user.company.contact_photo.disk_name" style="display:block;width:100%;height:auto">
              </figure>
            </td>
          </tr>
          <tr>
            <th>Title</th>
            <td>{{ user.company.contact_title }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Email</th>
            <td>{{ user.email }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Contact Email</th>
            <td>{{ user.company.contact_email }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Phone</th>
            <td>{{ user.company.contact_phone }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Referred by SXSW</th>
            <td>{{ user.sxsw ? 'Yes' : 'No' }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>How did you hear about us?</th>
            <td>{{ user.company.refer }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Have you been to China before?</th>
            <td>{{ user.company.been_to_china }}</td>
          </tr>
          <tr v-if="permission.update">
            <th>Submitted</th>
            <td>
              <b-field>
                <b-radio-button
                  v-model="user.company.submit"
                  :native-value="1"
                  type="is-success">
                  <b-icon icon="check"></b-icon>
                  <span>Yes</span>
                </b-radio-button>
                <b-radio-button
                  v-model="user.company.submit"
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
                  v-model="user.company.approval"
                  :native-value="1"
                  type="is-success">
                  <b-icon icon="check"></b-icon>
                  <span>Yes</span>
                </b-radio-button>
                <b-radio-button
                  v-model="user.company.approval"
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
      <b-tab-item label="Company Info">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Company Name</th>
            <td>{{ user.company.name }}</td>
          </tr>
          <tr>
            <th>Company Description</th>
            <td>{{ user.company.description }}</td>
          </tr>
          <tr>
            <th>Company Logo</th>
            <td>
              <figure v-if="user.company.logo" class="image" style="width:400px">
                <img :src="'/file/' + user.company.logo.disk_name" style="display:block;width:100%;height:auto">
              </figure>
            </td>
          </tr>
          <tr>
            <th>Year Established</th>
            <td>{{ user.company.established }}</td>
          </tr>
          <tr>
            <th>Type</th>
            <td>{{ user.company.type }}</td>
          </tr>
          <tr>
            <th>Size</th>
            <td>{{ user.company.size }}</td>
          </tr>
          <tr>
            <th>Website</th>
            <td v-if="user.company.website">
              <a target="_blank" :href="user.company.website">
                {{ user.company.website }} <span class="icon"><i class="fa fa-external-link"></i></span>
              </a>
            </td>
          </tr>
          <tr v-if="permission.private">
            <th>Address Line 1</th>
            <td>{{ user.company.address }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Address Line 2</th>
            <td>{{ user.company.address2 }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>City</th>
            <td>{{ user.company.city }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>State</th>
            <td>{{ user.company.state }}</td>
          </tr>
          <tr v-if="permission.private">
            <th>Zip Code</th>
            <td>{{ user.company.zip_code }}</td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Project Information">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Preliminary Stage City</th>
            <td>
              <span v-if="user.company.preliminary_competition">
                {{ user.company.preliminary_competition.city }}
                <b-tag
                  class="tag">{{ new Date(user.company.preliminary_competition.date).toLocaleDateString()  }}</b-tag>
              </span>
            </td>
          </tr>
          <tr>
            <th>Industries of Focus</th>
            <td>
              <b v-if="user.company.industry" v-html="user.company.industry.name"></b>
              <p v-if="user.company.sub_industry" v-html="user.company.sub_industry.name"></p>
            </td>
          </tr>
          <tr>
            <th>Technology/Project Name</th>
            <td>{{ user.company.project_name }}</td>
          </tr>
          <tr>
            <th>Technology/Project Description</th>
            <td>{{ user.company.project_description }}</td>
          </tr>
          <tr>
            <th>Technology/Project Financing Stage</th>
            <td>{{ user.company.project_stage }}</td>
          </tr>
          <tr>
            <th>Patent(s)</th>
            <td>{{ user.company.patents }}</td>
          </tr>
          <tr>
            <th>Preferred Way of Cooperation</th>
            <td class="content">
              <ul v-if="user.company.cooperation">
                <li v-for="way in user.company.cooperation.split(',')">
                  {{ way }}
                </li>
              </ul>
            </td>
          </tr>
          <tr>
            <th>Team Introduction</th>
            <td>{{ user.company.team_description }}</td>
          </tr>
          <tr>
            <th>Technical Requirements</th>
            <td>{{ user.company.tech_requirement }}</td>
          </tr>
          <tr>
            <th>Competitive Advantages and Sustainability of the Technology/Project</th>
            <td>{{ user.company.tech_advantage }}</td>
          </tr>
          <tr>
            <th>Risk of Technology</th>
            <td>{{ user.company.tech_risk }}</td>
          </tr>
          <tr>
            <th>Prospective Chinese Market Value/Business Value</th>
            <td>{{ user.company.business_value }}</td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Uploads" v-if="permission.private">
        <table class="table is-fullwidth">
          <tbody>
          <tr>
            <th>Executive Summary</th>
            <td>
              <div v-if="user.company.executive_summary">
                <span class="fa fa-file"></span> {{ user.company.executive_summary.filename }}
                <a class="button is-link is-small" :href="'/file/' + user.company.executive_summary.disk_name">Download</a>
                <a
                  class="button is-small"
                  target="_blank"
                  :href="'/file/' + user.company.executive_summary.disk_name + '?preview'">
                  Preview <i class="fa fa-external-link"></i>
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <th>Pitch Deck</th>
            <td>
              <div v-if="user.company.pitch_deck">
                <span class="fa fa-file"></span> {{ user.company.pitch_deck.filename }}
                <a class="button is-link is-small" :href="'/file/' + user.company.pitch_deck.disk_name">Download</a>
                <a
                  class="button is-small"
                  target="_blank"
                  :href="'/file/' + user.company.pitch_deck.disk_name + '?preview'">
                  Preview <i class="fa fa-external-link"></i>
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <th>Additional Info</th>
            <td>
              <div v-if="user.company.other_file_1">
                <span class="fa fa-file"></span> {{ user.company.other_file_1.filename }}
                <a class="button is-link is-small" :href="'/file/' + user.company.other_file_1.disk_name">Download</a>
                <a
                  class="button is-small"
                  target="_blank"
                  :href="'/file/' + user.company.other_file_1.disk_name + '?preview'">
                  Preview <i class="fa fa-external-link"></i>
                </a>
              </div>
            </td>
          </tr>
          <tr>
            <th>Additional Info</th>
            <td>
              <div v-if="user.company.other_file_2">
                <span class="fa fa-file"></span> {{ user.company.other_file_2.filename }}
                <a class="button is-link is-small" :href="'/file/' + user.company.other_file_2.disk_name">Download</a>
                <a
                  class="button is-small"
                  target="_blank"
                  :href="'/file/' + user.company.other_file_2.disk_name + '?preview'">
                  Preview <i class="fa fa-external-link"></i>
                </a>
              </div>
            </td>
          </tr>
          </tbody>
        </table>
      </b-tab-item>
      <b-tab-item label="Competitions" v-if="permission.private">
        <table class="table is-fullwidth">
          <thead>
          <tr>
            <th>Type</th>
            <th>Name</th>
            <th>Score</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="joined_competition in user.company.joined_competitions">
            <td>{{ joined_competition.competition.name }}</td>
            <td>{{ joined_competition.competition.city }}</td>
            <td>{{ joined_competition.score_average }}</td>
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
            'user.company.submit': function(value) {
                console.log('triggered');
                const vm = this;
                axios.put('/api/v1/participants/' + this.user.id, {submit: value}).then(function () {
                    vm.$toast.open({
                        message: 'Contestant account ' + (value === 1 ? 'submitted' : 'unsubmitted') + '!',
                        type: 'is-success'
                    });
                });
            },
            'user.company.approval': function(value) {
                const vm = this;
                axios.put('/api/v1/participants/' + this.user.id, {approval: value}).then(function () {
                    vm.$toast.open({
                        message: 'Contestant account ' + (value === 1 ? 'approved' : 'unapproved') + '!',
                        type: 'is-success'
                    });
                });
            }
        }
    };

    export default {
        props: ['user', 'permission'],
        data() {
            return {
                copy: null
            }
        },
        methods: {
            detailModal() {
                const instance = this;
                instance.loadingComponment = this.$loading.open();
                axios.get('/api/v1/participants/' + this.user.id).then(function (response) {
                    instance.loadingComponment.close();
                    instance.copy = response.data;
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