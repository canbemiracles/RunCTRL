<template>
    <li class="weekdays-list__item">
        <a href="#" @click.prevent="checkDay" class="day" :class="[{check: isCheck}, {disabled: disabled || (incorrectTime && !isCheck) || intersect && !isCheck}, {error: (intersect && isCheck) || (incorrectTime && isCheck)}]">
            <span class="day-content">{{day}}</span>
        </a>
    </li>
</template>
<script>
import {mapGetters, mapMutations} from 'vuex';
import timeFunctions from '../../../../../../Common/Mixins/timesFunctions'
export default {
    mixins: [timeFunctions],
    props:{
        day: String,
        index: Number,
        shiftId: Number,
        rowId: Number,
        selected: Boolean,
        disable: Boolean,
        edit_id: Number
    },
    data: function(){
        return {
            isCheck: false,
            disabled: false,
            time: null,
            intersect: false,
            error: false,
            
            incorrectTime: false
        }
    },
    computed:{
        ...mapGetters(['shift_groups', 'shifts']),
        listBusyDays: function(){
            let list;
            this.shift_groups.forEach(item=>{
                if(item.shift_id == this.shiftId){
                    list =  item.listBusyDays;
                }
            });
            return list;
        },
        time_rows: function(){
            let tr;
            this.shift_groups.forEach(item=>{
                if(item.shift_id == this.shiftId){
                    tr = item.time_rows;
                }
            }); 
            return tr;
        }
        
    },
    mounted(){
        if(this.selected){
            this.isCheck=true;
        }
        if(this.disable){
            this.disabled=true;
        }
        this.$watch('shift_groups', function(value){
            this.getTime();
        }, {
            deep: true,
            immediate: true
        });
        this.$watch('shifts', function(value){
            this.getTime();
        }, {
            immediate: true
        });
    },
    watch:{
        listBusyDays: {
            handler: function(value){
                if(!value.length){
                    this.isCheck=false;
                }
                let found = value.find(element => element.day==this.index);
                if(found){
                    if(!this.isCheck){
                       this.disabled=true;     
                    }
                }else{
                    this.disabled=false;
                }
            },
        },
        time: {
            handler: function(value){
                if(value){
                    let t1_start = moment(this.time.time_open, 'HH:mm:ss');
                    let t1_end = moment(this.time.time_close, 'HH:mm:ss');

                    if(t1_end.isSame(t1_start)){
                        this.incorrectTime = true; //если длительность шифта 0
                    }else{
                        this.incorrectTime = false;
                    }  
                }
            },
            deep: true
        }
    },
    methods:{
        ...mapMutations(['setValidate', 'setValidateArr', 'setErrorStatusShift']),
        checkDay(){
            if(this.disabled || (this.incorrectTime && !this.isCheck) || (this.intersect && !this.isCheck)){
                return false;
            }
            this.isCheck = !this.isCheck;
            this.checkIntersecting();
            this.$emit('check',{
                day: this.index,
                isCheck: this.isCheck,
                error: this.error,
                id: this.edit_id
            })
        },
        getTimebyRowId(shift_id, row_id){
            let time;
            this.shift_groups.forEach(elem=>{
                if(elem.shift_id == shift_id){
                    elem.time_rows.forEach(item => {
                        if(item.timeRow_id == row_id){
                            time = {
                                time_close: item.time_close,
                                time_open: item.time_open
                            };
                        }
                    });
                }
            });
            return time;
        },
        getTime(){
            //определяем время для данного дня
            this.time = this.getTimebyRowId(this.shiftId, this.rowId);
            this.checkIntersecting(); 
        },
        checkIntersecting(){
            if(this.shifts.length==0){
                this.intersect=false;
                this.error = false;
                return false;
            }
            let found=false;
            for(let i=0; i<this.shifts.length; i++){
                let element = this.shifts[i];
                
                //находим другие шифты
                if(element.shift_day == this.index && !this.disabled && element.shift_id != this.shiftId){
                    found=true;
                    //определяем время для другого шифта
                    let anotherShiftTime = this.getTimebyRowId(element.shift_id, element.timeRow_id);
                    //Проверяем пересечение диапазонов времени
                    let t1_start = moment(this.time.time_open, 'HH:mm:ss');
                    let t1_end = moment(this.time.time_close, 'HH:mm:ss');

                    let t2_start = moment(anotherShiftTime.time_open, 'HH:mm:ss');
                    let t2_end = moment(anotherShiftTime.time_close, 'HH:mm:ss');

                    let range1 = moment.range(moment(this.time.time_open, 'HH:mm:ss'), moment(this.time.time_close, 'HH:mm:ss'));
                    let range2 = moment.range(moment(anotherShiftTime.time_open, 'HH:mm:ss'), moment(anotherShiftTime.time_close, 'HH:mm:ss'));
                
                    if(t1_end.isBefore(t1_start)){
                        let part1 = moment.range(t1_start, moment('24:00:00', 'HH:mm:ss'));
                        let part2 = moment.range(moment('00:00:00', 'HH:mm:ss'), t1_end );
                        if(t2_end.isBefore(t2_start)){
                            let anotherPart1 = moment.range(t2_start, moment('24:00:00', 'HH:mm:ss'));
                            let anotherPart2 = moment.range(moment('00:00:00', 'HH:mm:ss'), t2_end );
                            anotherPart1 = part1.overlaps(anotherPart1);
                            anotherPart2 = part2.overlaps(anotherPart2);
                            this.intersect = anotherPart1 || anotherPart2;
                        }else{
                            part1 = part1.overlaps(range2);
                            part2 = part2.overlaps(range2);
                            this.intersect = part1 || part2;
                        }
                    }else if(t2_end.isBefore(t2_start)){
                        let part1 = moment.range(t2_start, moment('24:00:00', 'HH:mm:ss'));
                        let part2 = moment.range(moment('00:00:00', 'HH:mm:ss'), t2_end );
                        part1 = part1.overlaps(range1);
                        part2 = part2.overlaps(range1);
                        this.intersect = part1 || part2;
                    }else if(t1_end.isSameOrAfter(t1_start) && 
                        t2_end.isSameOrAfter(t2_start)){
                        this.intersect = range1.overlaps(range2);
                    }else{
                        this.intersect = false;
                    }
                    if(this.isCheck && this.intersect){
                        element.error = true;
                    }else{
                        element.error = false;
                    }
                }else if(this.intersect && this.index == element.shift_day && this.selected){
                    this.intersect = false;
                }
            }
            if(found==false && !this.incorrectTime){
               this.intersect = false; 
            }
            
            if((this.isCheck && this.intersect) || this.incorrectTime){
                this.error = true;
                this.setErrorStatusShift({ shift_id: this.shiftId,  shift_day: this.index, error:  true});
            }else{
                this.error = false;
                this.setErrorStatusShift({ shift_id: this.shiftId,  shift_day: this.index, error:  false});
            }
        }
    }
}
</script>
<style lang="scss" scoped>
.day{
    display: flex;
    width: 100%;
    min-width: 35px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Roboto-Medium";
    border-radius: 50%;
    background-color: #ffffff;
    border: 2px solid lighten($lightgray, 25);
    transition: all ease 300ms;
    color: lighten($lightgray, 20);
    position: relative;
    padding-bottom: 100%;
    box-sizing: content-box;
    &:hover{
        text-decoration: none;
        background-color: $blue;
        color: #fff;
        border: 2px solid $blue;
    } 
    .day-content{
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        min-width: 100%;
        min-height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    &.disabled{
        background-color: lighten($lightgray, 25);
        color: #fff;
        cursor: default;
        &:hover{
            border: 2px solid lighten($lightgray, 25);
            cursor: default;
        }
    }
}
.check{
    background-color: $blue;
    border: 2px solid $blue;
    color: #fff;
    &:hover{
        background-color: #ffffff;
        color: lighten($lightgray, 20);
    }
}
.error{
    background-color: $cancelRed;
    border-color: $cancelRed;
    &:hover{
       background-color: $white;
       border-color: $cancelRed; 
    } 
}
.weekend{
    color: $lightgrayTxt;
}
.weekdays-list__item{
    margin-right: 1rem;
    max-width: 55px;
    width: 14%;
    &:last-child{
        margin-right: 0;
    }
}
</style>

