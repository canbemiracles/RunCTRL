<template>
    <transition-group name="slide" tag="DIV" class="shifts-time-row-list">
        <shift-time-row
        v-for="(time_row, index) in timeRowsList"
        :key="index"
        :weekStart="time_row.week_day_start"
        :shiftId="shiftId"
        :rowId="time_row.timeRow_id"
        ></shift-time-row>
        <add-button @click="addShiftRow" key="add-btn" class="add-shift-row-btn"></add-button>
    </transition-group>
</template>
<script>
import {mapMutations} from 'vuex';
export default {
    props:{
        timeRowsList: Array,
        shiftId: Number,
    },
    methods:{
        ...mapMutations(['addNewShiftTimeRow']),
        addShiftRow(){
            this.addNewShiftTimeRow(this.shiftId);
        },
    },
    components:{
        shiftTimeRow: require('../ShiftTimeRow'),
        addButton: require('../../../../Common/ButtonPlus')
    }
}
</script>
<style lang='scss' scoped>
.shifts-time-row-list{
    display: flex;
    flex-direction: column;
    position: relative;
}
.add-shift-row-btn{
    position: absolute;
    right: 30px;
    bottom: 20px;
}
.slide-enter{
    opacity: 0;
}
.slide-enter-active{
    animation: slide-in .4s ease-out forwards;
    transition: opacity .4s;
}
.slide-leave-active{
    animation: slide-out .4s ease-out forwards;
    transition: opacity .4s;
    opacity: 0; 
    position: absolute; 
}
.slide-move{
   transition: transform .4s; 
}
@keyframes slide-in{
    from{
        transform: translateY(20px);
    }
    to{
        transform: translateY(0);
    }
}
@keyframes slide-out{
    from{
        transform: translateY(0);
    }
    to{
        transform: translateY(20px);
    }
}
</style>