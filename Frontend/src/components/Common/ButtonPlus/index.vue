<template>
    <a href="#" class="add-btn" @click.prevent="clickHendler">
        <span class="add-btn-inner">+</span>
    </a>
</template>
<script>
export default {
    methods:{
        clickHendler(){
           this.$emit('click');
        }
    }
}
</script>
<style lang='scss' scoped>
.add-btn{
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #e9eff2;
    display: flex;
    flex-shrink: 0;
    line-height: 35px;
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
    }
}
</style>
