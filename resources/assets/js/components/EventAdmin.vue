<template>
  <section>
    <div class="level">
      <b-field grouped group-multiline class="level-left">
        <div class="control">
          <b-input v-model="searchKeyword" @keyup.enter.native="search" placeholder="Search Event Name">
          </b-input>
        </div>
        <div class="control">
          <b-select v-model="searchCompetition">
            <option value="">Search Competitions</option>
            <option v-for="competition in competitions" :value="competition.id">{{ competition.name }} - {{
              competition.city}}
            </option>
          </b-select>
        </div>
        <div class="control">
          <button class="button is-primary" v-on:click="search">Search搜索</button>
        </div>
        <div class="control">
          <button class="button" v-on:click="reset">Reset重置</button>
        </div>
      </b-field>
      <b-field class="level-right" v-if="permission.create">
        <div class="control">
          <event-create :permission="permission" :competitions="competitions"></event-create>
        </div>
      </b-field>
    </div>


    <b-table
      :data="events"
      :loading="loading"
      paginated
      backend-pagination
      :total="total"
      :per-page="perPage"
      @page-change="onPageChange"
      :current-page.sync="page"
      backend-sorting
      default-sort-direction="desc"
      :default-sort="[sortField, sortOrder]"
      @sort="onSort">
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>

        <b-table-column field="name" label="Name活动名">
          <event-detail
            :event="props.row"
            :permission="permission"
            v-if="permission.id !== props.row.user_id && !permission.override">
          </event-detail>
          <event-edit
            :event="props.row"
            :permission="permission"
            v-if="permission.id === props.row.user_id || permission.override"></event-edit>
        </b-table-column>

        <b-table-column label="Start Date开始日期" >
          <span class="tag">
            {{ props.row.start_date ? new Date(props.row.start_date.date).toLocaleDateString() : 'N/A' }}
          </span>
        </b-table-column>

        <b-table-column label="End Date结束日期" >
          <span class="tag">
            {{ props.row.end_date ? new Date(props.row.end_date.date).toLocaleDateString() : 'N/A' }}
          </span>
        </b-table-column>

        <b-table-column label="Attendants参会人数">
          {{ props.row.user_total }}
        </b-table-column>

        <b-table-column label="Total Slots对接容量">
          {{ props.row.slot_total }}
        </b-table-column>

        <b-table-column label="Empty Slots" v-if="permission.create">
          {{ props.row.slot_empty }}
        </b-table-column>

        <b-table-column label="Pending Slots" v-if="permission.create">
          {{ props.row.slot_pending }}
        </b-table-column>

        <b-table-column label="Confirmed Slots" v-if="permission.create">
          {{ props.row.slot_confirmed }}
        </b-table-column>

        <b-table-column label="Status" v-if="permission.create">
          {{ props.row.published ? 'Published' : 'Draft' }}
        </b-table-column>

        <b-table-column field="user.id" label="Organizer组织单位" >
          <partner-detail :user="props.row.user" :permission="permission"></partner-detail>
        </b-table-column>

        <b-table-column label="Action" v-if="permission.id === props.row.user_id || permission.override">
          <span style="cursor: pointer" class="icon is-small has-text-danger">
            <i
              class="fa fa-remove"
              title="Delete"
              v-on:click="deleteEvent(props.row.id)"></i>
          </span>
        </b-table-column>
      </template>
    </b-table>
  </section>
</template>

<script>
    import EventDetail from "./EventDetail";
    import EventCreate from "./EventCreate";
    import EventEdit from "./EventEdit";
    import PartnerDetail from "./PartnerDetail";

    export default {
        props: ['permission'],
        components: {PartnerDetail, EventDetail, EventCreate, EventEdit},
        methods: {
            index() {
                this.loading = true;
                return axios.get('/api/v1/events', {
                    params: {
                        keyword: this.searchKeyword,
                        competition: this.searchCompetition,
                        page: this.page,
                        perPage: this.perPage,
                        sortBy: this.sortField,
                        sortDirection: this.sortOrder
                    }
                }).then(({data}) => {
                    this.events = data.data;
                    this.total = data.total;
                    this.loading = false;
                });
            },
            onSort(field, order) {
                this.sortField = field;
                this.sortOrder = order;
                this.index()
            },
            onPageChange(page) {
                this.page = page;
                this.index()
            },
            search() {
                this.page = 1;
                this.index();
            },
            reset() {
                this.searchKeyword = '';
                this.searchCompetition = '';
                this.search();
            },
            deleteEvent(id) {
                const instance = this;
                instance.$dialog.confirm({
                    title: 'Deleting judge account',
                    message: 'Are you sure that you want to delete event?',
                    confirmText: 'Delete',
                    type: 'is-danger',
                    hasIcon: true,
                    onConfirm: function () {
                        axios.delete('/api/v1/events/' + id)
                            .then(function () {
                                instance.$toast.open({
                                    message: 'Event deleted!',
                                    type: 'is-success'
                                });
                                instance.search();
                            })
                            .catch(function (error) {
                                instance.$toast.open({
                                    message: 'Error, unable to delete event: ' + error.response.data,
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
            instance.index();
            bus.$on('event-saved', function (id) {
                instance.index();
            });
            axios.get('/api/v1/competitions?filterBy=allJudges').then(function (response) {
                instance.competitions = response.data;
            });
        },
        data() {
            return {
                events: [],
                competitions: [],
                loading: false,
                searchKeyword: '',
                searchCompetition: '',
                sortField: 'created_at',
                sortOrder: 'desc',
                page: 1,
                isPaginationSimple: false,
                defaultSortDirection: 'desc',
                perPage: 20,
                totalPage: 0,
                total: 0
            }
        }
    }
</script>