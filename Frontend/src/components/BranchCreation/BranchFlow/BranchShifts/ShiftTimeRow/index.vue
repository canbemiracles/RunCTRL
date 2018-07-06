<template>
      <div class="shift-row d-flex">
        <time-range class="time-range-wrap"
          :timeOpen = "timeOpen"
          :timeClose = "timeClose"
          @changeTime="setTime"
        ></time-range>
          <div class="working-week-days">
              <week-list :weekList="weekDays" :shiftId="shiftId" :rowId="rowId" @addShift="addShift"></week-list>
          </div>
      </div>
</template>
<script>


import { mapActions, mapGetters, mapMutations } from "vuex";
export default {
  props: {
    weekStart: Number,
    shiftId: Number,
    rowId: Number,
  },
  computed: {
    ...mapGetters(['shift_groups']),
    time_rows: function(){
      let tr;
      this.shift_groups.forEach(item=>{
          if(item.shift_id == this.shiftId){
              tr = item.time_rows;
          }
      }); 
      return tr;
    }, 
  },
  watch:{
    time_rows(){
      this.initTimeGround();
    },
    shift_groups:{
      handler(shift_groups){
        if(shift_groups.length && !this.init){
          this.init = true;
          this.initListBusyDays();
          this.initWeekGround();
        }
      },
      deep: true,
      immediate: true
    }
  },
  data: () => {
    return {
      weekDays: [],
      timeOpen: null,
      timeClose: null,
      twelvehour: true,
      listBusyDays: [],
      init: false
    };
  },
  components: {
    weekList: require("./WeekDaysList"),
    timeRange: require('../../../../TimeRange')
  },
  beforeMount() {
    this.initTimeGround();
  },
  methods: {
    ...mapMutations(["addNewShift", 'setShiftTimeRow']),
    addShift(day, error, time) {
      this.addNewShift({
        shift_day: day,
        shift_id: this.shiftId,
        timeRow_id: this.rowId,
        error: error,
      });
    },
    setTime(time){
      this.timeOpen = time.time_open;
      this.timeClose = time.time_close;
      let data = {
        time_open: time.time_open,
        time_close: time.time_close,
        timeRow_id: this.rowId,
        shift_id: this.shiftId
      }
      this.setShiftTimeRow(data)
    },
    
    initWeekGround() {
      this.weekDays=[];
      let weekList = [
        { name: "MON", id: 1 },
        { name: "TUE", id: 2 },
        { name: "WED", id: 3 },
        { name: "THU", id: 4 },
        { name: "FRI", id: 5 },
        { name: "SAT", id: 6 },
        { name: "SUN", id: 7 }
      ];
      let start = this.weekStart - 1;
      for (let i = 0; i < 7; i++) {
        if (this.listBusyDays.length != 0) {
          for (let j = 0; j < this.listBusyDays.length; j++) {
            if (start > 6) {start = 0;}
            if (weekList[i].id == this.listBusyDays[j].day) {
                if(this.listBusyDays[j].rowId == this.rowId && 
                this.listBusyDays[j].shiftId == this.shiftId){
                  weekList[i].selected = true;
                  weekList[i].savedId = this.listBusyDays[j].savedId;
                }else{
                  weekList[i].disabled = true;
                  weekList[i].selected = false;
                }
            }
          }
          this.weekDays.push(weekList[start]);
        }else{
            if (start > 6) {start = 0;}
            this.weekDays.push(weekList[start]);
        }
        start++;
      }
    },
    initTimeGround() {
      this.time_rows.forEach(item=>{
        if(item.timeRow_id ==this.rowId){
          this.timeOpen = item.time_open;
          this.timeClose = item.time_close;
        }
      });
    },
    initListBusyDays(){
      let list;
      this.shift_groups.forEach(item=>{
          if(item.shift_id == this.shiftId){
              list =  item.listBusyDays;
          }
      });
      this.listBusyDays = list;
    }
  }
};
</script>
<style lang='scss' src='./style.scss' scoped></style>