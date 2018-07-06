<template>
    <ul class="weekdays-list">
        <week-day
        v-for="day in weekList"
        :key="day.id"
        :day="day.name"
        :shiftId="shiftId"
        :selected="day.selected"
        :disable="day.disabled"
        :index="day.id"
        :rowId="rowId"
        :edit_id="day.savedId"
        @check="checkDay"
        ></week-day>
    </ul>
</template>
<script>
import {mapGetters, mapMutations} from 'vuex';
export default {
    props:{
        weekList: Array,
        shiftId: Number,
        rowId: Number
    },
    data: function(){
        return{
           listBusyDays: [],
        }
    },
    computed:{
        ...mapGetters(['forDeleteArr'])
    },
    methods:{
        ...mapMutations(['removeShift', 'addforDeleteArr', 'removefromDeleteArr']),
        checkDay(state){
            if(state.isCheck){
                let currentDay = this.weekList.find(day=> day.id == state.day);
                this.$set(currentDay, 'selected', true);
                this.$emit('addShift', state.day, state.error);
                if(state.id && this.forDeleteArr.indexOf(state.id)!=-1){
                    this.removefromDeleteArr(state.id);
                }
            }else{
                let currentDay = this.weekList.find(day=> day.id == state.day);
                this.$set(currentDay, 'selected', false);
                this.removeShift(
                    {
                        shift_day: state.day,
                        shift_id: this.shiftId
                    });
                if(state.id){
                    this.addforDeleteArr(state.id);
                }
            }
        },
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

