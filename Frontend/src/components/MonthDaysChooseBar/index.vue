<template>
    <table class="days-list">
        <tr v-for="(row, index) in monthDays" :key="index">
            <day v-for="day in row"
            :day="day.day"
            :selected="!!~selectedDays.indexOf(day.day)"
             @check="checkDay"
            :key="day.day"></day>
        </tr>
    </table>
</template>
<script>
export default {
    props:{
        selectedDays: {
            type: Array,
            default: ()=>{
                return []
            }
        }
    },
    data(){
        return {
            monthDays: []
        }
    },
    mounted(){
        this.initMonthDaysList();
    },
    methods:{
        initMonthDaysList(){
            this.monthDays = [];
            let select = false;
            if(this.selectedDays.length){
                select = ~this.selectedDays.indexOf(i);
            }
            let day=1;
            for(let row=0; row<5; row++){
                this.monthDays.push([]);
                for(let j=0; j<7; j++){
                    this.monthDays[row].push({
                        day: day,
                        selected: select
                    });
                    day++;
                    if(day==32){
                        break;
                    }
                }
            } 
        },
        checkDay(state){
            if(state.isCheck){
                this.$emit('selectMonthDay', state.day); 
            }else{
                this.$emit('removeMonthDay', state.day); 
            } 
        }
    },
    components:{
        day: require('./MonthDay')
    }
}
</script>
<style lang='scss' scoped>
.days-list{
    width: 100%;
}
</style>