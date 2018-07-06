<template>
    <ul class="weekdays-list">
        <week-day
        v-for="day in weekList"
        :key="day.id"
        :day="day.name"
        :selected="day.selected"
        :index="day.id"
        @check="checkDay"
        ></week-day>
    </ul>
</template>
<script>
export default {
    props:{
        weekList: Array,
    },
    methods:{
        checkDay(state){
            if(state.isCheck){
               this.$emit('selectWeekDay', state.day); 
            }else{
                this.$emit('removeWeekDay', state.day); 
            }
        }
    },
    components:{
        weekDay: require('./WeekDay')
    }
}
</script>
<style lang="scss" scoped>
.weekdays-list{
    list-style: none;
    padding:0;
    margin: 0;
    margin-right: 65px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}
</style>