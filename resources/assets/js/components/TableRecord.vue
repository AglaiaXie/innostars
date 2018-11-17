<template>
  <div class="columns">
    <div class="column is-narrow">
      <b-taglist attached class="level-left">
        <b-tag type="is-dark">Table</b-tag>
        <b-tag type="is-info">{{ tableNumber}}</b-tag>
      </b-taglist>
    </div>
    <div class="column">
      <div class="columns is-multiline is-gapless">
        <div class="column is-narrow" v-for="timeSlot in timeSlots" :key="timeSlots.id">
          <table-detail :time_slot="timeSlot" :permission="permission" :event="event"></table-detail>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
    import TableDetail from "./TableDetail";
    export default {
        components: {TableDetail},
        props: ['tableNumber', 'timeSlots', 'permission', 'event'],
    }
</script>