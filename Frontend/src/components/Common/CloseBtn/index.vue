<template>
    <a href="#"  @click.prevent.stop="clickHendler">
        <svg width="15" class="icon-close">
            <use xlink:href="images/icons-sprite.svg#close-usage"></use>
        </svg>
    </a>
</template>
<script>
import "images/sprites/close.svg";
export default {
     methods:{
        clickHendler(){
           this.$emit('click'); 
        }
    }
}
</script>
<style lang='scss' scoped>
.icon-close{
    width: 12px;
    height: 12px;
    fill: #b0bfc6;
    transition: all ease 300ms;
    &:hover{
        fill: $gray;
    }
}
</style>