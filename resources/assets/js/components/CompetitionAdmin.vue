<template>
  <section>
    <b-table
      :data="competitions"
      :loading="loading">
      <template slot-scope="props">
        <b-table-column field="id" label="#" width="40" numeric>
          {{ props.index + 1 }}
        </b-table-column>

        <b-table-column label="Type">
          {{ props.row.name }}
        </b-table-column>

        <b-table-column  label="Location">
          <competition-detail :competition="props.row" :permission="permission"></competition-detail>
        </b-table-column>

        <b-table-column label="Industries">
          <b-taglist>
            <b-tag
              type="is-info"
              :title="industry.name"
              v-for="industry in props.row.industries"
              :key="industry.id">
              {{ industry.abbr }}
            </b-tag>
          </b-taglist>
        </b-table-column>

        <b-table-column label="Date" centered sortable>
          <span class="tag">
            {{ props.row.date ? new Date(props.row.date).toLocaleDateString() : 'TBD' }}
          </span>
        </b-table-column>

        <b-table-column label="Deadline" centered sortable>
          <span class="tag">
            {{ props.row.deadline ? new Date(props.row.deadline).toLocaleDateString() : 'TBD'}}
          </span>
        </b-table-column>

        <b-table-column label="Companies">
          {{ props.row.total_companies }}
        </b-table-column>

        <b-table-column label="Promoted">
          {{ props.row.total_promoted }}
        </b-table-column>

        <b-table-column label="Judges">
          {{ props.row.total_judges }}
        </b-table-column>

        <b-table-column label="Action" v-if="permission.private">
          <span style="cursor: pointer" class="icon is-small">
          </span>
        </b-table-column>
      </template>
    </b-table>
  </section>
</template>

<script>
    import CompetitionDetail from "./CompetitionDetail";

    export default {
        props: ['permission'],
        components: {CompetitionDetail},
        data() {
            return {
                competitions: [],
                loading: false
            }
        },
        methods: {
            index() {
                var instance = this;
                instance.loading = true;
                axios.get('/api/v1/competitions').then(function (response) {
                    instance.competitions = response.data;
                    instance.loading = false;
                });
            }
        },
        mounted: function() {
            this.index();
        }
    }
</script>