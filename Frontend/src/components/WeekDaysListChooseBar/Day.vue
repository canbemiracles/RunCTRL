<template>
    <li class="week-day" @click.prevent="checkDay" :class="[{check: isCheck}, {disabled: disabled }]">
        {{ day.name }}
    </li>
</template>
<script>
export default {
    props:{
        day: Object,
        selected: Boolean,
        disabled: {
            type: Boolean,
            default: false
        }
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
        disabled(value){
            this.isCheck = false;
            this.$emit('check',{
                day: this.day.id,
                isCheck: this.isCheck,
            })
        }
    },
    methods:{
        checkDay(){
            if(this.disabled){
                return false;
            }
            this.isCheck = !this.isCheck;
            this.$emit('check',{
                day: this.day.id,
                isCheck: this.isCheck,
            })
        },
    }

}
</script>
<style lang='scss' scoped>
.week-day{
    width: 27px;
    height: 27px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center; 
    background-color: #e9eff2;
    margin-left: 3px;
    font-size: 13px;
    color: #627680;
    cursor: pointer;
    transition: all ease 300ms;
    &:hover{
            background-color: $blue;
            color: #fff;
        } 
    &.disabled{
        background-color: lighten($lightgray, 23);
        color: #fff;
        cursor: default;
        &:hover{
            cursor: default;
        }
    }
    &.check{
    background-color: $blue;
    border: 2px solid $blue;
    color: #fff;
        &:hover{
            background-color: #e9eff2;
            color: lighten($lightgray, 20);
        }
    }
}
</style>