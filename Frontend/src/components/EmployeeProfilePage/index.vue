<template>
  <div class="wrap container-fluid p-0 container-one-page">
    <app-header></app-header>
    <div class="page-container">

      <nav>
        <router-link to="/employees-list">
          <svg class="back-icon" width="10" height="20" x="0px" y="0px" viewBox="0 0 477.175 477.175" style="enable-background:new 0 0 477.175 477.175;" xml:space="preserve">
            <g><path d="M145.188,238.575l215.5-215.5c5.3-5.3,5.3-13.8,0-19.1s-13.8-5.3-19.1,0l-225.1,225.1c-5.3,5.3-5.3,13.8,0,19.1l225.1,225 c2.6,2.6,6.1,4,9.5,4s6.9-1.3,9.5-4c5.3-5.3,5.3-13.8,0-19.1L145.188,238.575z" /></g>
          </svg>
          Back to Employees List
        </router-link>
      </nav>
      
      <profile :data="data" :id="id" />

      <div class="worked_time">
        <bar-chart
          title="Worked Time"
          :values="chartData"
          units="h">
          <!-- :tooltip-text-cb="({ yLabel }) => 'Time: ' + yLabel" -->
          <div class="chart_tabs">
            <small-btn arrow text="April" :active="range === 'month'" @click="range = 'month'" />
            <small-btn arrow text="2017" :active="range === 'year'" @click="range = 'year'" />
          </div>
        </bar-chart>
      </div>

      <log :data="data"/>

      <app-footer />

    </div>
  </div>
</template>

<script>

export default {

  props: ['id'],

  data() {
    return {
      data: {},
      chartData: [],
      range: 'month'
    }
  },

  // created() {
  //   this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}/current_month_history`).then(res => {
  //       this.data = res.body
  //       this.chartData = this.data.shifts.map( ({total_worked_time, date_start}) => ({title: this.$moment(date_start).format('DD MMM'), value: Math.round(total_worked_time/3600)}) )
  //     })
  // },

  watch: {
    range: {
      handler(range) {
        if (range === 'month') {
          this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}/current_month_history`).then(res => {
            this.data = res.body
            this.chartData = this.data.shifts.map( ({total_worked_time, date_start}) => ({title: this.$moment(date_start).format('DD MMM'), value: Math.round(total_worked_time/3600)}) )
          })
        } else {
          this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}/current_year_history`).then(res => {
            this.chartData = res.body.map( ({total_work_time}, index) => ({title: this.$moment(index, 'M').format('MMMM'), value: Math.round(total_work_time/3600)}) )
          })
        }
      },
      immediate: true
    }
  },

  components: {
    appHeader: require('../Header'),
    appFooter: require('../Footer'),
    barChart: require('../Common/BarChart'),
    smallBtn: require('../Common/SmallBtn'),
    profile: require('./Profile'),
    log: require('./Log')
  }
}
</script>

<style lang="scss" src="./style.scss" scoped></style>
