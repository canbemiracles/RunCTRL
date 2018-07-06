<template>
<b-modal id="branchStatus"
         class="branchStatus"
         hide-footer>
  <div slot="modal-header"
       class="header-report green d-flex">
    <div class="title">Branch Status Report</div>
    <button type="button"
            aria-label="Close"
            class="close"
            @click="hideModal">×</button>
  </div>
  <div class="branch-status-top">
    <div class="modal-row">
      <div class="branch-name">{{ branchData && branchData.geographical_area && branchData.geographical_area.street_address }}</div>
      <div class="report-time d-flex">
        Today at {{ currentTime }}
      </div>
    </div>
    <div class="modal-row info-top">
      <div class="col-3 modal-col">
        <div class="sub-title">Workday Start</div>
        <div class="info-top-value"
             :class="{ 'not-started' : workDayStart == 'Not started' }">{{ workDayStart }}</div>
        <div class="date"
             v-if="workDayStart == 'Not started'">{{ $moment() | moment(dateFormatValue)}}</div>
        <div class="date"
             v-if="data && data.workday_start">{{ $moment(data.workday_start) | moment(dateFormatValue)}}</div>
      </div>
      <div class="col-3 modal-col">
        <div class="sub-title">Current Time</div>
        <div class="info-top-value">{{ currentTime }}</div>
        <div class="date">{{ $moment() | moment(dateFormatValue)}}</div>
      </div>
      <div class="col-3 modal-col justify-content-start">
        <div class="sub-title">Total Worked Time</div>
        <div class="info-top-value">{{ totalWorkTime }}</div>
      </div>
      <div class="col-3 modal-col justify-content-start">
        <div class="sub-title">Ratio</div>
        <div class="info-top-value">
          {{ ratio }}
        </div>
      </div>
    </div>
    <div class="modal-row">
      <div class="col-8 modal-col flex-row">
        <donut-chart :values="tasksCounts"
                     class="tasks-chart">
          <div class="total">
            <span class="total-value">{{ totalTasks }}</span>
            <div class="total-desc">TASKS</div>
          </div>
        </donut-chart>
        <counter class="task-count"
                 label="Done"
                 label-color="green"
                 :count="total_done_tasks" />
        <counter class="task-count"
                 label="Pending"
                 label-color="yellow"
                 :count="total_pending_tasks" />
        <counter class="task-count"
                 label="Missed"
                 label-color="red"
                 :count="total_problem_tasks" />
      </div>
      <div class="col-4 modal-col">
        <div class="sub-title">Cashier Reports</div>
        <div class="info-top-value">
          {{ data && data.total_cash | currency(0, 0) }}
        </div>
        <div class="sub-title">Credit, Cash, Checks</div>
        <div class="info-top-value d-flex">
          <div class="count-reports commodity-reports">
            <svg class="icon boxes-icn-panel"
                 width="27"><use xlink:href="images/icons-sprite.svg#boxes"></use></svg>
            <span>{{ data ? data.commodity_reports.length  : '' }}</span>
          </div>
          <div class="count-reports problem-reports">
            <svg class="icon warning-icn-panel"
                 width="27"><use xlink:href="images/icons-sprite.svg#warning"></use></svg>
            <span>{{ data ? data.problem_reports.length  : '' }}</span>
          </div>
        </div>
      </div>
    </div>
    <div class="employee-table-wrap" v-if="data && data.employees.length">
      <employees-table :employees="data && data.employees" :twelvehour="twelvehour"/>
    </div>
  </div>
  <div class="cashier">
    <h2>Cashier Report</h2>
    <div class="counters">
      <cashier-counter v-for="(item, ind) in income.cashier"
                       :title="item.payment_method"
                       :value="item.amount"
                       :key="ind">
        <svg width="25" height="21"><use :xlink:href="'images/icons-sprite.svg#' + cashIconMatch(item.payment_method)"></use></svg>
      </cashier-counter>
      <cashier-counter title="Overal:" :value="income.total" />
      <!-- <cashier-counter title="Credits:" value="0">
        <svg width="25" height="21"><use xlink:href="images/icons-sprite.svg#credits"></use></svg>
      </cashier-counter>
      <cashier-counter title="Cash:" value="0">
        <svg width="21"  height="20"><use xlink:href="images/icons-sprite.svg#cash"></use></svg>
      </cashier-counter>
      <cashier-counter title="Checks:" value="0">
        <svg width="25" height="16"><use xlink:href="images/icons-sprite.svg#checks"></use></svg>
      </cashier-counter>
      <cashier-counter title="PayPal:" :value="item.paypal ? item.paypal.amount : 0">
        <svg width="22" height="22"><use xlink:href="images/icons-sprite.svg#paypal"></use></svg>
      </cashier-counter>
      <cashier-counter title="Overal:" :value="income.total" /> -->

    </div>
  </div>
  <div class="reports">
    <reports-collapse type="problem" :reports="problem_reports">
      <svg class="icon warning-icn-panel"
           width="27"><use xlink:href="images/icons-sprite.svg#warning"></use></svg>
      <h2 class="problem">Problem Reports</h2>
    </reports-collapse>
    <reports-collapse v-if="comodity_reports.length" type="commodity" :reports="comodity_reports" :twelvehour="twelvehour">
      <svg class="icon boxes-icn-panel"
           width="27"><use xlink:href="images/icons-sprite.svg#boxes"></use></svg>
      <h2 class="commodity">Commodity Reports</h2>
    </reports-collapse>
  </div>
</b-modal>
</template>
<script>
import 'images/sprites/info-panel/boxes.svg';
import 'images/sprites/info-panel/warning.svg';
import 'images/sprites/cashier/cash.svg';
import 'images/sprites/cashier/checks.svg';
import 'images/sprites/cashier/credits.svg';
import 'images/sprites/cashier/paypal.svg';
import { mapGetters, mapActions } from 'vuex';
export default {
  props: {
    branchData: Object
  },
  data() {
    return {
      data: null,
      intervalTimeFunc: null,
      currentTime: null,
      twelvehour: true,
      total_done_tasks: 0,
      total_pending_tasks: 0,
      total_problem_tasks: 0,
      problem_reports: [],
      comodity_reports: [],
      income: {
        cashier: [],
        total: 0
      },
    }
  },
  created(){
     this.getCompanyData().then(()=>{
      if(this.timeFormat && this.timeFormat == 'hh:mm a'){
        this.twelvehour = true;
      }else{
        this.twelvehour = false;
      }
      this.getCurrentTime();
    });
  },
  mounted() {
    let unwatch = this.$watch('branchData', function(value) {
      if (value) {
        this.$http.get(`api/v1/branches/${this.branchData.id}/get_live_data`)
          .then(res => {
            this.data = res.body;
            this.total_done_tasks = this.data.tasks.finished.length;
            this.total_pending_tasks = this.data.tasks.pending.length;
            this.total_problem_tasks = this.data.tasks.problems.length;
          });

          this.$http.post(`api/v1/branches/${this.branchData.id}/get_income`, {
            group: 'payment_method',
            date_start: moment().startOf('day'),
            date_end: moment().endOf('day')
          }).then(res => {
            this.income = res.body
            console.log(res.body)
          })

          this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/reports?type=problem&branches[]=${this.branchData.id}`).then(
						res => this.problem_reports = res.body.items
					)
          this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/reports?type=commodity&branches[]=${this.branchData.id}`).then(
						res => this.comodity_reports = res.body.items
					)

      }
    });
    this.intervalTimeFunc = setInterval(() => {
      this.getCurrentTime();
    }, 30000);
  },
  computed: {
    ...mapGetters(['timeFormat', 'dateFormat']),
    workDayStart() {
      if (this.data && this.data.workday_start) {
        return moment.utc(this.data.workday_start)
          .format(this.twelvehour ? 'h:mm a' : 'HH:mm');
      }else {
        return 'Not started'
      }
    },
    dateFormatValue(){
      return this.dateFormat ? this.dateFormat : 'DD MMM, YYYY';
    },
    totalWorkTime() {
      if (this.data && this.data.total_work_time[0]) {
        let time = +this.data.total_work_time[0];
        let hours = ~~time;
        let minutes = Math.round((time - hours) * 60);
        return hours + 'h.' + minutes + 'm.';
      } else {
        return '0h. 00m.';
      }
    },
    ratio() {
      if (this.data && this.data.ratio[0]) {
        return Math.round(this.data.ratio[0] * 100) + '%';
      } else {
        return '0%';
      }
    },
    tasksCounts() {
      let arr = [this.total_done_tasks, this.total_pending_tasks, this.total_problem_tasks]
      return arr;
    },
    totalTasks() {
      return this.total_done_tasks + this.total_pending_tasks + this.total_problem_tasks;
    }
  },
  methods: {
    ...mapActions(['getCompanyData']),
    getCurrentTime() {
      this.currentTime = moment().format(this.twelvehour ? 'h:mm a' : 'HH:mm');
    },
    hideModal() {
      this.$root.$emit('bv::hide::modal', 'branchStatus');
    },
    cashIconMatch(payment){
      switch(payment){
        case 'paypal':
          return 'paypal';
        case 'cash':
          return 'cash'
        case 'checks':
          return 'checks'
        case 'credits':
          return 'credits'
        default:
          return 'paypal'      
      }
    },
  },
  components: {
    counter: require('../Common/LabelCount'),
    cashierCounter: require('../ReportsPage/SimplePopup/Counter'),
    donutChart: require('../Common/DonutChart'),
    employeesTable: require('./BranchStatusEmployeeTable'),
    reportsCollapse: require('./ReportsCollapse')
  },
  beforeDestroy() {
    clearInterval(this.intervalTimeFunc);
  }
}
</script>
<style lang='scss' src="./style.scss" scoped></style> 
<style lang='scss'> 
.modal-content {
    box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
    border-radius: 2px;
}

.fade {
    background-color: rgba(43, 69, 83, 0.2);
}
</style>
