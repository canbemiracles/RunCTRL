<template>
    <td class="month-day"  @click.prevent="checkDay" :class="{check: isCheck}">
        {{ day }}
    </td>
</template>
<script>
export default {
    props:{
        day: Number,
        selected: Boolean
    },
    data: function(){
        return {
            isCheck: false,
        }
    },
    watch:{
        selected: {
            handler(value){
                if(value){
                    this.isCheck = true;
                }else{
                    this.isCheck = false;
                }
            },
            immediate: true 
        },
    },
    methods:{
        checkDay(){
            this.isCheck = !this.isCheck;
            this.$emit('check',{
                day: this.day,
                isCheck: this.isCheck,
            })
        },
    }
}
</script>
<style lang='scss' scoped>
.month-day{
    width: 14.2857%;
    display: table-cell;
    border: 1px solid #c2ced4;
    transition: all ease 300ms;
    &:hover{
        background-color: $blue;
        color: #fff;
        cursor: pointer;  
    }
    &.check{
        background-color: $blue;
        color: #fff;
    }
}
</style>