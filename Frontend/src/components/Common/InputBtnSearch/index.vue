<template>
    <div class="search-wrap d-flex">
        <div class="search-block">
            <input type="text" :value="value" ref='input'
                class="search-input" :class="{show: openSearch}"
                @input="typeSearch($event.target.value)"
                @change="changeSearch($event.target.value)"
                >
            
        </div>
        <a href="#" class="circle-btn" @click.prevent="clickHendler">
            <svg width="18" height="16" class="icon-user-plus" viewBox="0 0 2048 1792" xmlns="http://www.w3.org/2000/svg"><path d="M704 896q-159 0-271.5-112.5t-112.5-271.5 112.5-271.5 271.5-112.5 271.5 112.5 112.5 271.5-112.5 271.5-271.5 112.5zm960 128h352q13 0 22.5 9.5t9.5 22.5v192q0 13-9.5 22.5t-22.5 9.5h-352v352q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-352h-352q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h352v-352q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5v352zm-736 224q0 52 38 90t90 38h256v238q-68 50-171 50h-874q-121 0-194-69t-73-190q0-53 3.5-103.5t14-109 26.5-108.5 43-97.5 62-81 85.5-53.5 111.5-20q19 0 39 17 79 61 154.5 91.5t164.5 30.5 164.5-30.5 154.5-91.5q20-17 39-17 132 0 217 96h-223q-52 0-90 38t-38 90v192z"/></svg>
        </a>
    </div>
</template>
<script>
import 'images/sprites/search.svg'
export default {
    props: {
        value: String
    },
    data:()=>{
        return{
            openSearch: false,
        }
    },
    methods:{
        clickHendler(){
            this.openSearch = true;  
            this.$emit('click'); 
        },
        typeSearch(value){
            var formattedValue = value.trim();
            if (formattedValue !== value) {
                this.$refs.input.value = formattedValue
            }
            this.$emit('typeSearch', formattedValue);
        },
        changeSearch(value){
            this.$emit('changeSearch', value);
        }
    }
}
</script>

<style lang='scss' scoped>
.circle-btn{
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #e9eff2;
    display: flex;
    flex-shrink: 0;
    cursor: pointer;
    align-items: center;
    justify-content: center;
    font-size: 30px;
    color: #b0bfc6;
    transition: all ease 300ms;
    &:hover{
        text-decoration: none;
        color: $white;
        background-color: darken(#e9eff2, 10);
        .icon-user-plus{
            fill: #fff;
        }
    }
    .icon-user-plus{
        position: relative;
        left: 2px;
        top: -1px;
        fill: #8b9da6;
    }
}
.search-block{
    overflow: hidden;
    margin: 0 10px; 
}
.search-input{
    width: 100%;
    height: 100%;
    border: none;
    border-bottom: 1px solid rgba($lightgray, .3);
    &:focus{
        border: none;
        outline: none;
        border-bottom: 1px solid $blue;
    }
    transition: transform ease 300ms;
    transform: translateX(100%);
    &.show{
        transform: translateX(0%); 
    }
}

</style>