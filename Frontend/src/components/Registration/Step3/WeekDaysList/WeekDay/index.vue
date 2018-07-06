<template>
    <li class="weekdays-list__item">
        <a href="#" @click.prevent="checkDay" class="day" :class="[{check: isCheck}, {disabled: disabled }]">
            <span class="day-content">{{day}}</span>
        </a>
    </li>
</template>
<script>
export default {
    props:{
        day: String,
        index: Number,
        selected: Boolean,
        disable: Boolean
    },
    data: function(){
        return {
            isCheck: false,
            disabled: false,
        }
    },
    mounted(){
        if(this.selected){
            this.isCheck=true;
        }
        if(this.disable){
            this.disabled=true;
        }
    },
    methods:{
        checkDay(){
            if(this.disabled){
                return false;
            }
            this.isCheck = !this.isCheck;
            this.$emit('check',{
                day: this.index,
                isCheck: this.isCheck,
            })
        },
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