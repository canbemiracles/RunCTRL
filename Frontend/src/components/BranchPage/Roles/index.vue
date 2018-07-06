<template>
    <div v-if="role" @mouseover="toggleExpanded()" @mouseout="toggleExpanded()" class="role-card" :class="[isOverTime(), {'over-much' : !role.time_open && type=='role'}]">
        <div class="preview d-flex flex-column">
            <div class="block-top">
                <div class="avatar">
                    <template v-if="type=='role' && role.time_open">
                        <canvas class="role-progress-chart" width="132" height="132" ref="chart"></canvas>
                        <canvas class="role-progress-chart over-time" width="132" height="132" ref="chartOverTime"></canvas>
                    </template>
                    <div v-if="type=='employee'" class="wrap-static-circle">
                        <div class="check-state" v-if="selected">
                            <svg width="20" height="100%" class="icon-check">
                                <use xlink:href="images/icons-sprite.svg#check-usage"></use>
                            </svg>
                        </div>
                    </div>
                    <div v-if="type=='role' && !role.time_open" class="wrap-static-circle" :style="{borderColor: '#' + role.color}">
                        <div class="check-state not-stationed">!</div>
                    </div>
                    <div class="pic-holder" v-if="(type=='role' && role.time_open) || type=='employee'"
                    :style="{backgroundImage: 'url(' + (role.avatar ? $http.options.root +'/'+ role.avatar.path : 'images/user_default.jpg') +')' }"
                    :class="{inActive: type=='employee' && !selected}"
                    ></div>
                    <div class="pic-holder not-stationed" :class="{black: type == 'employee' && !role.avatar}" v-if="(type=='role' && !role.time_open) || (type == 'employee' && !role.avatar)" v-color:bg="role.color">
                        {{role.role ? role.role : role.first_name + ' ' + role.last_name | abbr}}
                    </div>
                </div>
                <div class="employee-name">{{role.first_name}} {{role.last_name}}</div>
                <div class="employee-post" v-color="role.color">{{role.role}}</div>
                <div class="employee-stationed" v-if="!role.time_open && type =='role'">Not Stationed</div>
                <div class="working-time" :class="isOverTime()">{{work_time}}</div>
                <div class="working-time" v-if="type =='employee'">{{ employee_work_time }}</div>
            </div>
            <div class="block-bottom flex-column" v-if="role.time_open || type =='employee'">
                <div class="state-tasks">
                    <div class="task-state d-flex align-items-center">
                        <ready :disabled="isTaskDisabled(role.ready)"></ready>
                        <span class="amount">{{role.ready}}</span>
                    </div>
                    <div class="task-state d-flex align-items-center">
                        <pending :disabled="isTaskDisabled(role.pending)"></pending>
                        <span class="amount">{{role.pending}}</span>
                    </div>
                    <div class="task-state d-flex align-items-center">
                        <cancel :disabled="isTaskDisabled(role.cancel)"></cancel>
                        <span class="amount">{{role.cancel}}</span>
                    </div>
                </div>
                <transition name="fade">
                    <div class="chart-status" v-if="expanded">
                        <donut-chart :values="[role.ready, role.pending, role.cancel]" class="chart">
                            <div class="total">
                                <span class="total-count">{{ parseInt(role.ready) + parseInt(role.pending) + parseInt(role.cancel)}}</span>
                                <span class="desc">TASKS</span>
                            </div>
                        </donut-chart>
                    </div>
                </transition>
            </div>
            <div class="block-bottom-empty" v-else></div>
        </div>
        <transition name="fade">
            <div v-if="expanded" class="content-right d-flex flex-column">
                <div class="block-top-right">
                    <div class="info-title">WORKING HOURS</div>
                    <div class="time-row d-flex info-content">
                        <div class="time time-start" :class="{ 'time-late' : isLate(role)}">
                            {{role.date_start | moment(timeFormat ? timeFormat: 'h:mm a')}}
                        </div>
                        <div class="time time-end">
                            WORKING
                        </div>
                    </div>
                    <div class="info-content">
                        <div class="info-title">WORKED TODAY</div>
                        <div class="time-worked">{{ formatedWorkedTime }}</div>
                        <div class="time-overwork">{{ overTimeFormat }}</div>
                    </div>
                    <div class="info-content">
                        <div class="info-title">EARNED TODAY</div>
                        <div class="earned">
                            <span class="total">{{ totalEarned | currency }}</span>
                            <span class="rate">({{ role.hourly_rate | currency(0, 0) }}/h.)</span>
                        </div>
                    </div>
                </div>
                <div class="block-bottom-right" >
                    <div class="problems" v-if="role.problem_reports">
                        <div class="info-content">
                            <div class="info-title">PROBLEMS</div>
                            <ul class="problems-list">
                              <li class="problem-info" 
                              v-for="(item, ind) in role.problem_reports"
                              :key="ind"
                              >
                                {{ item.title }}
                              </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </transition>
        <transition name="fade">
            <div class="shadow-expanded" v-if="expanded"></div>
        </transition>
    </div>
</template>
<script>
import Chart from "chart.js";
import "images/sprites/check.svg";
import pallete from "../../../stylesheets/index";
import { abbrName } from "../../Common/utils.js";
import {mapGetters} from 'vuex';
export default {
  props: {
    role: Object,
    type: String,
    selected: Boolean,
    employee_work_time: String
  },
  data: function() {
    return {
      totalTime: 8,
      percentTime: null,
      overTime: 0,
      styleObj: {},
      chart: null,
      overChart: null,
      timeIntervalFunc: null,
      updateInterval: 60000,
      expanded: false,
      work_time: null,
      overTimeMs: null
    };
  },
  computed: {
    ...mapGetters(['company', 'timeFormat']),
    overTimeFormat(){
        let over = moment.duration(this.overTimeMs);
        let result='';
        let min = over.minutes();
        let hours = over.hours(); 
        if(hours){
            result += moment.duration(hours, 'hours').humanize();
        }
        if(min){
           result += ` ${min} minute${ min == 1 ? '': 's'}`;
        }
        if(result){
            result+=' overwork';
        }
        return result;
    },
    totalEarned(){
        if(!$.isEmptyObject(this.company)){
            let overRate = this.company.overtime_hourly_rate;
            let overEarn=0, normalEarn;
            if(this.overTimeMs){
                overEarn = ((overRate/100 + 1) * this.role.hourly_rate * (moment.duration(this.overTimeMs).hours() + moment.duration(this.overTimeMs).minutes() / 60));
                normalEarn = (this.role.hourly_rate * (moment.duration(this.totalTime).hours() + moment.duration(this.totalTime).minutes() / 60));
            }else{
              normalEarn = (this.role.hourly_rate * (moment.duration(this.work_time).hours() + moment.duration(this.work_time).minutes() / 60));
            }
            return (normalEarn + overEarn).toFixed(2);
        }
    },
    formatedWorkedTime(){
        let  dur= moment.duration(this.work_time);
        dur = dur.hours() + dur.minutes() / 60;
        return Math.round(dur*100)/100 + ' hours';
    },
  },
  filters: {
    abbr(value) {
      return abbrName(value);
    }
  },
  mounted() {
    if (this.type == "role" && this.role.time_open) {
      this.setPercentTime();
      this.createChart();

      this.timeIntervalFunc = setInterval(() => {
        this.setPercentTime();
        this.updateChart();
      }, this.updateInterval);
    }
  },
  beforeDestroy(){
    clearInterval(this.timeIntervalFunc);
  },
  components: {
    ready: require("../../StateTask/ready"),
    pending: require("../../StateTask/pending"),
    cancel: require("../../StateTask/cancel"),
    donutChart: require('../../Common/DonutChart'),
  },
  methods: {
    isOverTime() {
      //если сверхурочные выше 15%
      if (this.overTime > 15) {
        return "over-much";
      } else if (this.overTime > 0) {
        return "over";
      }
    },
    isLate(role){
      let dateStart = moment(role.date_start);
      let startShift = moment(role.time_open);
      if(dateStart.isAfter(startShift)){
          return true;
      }
      return false;
    },
    isTaskDisabled(value) {
      return value == 0 || value == undefined;
    },
    getWorkingTimeForRoles() {
      let timeCurr = new Date().getTime();
      let startTime = new Date(this.role.date_start).getTime();
      let diff = timeCurr - startTime;
      return diff;

    },
    setPercentTime() {
      if (this.type == "role") {
        let timeStart = new Date(this.role.time_open);
        let timeEnd = new Date(this.role.time_close);
        this.totalTime = timeEnd.getTime() - timeStart.getTime();
        var work_time = this.getWorkingTimeForRoles();
        let timeWork = moment.duration(work_time, "milliseconds");
        this.work_time = timeWork.format("HH:mm", {
          trim: false
        });
        if (work_time > this.totalTime) {
          this.overTime = this.overTimeMs =  work_time - this.totalTime;
          work_time = this.totalTime;
          this.overTime = this.overTime / this.totalTime * 100;
        }
        this.percentTime = work_time / this.totalTime * 100;
      } else {
        this.percentTime = 100;
      }
    },
    setChartData() {
      let color = "#" + this.role.color;
      if(this.$refs.chart){
         var ctx = this.$refs.chart.getContext("2d");
        if (this.percentTime > 90 && this.percentTime < 100) {
          var gradient = ctx.createLinearGradient(132, 132, 25, 5);
          gradient.addColorStop(0, color);
          gradient.addColorStop(0.8, color);
          gradient.addColorStop(0.95, "#f83c37");
          gradient.addColorStop(1, "#f83c37");
          color = gradient;
        }
      }
      let data = {
        datasets: [
          {
            data: [this.percentTime, 100 - this.percentTime],
            borderWidth: 0,
            backgroundColor: [color, "#e6ebed"]
          }
        ]
      };
      let data2 = {
        datasets: [
          {
            data: [this.overTime, 100 - this.overTime],
            borderWidth: 0,
            backgroundColor: ["#f83c37", "transparent"]
          }
        ]
      };
      return { data, data2 };
    },
    createChart() {
      let color = "#" + this.role.color;
      var ctx = this.$refs.chart.getContext("2d");
      let dataChart = this.setChartData();
      let vm = this;
      var ctx2 = this.$refs.chartOverTime.getContext("2d");
      this.chart = new Chart(ctx, {
        type: "doughnut",
        data: dataChart.data,
        options: {
          cutoutPercentage: 95,
          legend: { display: false },
          tooltips: {
            enabled: false
          },
          animation: {
            onComplete: function(animation) {
              vm.overChart = new Chart(ctx2, {
                type: "doughnut",
                data: dataChart.data2,
                options: {
                  cutoutPercentage: 94,
                  legend: { display: false },
                  tooltips: {
                    enabled: false
                  }
                }
              });
            }
          }
        }
      });
    },
    updateChart() {
      let dataChart = this.setChartData();
      this.chart.data.datasets = dataChart.data.datasets;
      if(this.overChart){
        this.overChart.data.datasets = dataChart.data2.datasets;
        this.overChart.options.animation.duration = 0;
        this.overChart.update();
      }
      this.chart.options.animation.duration = 0;
      this.chart.update();

    },
    toggleExpanded(){
        if(this.type=='role' && this.role.time_open){
          this.expanded =!this.expanded; 
        }
    }
  }
};
</script>
<style lang='scss' src='./style.scss' scoped></style>
<style lang="scss">
.chartjs-size-monitor {
  margin: auto;
}

.ct-series-b .ct-slice-donut-solid {
  fill: #e6ebed;
}

.ct-transparent {
  fill: transparent;
}

.ct-overTime {
  fill: #f83c37;
}
</style>
