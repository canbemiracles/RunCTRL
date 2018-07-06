<template>
    <div class="month-choose-wrap d-flex">
        <month-item v-for="month in monthsList" 
        :key="month.id"
        :month="month"
        :selected="!!~selectMonthList.indexOf(month.id)"
        @check="checkMonth"
        ></month-item>
    </div>
</template>
<script>
export default {
    props:{
        selectedMonths: {
            type: Array,
            default: ()=>{
                return []
            }
        }
    },
    data(){
        return{
            monthsList: []
        }
    },
    created(){
        this.initMonthList();
    },
    computed:{
        selectMonthList(){
            return this.selectedMonths.map(({month})=>(month));
        }
    },
    methods:{
        initMonthList() {
            this.monthsList = [];
            let date = moment().startOf('year');
            for (let i = 1; i <= 12; i++) {
                let monthAbbr = date.format('MMM');
                this.monthsList.push({
                    month: monthAbbr,
                    id: i
                });
                date.add(1, 'months');
            }
        },
        checkMonth(state){
            if(state.isCheck){
               this.$emit('selectMonth', state.month); 
            }else{
                this.$emit('removeMonth', state.month); 
            }
        }
    },
    components:{
        monthItem: require('./MonthItem')
    }
}
</script>
<style lang='scss' scoped>
.month-choose-wrap {
    flex-wrap: wrap;
    width: 80%;
    margin: 10px auto;
}
</style>