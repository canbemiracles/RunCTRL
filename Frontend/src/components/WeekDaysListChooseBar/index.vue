<template>
    <ul class="week-days-list d-flex">
        <day v-for="day in weekDays" 
        :key="day.id"
        :day="day"
        :selected="!!~selectedWeekDays.indexOf(day.id)"
        :disabled="disabled || day.disabled"
        @check="checkDay"
        ></day>
    </ul>
</template>
<script>
import {mapActions, mapState, mapMutations} from 'vuex'
export default {
    props:{
        oneSelect: {
            type: Boolean,
            default: false
        },
        disabled: {
            type: Boolean,
            default: false
        },
        disabledDays: Array,
        dayStart: String/Number, 
        selectedWeekDays: {
            type: Array,
            default: ()=>{
                return []
            }
        }
    },
    data(){
        return{
            weekDays: [],
        }
    },
    computed:{
        ...mapState({
            day_startGlobal: state => state.shifts.day_start
        }),
        day_start(){
            return this.dayStart || this.day_startGlobal
        }

    },
    watch:{
        day_start: {
            handler(val){
                if(val){
                    this.initWeekGround();
                }
            },
            immediate: true
        }
    },
    methods:{
        initWeekGround() {
            this.weekDays = [];
            let weekList = [
                { name: "M", id: 1, disabled: false },
                { name: "T", id: 2, disabled: false },
                { name: "W", id: 3, disabled: false },
                { name: "T", id: 4, disabled: false },
                { name: "F", id: 5, disabled: false },
                { name: "S", id: 6, disabled: false },
                { name: "S", id: 7, disabled: false }
            ];
            let start = this.day_start - 1;
            for (let i = 0; i < 7; i++) {
                if (start > 6) {start = 0;}
                if(this.disabledDays.length){
                    if(~this.disabledDays.indexOf(start+1)){
                        weekList[start].disabled = true;
                    }
                }
                this.weekDays.push(weekList[start]);
                start++;    
            }
        },
        checkDay(state){
                if(state.isCheck){
                    if(this.oneSelect){
                        // this.selected = state.day;
                        this.$emit('selectWeekDay', state.day, true); 
                    }else{
                       this.$emit('selectWeekDay', state.day);  
                    }
                }else{
                    this.$emit('removeWeekDay', state.day); 
                } 
        }
    },
    components:{
        day: require('./Day')
    }
}
</script>
<style lang='scss' scoped>
.week-days-list{
    list-style: none;
    margin: 0;
    padding: 0;
    margin-left: auto;
}
</style>
