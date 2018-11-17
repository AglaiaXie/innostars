<template>
  <a href="#" @click="assignModal()">
    <b-icon icon="plus" type="is-info"></b-icon>
  </a>
</template>

<script>
    import JudgeDetail from "./JudgeDetail";

    const ModalForm = {
        props: ['permission', 'company', 'judges'],
        components: {JudgeDetail},
        template: `<div class="modal-card">
  <header class="modal-card-head">
    <p class="modal-card-title">Assign Judge for {{ company.company.name }}</p>
  </header>
  <section class="modal-card-body">
    <b-table
      :data="scores"
      :loading="loading"
      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      @page-change="onPageChange"
      :current-page.sync="page">
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>

        <b-table-column label="Judge">
          <judge-detail :user="props.row.judge.judge.user" :permission="permission"></judge-detail>
        </b-table-column>

        <b-table-column label="Score">
          {{ props.row.total_score }}
        </b-table-column>

        <b-table-column label="Submitted">
          {{ props.row.submit ? 'Yes' : 'No' }}
        </b-table-column>
        <b-table-column label="">
          <span style="cursor: pointer" class="icon is-small has-text-danger">
            <i
              class="fa fa-remove"
              title="Remove Judge"
              v-on:click="removeJudge(props.row.id)"></i>
          </span>
        </b-table-column>
      </template>
    </b-table>
  </section>
  <footer class="modal-card-foot">
    <div class="field has-addons">
      <div class="control" ref="assign_element">
        <div class="select">
          <select v-model="form.assign_number">
            <option value="1">1 Judge</option>
            <option value="2">2 Judges</option>
            <option value="3">3 Judges</option>
            <option value="4">4 Judges</option>
            <option value="5">5 Judges</option>
            <option value="10">10 Judges</option>
            <option value="15">15 Judges</option>
            <option value="20">20 Judges</option>
            <option value="50">50 Judges</option>
          </select>
        </div>
      </div>
      <div class="control">
        <button class="button is-primary" @click="assign()">Auto Assign</button>
      </div>
    </div>
  </footer>
</div>
        `,
        data() {
            return {
                form: {
                    assign_number: 10,
                },
                scores: [],
                loading: false,
                page: 1,
                perPage: 20,
                totalPage: 0,
                total: 0,
                assign_loading: false
            }
        },
        methods: {
            save() {
                var instance = this;
                axios.put('/api/v1/companies/' + instance.company.id, instance.form).then(function () {
                    bus.$emit('company-reload');
                    instance.$parent.close();
                });
            },
            index() {
                this.loading = true;
                axios.get('/api/v1/companies/' + this.company.id + '/scores', {
                    params: {
                        page: this.page,
                        perPage: this.perPage,
                    }
                }).then(({data}) => {
                    this.scores = data.data;
                    this.total = data.total;
                    this.loading = false;
                });
            },
            assign() {
                var instance = this;
                instance.loadingComponent = instance.$loading.open({
                    container: instance.$refs.assign_element.$el
                });
                axios.post('/api/v1/companies/' + this.company.id + '/auto-assign', {
                    number: this.form.assign_number
                }).then(({data}) => {
                    instance.loadingComponent.close();
                    instance.$toast.open({
                        message: instance.form.assign_number + ' judge(s) assigned',
                        type: 'is-success'
                    });
                    instance.index();
                });
            },
            removeJudge(id) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Remove judge from this company',
                    message: 'Are you sure that you want to remove this judge?',
                    confirmText: 'Remove',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/companies/' + instance.company.id + '/scores/' + id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'Judge removed!',
                                    type: 'is-success'
                                });
                                instance.index();
                            })
                            .catch(function (error) {
                                instance.$toast.open({
                                    message: 'Error, unable to remove judge: ' + error.response.data,
                                    type: 'is-danger',
                                    duration: 10000
                                });
                            });
                    }
                });
            },
            onPageChange(page) {
                this.page = page;
                this.index()
            }
        },
        mounted: function () {
            this.index();
        }
    };

    export default {
        props: ['permission', 'company', 'judges'],
        methods: {
            assignModal() {
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