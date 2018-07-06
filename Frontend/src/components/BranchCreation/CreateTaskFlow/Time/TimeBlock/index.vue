<template>
<div class="row wrap d-flex justify-content-center time-block">
  <div class="row1 header-row">{{ branchName }}</div>
  <div class="row1 d-flex">
    <ul class="weekdays-list">
      <week-day v-for="(day, index) in weekGround"
                :key="index"
                class="item-week-day__time"
                :day="day.name"
                :number="day.day"
                :disabled="day.disabled"
                :selected="isSelectedWeekDays(day)"
                :timerows="day.time_rows"
                @click.native="setDay(day)"></week-day>
      <li class="item-week-day__time calendar-wrap">
        <div class="calendar-item">
          <svg class="calendar-icon"
               width="14"
               height="19"
               viewBox="0 0 1792 1792"
               xmlns="http://www.w3.org/2000/svg"><path d="M192 1664h288v-288h-288v288zm352 0h320v-288h-320v288zm-352-352h288v-320h-288v320zm352 0h320v-320h-320v320zm-352-384h288v-288h-288v288zm736 736h320v-288h-320v288zm-384-736h320v-288h-320v288zm768 736h288v-288h-288v288zm-384-352h320v-320h-320v320zm-352-864v-288q0-13-9.5-22.5t-22.5-9.5h-64q-13 0-22.5 9.5t-9.5 22.5v288q0 13 9.5 22.5t22.5 9.5h64q13 0 22.5-9.5t9.5-22.5zm736 864h288v-320h-288v320zm-384-384h320v-288h-320v288zm384 0h288v-288h-288v288zm32-480v-288q0-13-9.5-22.5t-22.5-9.5h-64q-13 0-22.5 9.5t-9.5 22.5v288q0 13 9.5 22.5t22.5 9.5h64q13 0 22.5-9.5t9.5-22.5zm384-64v1280q0 52-38 90t-90 38h-1408q-52 0-90-38t-38-90v-1280q0-52 38-90t90-38h128v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h384v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h128q52 0 90 38t38 90z"/></svg>
        <input id="my_hidden_input" class="datepickerInput" ref="datePicker">
        </div>
      </li>
    </ul>

    <time-range :useSeconds="false"
                :timeOpen="value.time && value.time.time_open"
                :timeClose="value.time && value.time.time_close"
                :timeLimit="selectedDay"
                @changeTime="value.time = $event"></time-range>
  </div>

  <div class="wrap-footer d-flex justify-content-between">
        <template v-if="type!='notification'">
          <snooze></snooze>
          <counter class="counter counter-item" :min="0" unit="min" @changeValue="setSnoozeCountMin"></counter>
          <counter class="counter counter-item" :min="0" unit="times" @changeValue="setSnoozeCountTimes"></counter>
        </template>
        <div class="repeat-task d-flex justify-content-between" :class="{fullwidth: type=='notification'}">
          <span>Repeat task</span>
          <repeat-task v-model="value.repeat" :index="branch"></repeat-task>
        </div>
      </div>

  <div class="repeat"
       v-if="value.repeat">
  <template v-if="value.repeat">    
    <div class="repeat-collapse" >
        <small-btn @click="repeatPeriod = 1" text="Daily" :active="repeatPeriod === 1"/>
        <small-btn @click="repeatPeriod = 2" text="Weekly" :active="repeatPeriod === 2"/>
        <small-btn @click="repeatPeriod = 3" text="Monthly" :active="repeatPeriod === 3"/>
        <small-btn @click="repeatPeriod = 4" text="Yearly" :active="repeatPeriod === 4"/>
    </div>
    <div class="repeat-collapse-content d-flex">
        <div class="repeat-row d-flex">
            <span class="label-snooze-counter">Every</span>
            <counter class="counter counter-item repeat-counter" :init="value.repeat_subunit" :min="1" @changeValue="setReapeatPeriod"></counter>     
            <span class="label-snooze-counter">{{ repeatUnit }}{{ value.repeat_subunit > 1 ? 's': ''}} {{ value.repeat_unit == 4 ? 'in:' : ''  }}</span>
            
            <week-days-bar v-if="value.repeat_unit == 2" 
            @selectWeekDay = "selectWeekDayWeek"
            :selectedWeekDays="value.repeat_week_days_week"
            :dayStart="weekStart" 
            :disabledDays="disabledDays" 
            @removeWeekDay = "removeWeekDayWeek">
            </week-days-bar>
            <div class="radio-buttons" v-if="value.repeat_unit == 3">
                <div class="radio-item">
                    <input type="radio" id="monthDay" value="day" v-model="value.pickedMonthRepeat">
                    <label for="monthDay" class="radio-input"></label>
                    <label for="monthDay" class="radio-label">month day</label>
                </div>
                <div class="radio-item">
                    <input type="radio" id="monthWeek" value="week" v-model="value.pickedMonthRepeat">
                    <label for="monthWeek" class="radio-input"></label>
                    <label for="monthWeek" class="radio-label">week day</label>
                </div>
            </div>
        </div>
        <div class="repeat-row d-flex" v-if="value.pickedMonthRepeat == 'week' && value.repeat_unit == 3">
            <app-select :options="numberWeekList"
            @changeSelection="changeNumberWeek($event, 3)"
            class="task-select"
            dropDownClass="select-dropdown"
            :initValue="value.repeat_week_month"
            ></app-select>
            <week-days-bar
            :oneSelect="true" 
            :dayStart="weekStart"
            :disabledDays="disabledDays" 
            :selectedWeekDays="value.repeat_week_days_month" 
            @selectWeekDay = "selectWeekDayMonth"
            @removeWeekDay = "removeWeekDayMonth">
            </week-days-bar>
        </div>
        <div class="repeat-row d-flex" v-if="value.pickedMonthRepeat == 'day' && value.repeat_unit == 3">
            <month-days-picker 
            :selectedDays="value.month_days"
            @selectMonthDay="chooseMonthDay"
            @removeMonthDay="removeMonthDay"></month-days-picker>
        </div>
        <template v-if="value.repeat_unit == 4">
            <div class="repeat-row d-flex">
                <month-choose-bar
                :selectedMonths="value.repeat_months"
                @selectMonth = "selectMonth"
                @removeMonth = "removeMonth"
                ></month-choose-bar>
            </div>
            <div class="repeat-row d-flex">
                <div class="checkbox">
                    <input type="checkbox" id="yearRepeatWeek" value="yearRepeat" v-model="value.pickedYearRepeatWeek">
                    <label for="yearRepeatWeek" class="radio-input"></label>
                    <label for="yearRepeatWeek" class="radio-label">On the: </label>
                </div>
            </div>
            <div class="repeat-row d-flex">
                <app-select :options="numberWeekList"
                @changeSelection="changeNumberWeek($event, 4)"
                :disabled="!value.pickedYearRepeatWeek"
                class="task-select"
                dropDownClass="select-dropdown"
                :initValue="value.repeat_week_year"
                ></app-select>
                <week-days-bar
                :oneSelect="true"
                :dayStart="weekStart"
                :disabled="!value.pickedYearRepeatWeek" 
                :disabledDays="disabledDays"
                :selectedWeekDays="value.repeat_week_days_year"  
                @selectWeekDay = "selectWeekDayYear"
                @removeWeekDay = "removeWeekDayYear">
                </week-days-bar>
            </div>
        </template>           
      </div>
    </template>
  </div>
  <div class="wrap-footer button-wrap"
       v-if="type=='notification'">
    <button class="btn-send-time mb-1"
            @click="saveAndCreateNew">Save & Create New Task</button>    
    <button class="btn-send-time"
            @click="saveTimeData">Complete</button>
  </div>
  <preloader :show="showPreloader"></preloader>
</div>
</template>
<script>
import daterangepicker from "bootstrap-daterangepicker";
import {mapGetters, mapActions, mapState, mapMutations} from 'vuex';
export default {
  props: {
    value: {
      type: Object,
      required: true
    },
    type: String,
    branch: Number,
    branchName: String,
    weekStart: Number,
    active: Boolean,
    lastIndex: Boolean
  },
  data() {
    return {
      week: ['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'],
      repeatPeriod: 1,
      selectedDay: null,
      weekGround: [],
      init: false,
      disabledDays: [],
    }
  },
  watch: {
    value: {
      handler(value) {
        console.log('change time data');
        if(!value.day){
          this.initCurrentDay();
        }
        this.$emit('input', value)
      },
      deep: true
    },
    active: {
      handler(value) {
        if (value) {
          this.init= true;
        }
      }
    },
    branch:{
      handler(value) {
        if (value) {
          this.initWeekData();
        }
      },
      immediate: true
    },
    repeatPeriod() {
      this.$set(this.value, 'repeat_unit', this.repeatPeriod);
    }
  },
  computed: {
    ...mapGetters(['company', 'shifts', 'language']),
    ...mapState({
      showPreloader: state => state.calendar.showPreloader,
    }),
    maxValueForRepeatPeriod() {
      switch (this.repeatPeriod) {
        case 1:
          return 30
        case 2:
          return 4
        case 3:
          return 12
        case 4:
          return 1
      }
    },
    repeatUnit(){
      switch(this.repeatPeriod){
          case 1:
              return 'day'
          case 2:
              return 'week'
          case 3:
              return 'month'
          case 4:
              return 'year'
      }
    },
    numberWeekList(){
      let list = [
          { value: 1, text: 'first'},
          { value: 2, text: 'second'}, 
          { value: 3, text: 'third'}, 
          { value: 4, text: 'fourth'},
      ];
      let date = moment(this.value.repeat_month_days[0], 'MMM D, YYYY');
      let lastDay = date.daysInMonth();
      let year = date.year();
      let month = date.month();
      if(this.weekOfMonth(moment({year, month, day: lastDay}))== 5){
          list.push({ value: 5, text: 'fifth'});
      }
      return list;  
    },
    selectedDays(){
      let daysArr = '';
      this.value.repeat_month_days.forEach((el, ind)=>{
          if(ind==0){
              daysArr += el.month_day;
          }else{
            daysArr += ', ' + el.month_day;  
          }
      });
      return daysArr;
    },
    

  },
  methods: {
    ...mapActions(['getCompanyData', 'getBranchShifts']),
    ...mapMutations(['setPreloaderState']),
    initWeekData() {
      if(this.active){
        this.setPreloaderState(true);
      }
      let currentDayWeek = moment().isoWeekday();
      let weekList = this.week;
      this.weekGround = [];
      this.getBranchShifts(this.branch)
        .then(() => {
          console.log('weekGround');
          // получаем массив с днями недели
          let start = currentDayWeek - 1;
          let weekDays = [];
          for (let i = 0; i < 7; i++) {
            if (start > 6) {
              start = 0;
            }
            let dayObj = moment().clone().add(i, 'days').toDate();
            let w_day = dayObj.getDate();
            let w_month = dayObj.getMonth() + 1;
            let w_year = dayObj.getFullYear();
            let weekDay = {
              name: weekList[start],
              day: w_day,
              month: w_month,
              year: w_year,
              disabled: true,
              time_rows: []
            }
            this.shifts.forEach(shift => {
              if (shift.shift_day.day == start + 1) {
                weekDay.time_rows.push({
                  time_open: shift.time_open,
                  time_close: shift.time_close
                });
                weekDay.disabled = false;
              }
            });
            this.weekGround.push(weekDay);
            start++;
          }
          this.initCurrentDay();
          if(this.lastIndex && this.active){
            this.setPreloaderState(false);
          }
          this.disabledDays = this.weekGround.filter((item)=> item.disabled == true).map((item) => {
            let day = moment({y: item.year, M: item.month-1, d: item.day}).day();
            if(day == 0){
              return 7
            }else{
              return day
            }
          })
          this.initTimepicker();
        });
    },
    isSelectedWeekDays(day){
      let day_moment = moment({y: day.year, M: day.month -1, d: day.day});
      let selected= false;
      this.value.repeat_month_days.forEach((el, ind)=>{
          if(day_moment.isSame(moment(el.month_day, 'MMM D, YYYY'))){
            selected = true;
          }
      });
      return selected;
    },
    setSnoozeCountMin(value) {
      this.$set(this.value, 'snooze_time', value * 60);
    },
    setSnoozeCountTimes(value) {
      this.$set(this.value, 'snooze_max', value);
    },
    setReapeatPeriod(value) {
      this.$set(this.value, 'repeat_subunit', value);
    },
    setDay(day) {
      if (day.disabled) {
        return false;
      }
      let setDatesArr;
      let checkday = moment(`${day.year}/${day.month}/${day.day}`, 'YYYY/MM/DD');
      if(this.isSelectedWeekDays(day)){
        //remove selected day
        this.removeRepeatMonthDay(checkday.format('MMM D, YYYY'));
        this.removeMonthDay(day.day);
        let date = null;
        setDatesArr = this.value.repeat_month_days.map(({month_day}) => (new Date(moment(month_day, 'MMM D, YYYY').toISOString(true))));
        if(this.value.repeat_month_days.length){
          date = moment(this.value.repeat_month_days[0], 'MMM D, YYYY').toISOString(true);
        }
        date && this.setCurrentDay(date);
        $(this.$refs.datePicker).datepicker('setDates', setDatesArr);
        return;
      }
      this.setCurrentDay(checkday.toISOString(true));
      setDatesArr = this.value.repeat_month_days.map(({month_day}) => (new Date(moment(month_day, 'MMM D, YYYY').toISOString(true))));
      setDatesArr.push(new Date(checkday.toISOString(true)));
      $(this.$refs.datePicker).datepicker('setDates', setDatesArr);
    },
    saveTimeData() {
      this.$parent.$emit('timeRequest');
      this.$parent.$emit('complete');
    },
    saveAndCreateNew(){
       this.$parent.$emit('timeRequest');
       this.$parent.$parent.moveUpTo(0);
    },
    initCurrentDay() {
      let currDate = moment().toISOString(true);
      this.getAvailableDay(currDate);
    },
    setCurrentDay(currDate) {
      console.log(currDate);
      this.$set(this.value, 'day', moment(currDate).toDate().getDate());
      this.$set(this.value, 'dateStr', moment(currDate).toISOString(true));
      this.setSelectedDay(this.value.dateStr);
    },
    getAvailableDay(currDate, count = 7) {
      let check = this.isNotDisabledDay(currDate);
      if (check) {
        this.setCurrentDay(currDate);
      } else {
        if (count) {
          count--;
          let nextDay = moment(currDate).add(1, 'days').toISOString(true);
          this.getAvailableDay(nextDay, count);
        } else {
          this.selectedDay = null;
        }
      }
    },
    initTimepicker() {
       let start =  moment();
       let vm = this;
       let disabledDays = this.disabledDays.map(el=>{
         if(el==7){
           el = 0
         }
         return el;
       });
        $.fn.datepicker.dates['en'].daysMin = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        $(vm.$refs.datePicker).datepicker({
            multidate: true,
            format: 'MMM D, YYYY',
            weekStart: vm.weekStart,
            startDate: start.toString(),
            language: vm.language,
            orientation: 'bottom',
            daysOfWeekDisabled: disabledDays,
            maxViewMode: 2,
            templates:{
                leftArrow: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="9" viewBox="0 0 10 9"><defs><path id="edkua" d="M4482.45 234.1l-4.54-4.5-.91.9 4.55 4.5z"/><path id="edkub" d="M4477 230.5l4.55-4.5.9.9-4.54 4.5z"/><path id="edkuc" d="M4477.91 229.83h9.1v1.35h-9.1z"/></defs><g><g transform="translate(-4477 -226)"><g><use fill="#adadad" xlink:href="#edkua"/></g><g><use fill="#adadad" xlink:href="#edkub"/></g><g><use fill="#adadad" xlink:href="#edkuc"/></g></g></g></svg>',
                rightArrow: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="9" viewBox="0 0 10 9"><defs><path id="bzyba" d="M4772.45 234.1l-4.54-4.5-.91.9 4.55 4.5z"/><path id="bzybb" d="M4767 230.5l4.55-4.5.9.9-4.54 4.5z"/><path id="bzybc" d="M4767.91 229.83h9.1v1.35h-9.1z"/></defs><g><g transform="matrix(-1 0 0 1 4777 -226)"><g><use fill="#adadad" xlink:href="#bzyba"/></g><g><use fill="#adadad" xlink:href="#bzybb"/></g><g><use fill="#adadad" xlink:href="#bzybc"/></g></g></g></svg>' 
            }
        }).on('changeDate', (e)=>{
            console.log('changeDate', e);
            let dates = e.dates.map(date=>{
                return moment(date).format('MMM D, YYYY');
            });
            dates.forEach(el=>{
                this.selectMonthDay(el);
                this.chooseMonthDay(moment(el, 'MMM D, YYYY').date());
            });
        });
        let { year, month, day } = this.selectedDay;
        let selectedDay = moment({y: year, M: month -1, d: day });
        $(vm.$refs.datePicker).datepicker('setDates', new Date(selectedDay.toString()));
    
    },
    
    setSelectedDay(value) {
      let selDate;
      if (this.weekGround && this.weekGround.length) {
        this.weekGround.forEach(day => {
          let date = moment({
              year: day.year,
              month: day.month - 1,
              day: day.day
            }).toDate().getDay();
          let selectedDay = moment(value).toDate().getDay();
          if (date == selectedDay) {
            selDate = day;
          }
        });
        this.selectedDay = selDate;
      }
    },
    isNotDisabledDay(checkDay) {
      let check = false;
      if (this.weekGround && this.weekGround.length) {
        this.weekGround.forEach(day => {
          let date = moment({
              year: day.year,
              month: day.month - 1,
              day: day.day
            }).toDate().getDay();
          let checkDayofWeek = moment(checkDay).toDate().getDay();
          if (date == checkDayofWeek && !day.disabled) {
            check = true;
          }
        });
        return check;
      }
    },
    selectWeekDayMonth(id, oneSelect){
      if(oneSelect){
          this.value.repeat_week_days_month = [];
      }
      this.value.repeat_week_days_month.push(id);
    },
    removeWeekDayMonth(id){
        this.value.repeat_week_days_month = this.value.repeat_week_days_month.filter((week_day)=>{
            return id != week_day;
        }); 
    },
    selectWeekDayYear(id, oneSelect){
        if(oneSelect){
            this.value.repeat_week_days_year = [];
        }
        this.value.repeat_week_days_year.push(id);
    },
    removeWeekDayYear(id){
        this.value.repeat_week_days_year = this.value.repeat_week_days_year.filter((week_day)=>{
            return id != week_day;
        }); 
    },
    selectWeekDayWeek(id, oneSelect){
        if(oneSelect){
            this.value.repeat_week_days_week = [];
        }
        this.value.repeat_week_days_week.push(id);
    },
    removeWeekDayWeek(id){
        this.value.repeat_week_days_week = this.value.repeat_week_days_week.filter((week_day)=>{
            return id != week_day;
        }); 
    },
    selectMonth(id){
        this.value.repeat_months.push({ month: id});
    },
    removeMonth(id){
        this.value.repeat_months = this.value.repeat_months.filter(({month})=>{
            return id != month;
        }); 
    },
    selectMonthDay(date){
      console.log(date);
      let exist = this.value.repeat_month_days.find(el => (el.month_day == date));
      if(exist) return;
      this.value.repeat_month_days.push({ month_day: date});
      let month = moment(date, 'MMM D, YYYY').month() + 1;
      let existMonth = false;
      this.value.repeat_months.forEach(el=>{
          if(el.month == month){
              existMonth = true;
          }
      });
      console.log(existMonth);
      if(!existMonth){
          console.log(month);
          this.value.repeat_months.push({month});
      } 
    },
    removeRepeatMonthDay(date){
      this.value.repeat_month_days = this.value.repeat_month_days.filter((day)=>(day.month_day != date));
    },
    chooseMonthDay(date){
        this.value.month_days.push(date);
    },
    removeMonthDay(date){
        this.value.month_days = this.value.month_days.filter((day)=>(day!=date));
    },
    weekOfMonth(m) {
        return m.week() - moment(m).startOf('month').week() + 1;
    },
    changeNumberWeek(option, unit){
        if(unit==3){
          this.value.repeat_week_month = option.value;  
        }else if(unit==4){
          this.value.repeat_week_year = option.value;   
        }
    },

  },
  components: {
    WeekDay: require('../WeekDay/'),
    timeRange: require('../../../../TimeRange'),
    snooze: require('../Snooze'),
    counter: require('../../../../Common/Counter'),
    repeatTask: require('../RepeatTask'),
    smallBtn: require('../../../../Common/SmallBtn'),
    preloader: require('../../../../Common/Preloader'),
    weekDaysBar: require('../../../../WeekDaysListChooseBar'),
    appSelect: require('../../../../Common/Select'),
    monthChooseBar: require('../../../../MonthChooseBar'),
    monthDaysPicker: require('../../../../MonthDaysChooseBar'),
  }
}
</script>
<style lang='scss' src='../style.scss' scoped></style>
<style lang='scss' src='../../../../TaskCard/style.scss' scoped></style>