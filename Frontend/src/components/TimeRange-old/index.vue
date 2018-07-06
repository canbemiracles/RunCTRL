<template>
    <div class="time-range-container d-flex">
        <div class="time-range-container-inner">
             <div class="time-wrap">
                <div class="time-inner">
                    <div class="content-row">
                        <slot name="icon"></slot>
                        <div class="time">
                            <input type="text" class="input-hours" pattern="[0-9]{2}" maxlength=2
                            v-model.lazy="time_open.hours" @change="changehandler" 
                            :class="{'field-error': validation.hasError('time_open.hours')}">
                            <span class="time-divider">:</span>
                            <input type="text" class="input-minutes" 
                            :class="{'field-error': validation.hasError('time_open.minutes')}" 
                            @change="changehandler" maxlength=2 pattern="[0-9]{2}" 
                            v-model.lazy="time_open.minutes">
                        </div>
                        <div class="part-day">
                            <input type="radio" :id="'open_am' + id" value=0 class="part-day__item" 
                            v-model="time_open.pmAm" @change="changehandler">
                            <label :for="'open_am' + id" class="part-day__label">AM</label>
                            <input type="radio" :id="'open_pm' + id" value=1 class="part-day__item" 
                            v-model="time_open.pmAm" @change="changehandler">
                            <label :for="'open_pm' + id" class="part-day__label">PM</label>
                        </div>
                    </div>
                    <div class="error-row">
                        <div class="error-message">{{validation.firstError('time_open.hours')}}</div>
                        <div class="error-message">{{validation.firstError('time_open.minutes')}}</div>
                    </div>
                </div>
            </div>
            <div class="time-wrap">
                <div class="time-inner">
                    <div class="content-row">
                        <slot name="icon"></slot>
                        <div class="time">
                            <input type="text" class="input-hours"  maxlength=2 pattern="[0-9]{2}" v-model.lazy="time_close.hours" @change="changehandler">
                            <span class="time-divider">:</span>
                            <input type="text" class="input-minutes"  maxlength=2 pattern="[0-9]{2}" v-model.lazy="time_close.minutes" @change="changehandler">
                        </div>
                        <div class="part-day">
                            <input type="radio" :id="'close_am' + id" value=0 class="part-day__item" v-model="time_close.pmAm" @change="changehandler">
                            <label :for="'close_am' + id" class="part-day__label">AM</label>
                            <input type="radio" :id="'close_pm' + id" value=1 class="part-day__item" v-model="time_close.pmAm" @change="changehandler">
                            <label :for="'close_pm' +  id" class="part-day__label">PM</label>
                        </div>
                    </div>
                    <div class="error-row">
                        <div class="error-message">{{validation.firstError('time_close.hours')}}</div>
                        <div class="error-message">{{validation.firstError('time_close.minutes')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Validator } from "simple-vue-validator";
export default {
    mixins: [require("simple-vue-validator").mixin],
    props:{
        id: String/Number,
        timeOpen: String,
        timeClose: String,
        useSeconds: {
          type: Boolean,
          default: true
        }
    },
    data: function(){
        return{
            time_open: null,
            time_close: null
        }
    },

    watch: {
      time_open() {
        this.changehandler()
      },
      time_close() {
        this.changehandler()
      },
      timeOpen(value) {
        if(value){
            this.time_open =  this.parseTime(value);  
        }
      },
      timeClose(value) {
        if(value){
            this.time_close =  this.parseTime(value);
        }
      }
    },

    validators: {
        "time_open.hours"(value) {
        return Validator.value(value)
            .required()
            .integer()
            .between(1, 12);
        },
        "time_open.minutes"(value) {
        return Validator.value(value)
            .required()
            .integer()
            .between(0, 59);
        },
        "time_close.hours"(value) {
        return Validator.value(value)
            .required()
            .integer()
            .between(1, 12);
        },
        "time_close.minutes"(value) {
        return Validator.value(value)
            .required()
            .integer()
            .between(0, 59);
        }
  },
    created(){
        if(this.timeOpen){
            this.time_open =  this.parseTime(this.timeOpen);  
        }   
        if(this.timeClose){
            this.time_close =  this.parseTime(this.timeClose);
        }
    },
    methods:{
        formatTime(time) {
            //format to hh:mm
            let hours = time.pmAm == 0 ? time.hours : parseInt(time.hours) + 12;
            if(time.hours==12 && time.pmAm == 1){
                hours = 12;
            }
            if(hours==12 && time.pmAm == 0){
                hours ='00';
            }
            let minutes = time.minutes;
            let seconds = time.seconds;
            return this.useSeconds
              ? hours + ":" + minutes + ":" + seconds
              : hours + ":" + minutes
        },
        parseTime(time){
            let timeStr = time.split(':');
            let hours = +timeStr[0];
            let pmAm = hours >= 12 ? 1 : 0 ;
            hours = hours > 12 ? hours - 12 : hours;
            hours = hours < 10 ? '0' + hours : hours;
            hours = hours == 0 ? 12 : hours;
            let minutes = timeStr[1];
            let seconds = timeStr[2]
            return {
                hours,
                minutes,
                seconds,
                pmAm
            }
        },
        changehandler(){
            let time={
                time_open: this.formatTime(this.time_open),
                time_close: this.formatTime(this.time_close), 
            }
            this.$emit('changeTime', time);
        }
    },
    
}
</script>
<style lang='scss' src='./style.scss' scoped></style>