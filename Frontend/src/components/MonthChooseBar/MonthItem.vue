<template>
    <div class="month-item" @click.prevent="checkMonth" :class="{check: isCheck}">
        {{ month.month }}
    </div>
</template>
<script>
export default {
    props:{
        month: Object,
        selected: Boolean
    },
    data(){
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
        checkMonth(){
            this.isCheck = !this.isCheck;
            this.$emit('check',{
                month: this.month.id,
                isCheck: this.isCheck,
            })
        },
    }
}
</script>
<style lang='scss' scoped>
.month-item{
    width: 33.3333%;
    background-color: #e9eff2;
    border-bottom: 1px solid #fff;
    border-right: 1px solid #fff;
    color: #627680;
    transition: all ease 300ms;
    &:hover, &.check{
        background-color: $blue;
        color: #fff;
        cursor: pointer;
    }
}
</style>