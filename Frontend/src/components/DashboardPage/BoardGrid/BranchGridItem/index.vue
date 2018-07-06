<template>
<div class="branch-user"
     :class="{'branch-user-problem': branch.tasks.problems.length > 0}">
  <div class="user-photo">
    <div class="branch-photo"
         v-if="branch.branch_manager && branch.branch_manager.avatar"
         :style="{ backgroundImage: `url(${$http.options.root}/${branch.branch_manager.avatar.path}`}"
         alt="userpic"></div>
    <div class="branch-photo"
         v-else
         :style="{ backgroundImage: 'url(' + require('images/user_default.jpg')+')'}"
         alt="userpic"></div>
    <div class="user-name"
         v-if="branch.branch_manager">{{branch.branch_manager ? `${branch.branch_manager.first_name} ${branch.branch_manager.last_name}` : '&nbsp;'}}</div>
    <div :class="{'user-status-off': branch.tasks.problems.length > 0, 'user-status-s': leftTime === 'status-closed'}"
         class="user-status-on"></div>
  </div>
  <div class="user-info">

    <div class="user-info-title">
      <h3 class="user-address">{{branch.geographical_area && branch.geographical_area.street_address? branch.geographical_area.street_address : '&nbsp;'}}</h3>
      <h4 class="user-city">{{branch.geographical_area && branch.geographical_area.region ? branch.geographical_area.region : '&nbsp;'}}</h4>
    </div>
    <div class="wrap-user-info-time">

      <ul class="user-info-time">
        <li :class="leftTime"
            class="wrap-time">{{getLeftTime()}}
          <ol v-if="leftTime === 'late'"
              class="detailed-time__wrap detailed-time">
            <li class="detailed-time-li">Open Time: <span>{{branch.expected_workday_start | moment(timeFormat ? timeFormat : 'h:mm a')}}</span></li>
            <li class="detailed-time-li"><strong>{{getLateTime()}} min late</strong></li>
          </ol>
        </li> -
        <li :class="rightTime"
            class="wrap-time">{{getRightTime()}}
          <ol v-if="leftTime === 'late'"
              class="detailed-time__wrap detailed-time">
            <li class="detailed-time-li">Open Time: <span>{{branch.expected_workday_start | moment(timeFormat ? timeFormat : 'h:mm a')}}</span></li>
            <li class="detailed-time-li"><strong>{{getLateTime()}} min late</strong></li>
          </ol>
        </li>
      </ul>
    </div>
  </div>
  <div class="user-panel-task d-flex justify-content-center">
    <div class="task__item task__wrap-ready d-flex justify-content-center">
      <ready></ready>
      <span class="task">{{branch.tasks.finished.length}}</span>
    </div>
    <div class="task__item task__wrap-pending d-flex justify-content-center">
      <pending></pending>
      <span class="task">{{branch.tasks.pending.length}}</span>
    </div>
    <div class="task__item task__wrap-cancel d-flex justify-content-center">
      <cancel></cancel>
      <span class="task">{{branch.tasks.problems.length}}</span>
    </div>
  </div>
</div>
</template>

<script>
export default {
  name: 'branch-grid-item',
  props: {
    branch: {
      type: Object
    },
    timeFormat: String
  },

  data: () => ({
    leftTime: 'status-closed',
    rightTime: 'status-closed'
  }),

  methods: {
    getLeftTime() {
      if (this.branch.workday_start) {
        if (this.branch.workday_start > this.branch.expected_workday_start) {
          this.leftTime = 'late'
        } else {
          this.leftTime = 'time'
        }
        return this.$moment(this.branch.workday_start)
          .format(this.timeFormat ? this.timeFormat : 'h:mm a')
      } else {
        this.leftTime = 'status-closed'
        return 'closed'
      }
    },
    getRightTime() {
      if (this.branch.workday_end) {
        if (this.branch.workday_start > this.branch.expected_workday_start) {
          this.rightTime = 'late'
        } else {
          this.rightTime = 'time'
        }
        return this.$moment(this.branch.workday_end)
          .format(this.timeFormat ? this.timeFormat : 'h:mm a')
      } else if (this.branch.workday_start) {
        this.rightTime = 'status-open'
        return 'open'
      } else {
        this.rightTime = 'status-closed'
        return 'closed'
      }
    },
    getLateTime() {
      return this.$moment(this.branch.workday_start)
        .subtract(this.branch.expected_workday_start)
        .format('mm')
    }
  },

  computed: {},

  components: {
    ready: require('../../../StateTask/ready'),
    pending: require('../../../StateTask/pending'),
    cancel: require('../../../StateTask/cancel')
  }
}
</script>

<style lang='scss' src='./style.scss' scoped></style>
