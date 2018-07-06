<template>
    <ul class="color-list">
        <color-item 
        v-for="(color, index) in colors"
        :key="index"
        :color="color"
        @chooseColor="chooseColor"
        ></color-item>
    </ul>
</template>
<script>
export default {
    props:{
        colors: Array
    },
    data: function(){
        return {
            
        }
    },
    methods:{
        chooseColor(color){
            this.$emit('chooseColor', color);
        }
    },
    components: {
        colorItem: require('./ColorItem')
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>