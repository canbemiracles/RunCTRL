<template>
  <div class="log">
    <h2>Last Month Log</h2>
    <table v-if="data.shifts">
      <tr >
        <th>Date</th>
        <th>Branch</th>
        <th>Region</th>
        <th>Station</th>
        <th>Role</th>
        <th>Start shift</th>
        <th>total</th>
        <th>salary</th>
      </tr>

      <tr v-for="(shift, i) in data.shifts.slice(0, count)">
        <td class="date">{{ shift.date_start | moment('DD MMM YYYY') }}</td>
        <td class="branch">{{ shift.branch_geo ? shift.branch_geo.street_address : ''}}</td>
        <td class="region">{{ shift.branch_geo ? shift.branch_geo.region : '' }}</td>
        <td class="station">{{ shift.station }}</td>
        <td class="role">{{ shift.role }}</td>
        <td class="start_shift">
          <span>{{ shift.date_start | moment('H:MM a') }}</span>
          -
          <span>{{ shift.date_end | moment('H:MM a') }}</span>
        </td>
        <td class="total">{{ shift.total_worked_time | moment('H:MM') }}</td>
        <!-- TODO: fix duration -->
        <td class="salary">
          <span>
            <span>{{ '$' + Math.round(shift.total_worked_time * data.hourly_rate / 3600) }}</span>
            <span class="hourly">{{ ' / $' + data.hourly_rate + '/h' }}</span>
          </span>
          <div class="indicators">
            <indicator :value="shift.completed_tasks" />
            <indicator :value="shift.problem_tasks" color="red" />
          </div>
        </td>
      </tr>

    </table>
    <button
      v-if="data.shifts && data.shifts.length > 10"
      class="load_more_btn"
      @click.prevent="count += 10">
      Load More
  </button>
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object
    }
  },
  data() {
    return {
      count: 10
    }
  },

  components: {
    indicator: require('../../Common/Indicator')
  }
}
</script>

<style lang="scss" src="./style.scss" scoped></style>
