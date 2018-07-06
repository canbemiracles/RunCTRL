<template>
    <div class="time-range-container d-flex">
        <div class="time-range-container-inner">
            <div class="time-pickers-input-wrap">
                <div class="time-inner">
                    <div class="content-row">
                        <div class="start-time d-flex" ref="startTime">
                            <div class="time" ref="startContainer">
                                <span class="clockpicker-span-hours">{{ $moment(timeOpen, 'HH:mm:ss').format(twelvehour ? 'hh' : 'HH')}}</span>
                                <span class="time-divider">:</span>
                                <span class="clockpicker-span-minutes">{{ $moment(timeOpen, 'HH:mm:ss').format('mm')}} </span>
                            </div>
                            <div class="clockpicker-am-pm-wrap part-day" v-if="twelvehour">
                                <span class="clockpicker-btn-am part-day__label" :class="{active: $moment(timeOpen, 'HH:mm:ss').format('a') =='am'}">AM</span>
                                <span class="clockpicker-btn-pm part-day__label" :class="{active: $moment(timeOpen, 'HH:mm:ss').format('a') =='pm'}">PM</span>
                            </div> 
                        </div>
                    </div>
                    <div class="error-row">
                        <div class="error-message">{{validation.firstError('timeOpen')}}</div>
                    </div>
                </div>
            </div>
            <div class="time-pickers-input-wrap">
                <div class="time-inner">
                    <div class="content-row">
                        <div class="end-time d-flex" ref="endTime">
                            <div class="time" ref="endContainer">
                                <span class="clockpicker-span-hours">{{ $moment(timeClose, 'HH:mm:ss').format(twelvehour ? 'hh' : 'HH')}}</span>
                                <span class="time-divider">:</span>
                                <span class="clockpicker-span-minutes">{{ $moment(timeClose, 'HH:mm:ss').format('mm')}} </span>
                            </div>
                            <div class="clockpicker-am-pm-wrap part-day" v-if="twelvehour">
                                <span class="clockpicker-btn-am part-day__label" :class="{active: $moment(timeClose, 'HH:mm:ss').format('a') =='am'}">AM</span>
                                <span class="clockpicker-btn-pm part-day__label" :class="{active: $moment(timeClose, 'HH:mm:ss').format('a') =='pm'}">PM</span>
                            </div> 
                        </div>
                    </div>
                    <div class="error-row">
                        <div class="error-message">{{validation.firstError('timeOpen')}}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { mapGetters } from 'vuex';
import { Validator } from "simple-vue-validator";
export default {
    mixins: [require("simple-vue-validator").mixin],
    props:{
        timeOpen: String,
        timeClose: String,
    },
    data(){
        return {
            time_open: null,
            time_close: null,
            twelvehour: true,
            showTimeInputEnd: false,
            showTimeInputStart: false   
        }
    },
    validators: {
        "timeOpen"(value) {
        return Validator.value(value)
            .required();
        },
        "timeClose"(value) {
        return Validator.value(value)
            .required();
        },
    },
    watch:{
        timeFormat: {
            handler(value){
                if(value == 'hh:mm a'){
                    this.twelvehour = true;
                }else{
                    this.twelvehour = false; 
                }
            },
            immediate: true
        }
    },
    computed: {
        ...mapGetters(['timeFormat']),
    },
    mounted(){
        let vm = this;
        $(this.$refs.startTime).clockpicker({
            container: this.$refs.startContainer,
            align: 'left',
            twelvehour: this.twelvehour,
            afterDone: this.setStartTime,
            autoclose: true,
            beforeShow: function(){
                vm.showTimeInputStart = true;
                $(vm.$refs.endTime).clockpicker('hide');
            },
            beforeHide: function(){
                vm.showTimeInputStart = false;
            }
        });
        $(this.$refs.endTime).clockpicker({
            container: this.$refs.endContainer,
            align: 'left',
            twelvehour: this.twelvehour,
            afterDone: this.setEndTime,
            autoclose: true,
            beforeShow: ()=>{
                vm.showTimeInputEnd = true;
                $(vm.$refs.startTime).clockpicker('hide');
            },
            beforeHide: ()=>{
                vm.showTimeInputEnd = false;
            }
        });
    },
    methods:{
        setStartTime(){
            let hours = $(this.$refs.startTime).find('.clockpicker-span-hours').text();
            let minutes = $(this.$refs.startTime).find('.clockpicker-span-minutes').text();
            let time;
            if(this.twelvehour){
                let amORpm =  $(this.$refs.startTime).find('.clockpicker-am-pm-wrap .active').text();
                time = moment(`${hours}:${minutes} ${amORpm}`, 'hh:mm a').format('HH:mm:ss'); 
            }else{
                time = `${hours}:${minutes}:00`;
            }
            let data={
                time_open: time,
                time_close: this.timeClose 
            }
            this.$emit('changeTime', data)
        },
        setEndTime(){
            let hours = $(this.$refs.endTime).find('.clockpicker-span-hours').text();
            let minutes = $(this.$refs.endTime).find('.clockpicker-span-minutes').text();
            let time;
            if(this.twelvehour){
                let amORpm =  $(this.$refs.endTime).find('.clockpicker-am-pm-wrap .active').text();
                time = moment(`${hours}:${minutes} ${amORpm}`, 'hh:mm a').format('HH:mm:ss'); 
            }else{
                time = `${hours}:${minutes}:00`;
            }
            let data={
                time_open: this.timeOpen,
                time_close: time 
            }
            this.$emit('changeTime', data)
        },
    }
}
</script>
<style lang='scss' src="./style.scss" scoped></style>