<template>
<section class="slide-section-container d-flex">
  <div class="slide-content d-flex flex-column">
    <div class="row">
      <h3 class="title-header col-md-12">Time</h3>
    </div>
    <time-block v-for="(task, index) in value"
                :lastIndex="index==value.length-1"
                :value.sync="task.timeData"
                :type="type"
                :key="task.index"
                :weekStart="weekStart"
                :active="active"
                :branchName="task.branchName"
                :branch="task.branches"></time-block>           
  </div>
  <recommend>
    <div slot="recommend">
      Analytics only available on paid plans. Please upgrade your account to see.
    </div>
  </recommend>
</section>
</template>
<script>
import {
  mapGetters,
  mapActions
} from 'vuex';
export default {
  props: {
    value: {
      type: Array,
      required: true
    },
    type: String,
    active: Boolean
  },
  data() {
    return {
      weekStart: null
    }
  },
  watch: {
    value(arr) {
      this.$emit('input', arr)
    },
  },
  created() {
    this.getCompanyData()
      .then((res) => {
        this.weekStart = res.week_start_on;
      });
  },
  methods: {
    ...mapActions(['getCompanyData']),
  },
  components: {
    recommend: require('../../../SidebarRecommend'),
    timeBlock: require('./TimeBlock')
  }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
