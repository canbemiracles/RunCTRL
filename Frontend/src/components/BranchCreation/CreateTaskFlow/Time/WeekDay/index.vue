<template>
    <li class="weekdays-list__item" :class="{selected}" >
        <p class="day">{{day}}</p>
        <a href="#" 
            @click.prevent
            class="number"
            :class="[{check: isCheck}, {disabled : disabled}]">
        {{number}}
        </a>
    </li>
</template>
<script>
export default {
    props:{
        number: Number,
        day: String,
        disabled: Boolean,
        timerows: Array,
        selected: Boolean
    },
    data: function(){
        return {
            isCheck: false,
            time: null,
        }
    },
    methods:{
    }
}
</script>
<style lang="scss" scoped>
.number{
    display: flex;
    width: 100%;
    min-width: 50px;
    font-size: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-family: "Roboto-Medium";
    border-radius: 50%;
    background-color: #fff;
    border: 2px solid lighten($lightgray, 25);
    transition: all ease 300ms;
    color: lighten($lightgray, 20);
    &:hover{
        text-decoration: none;
        background-color: $blue;
        color: #fff;
        border: 2px solid $blue;
    }
    &:before{
        content: "";
        padding-top: 100%;
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
.selected a{
    background-color: $blue;
    border: 2px solid $blue;
    color: #fff;
    &:hover{
        background-color: #ffffff;
        color: lighten($lightgray, 20);
    }
}
.weekdays-list__item{
    list-style: none;
    &:last-child{
        margin-right: 0;
    }
    .day {
        margin-bottom: 5px;
        text-align: center;
        font-family: 'Roboto-Bold';
        font-size: 12px;
        color: #627680;
    }
}

</style>

