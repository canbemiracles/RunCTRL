<template>
    <li class="color-item d-flex" ref="colorwrap" @mouseover="setColor" @mouseleave="resetColor">
        <a href="#" class="color-item__inner" v-color:bg="color" @click.prevent="chooseColor"></a>
    </li>
</template>
<script>
export default {
    props:{
        color: String
    },
    data: function(){
        return{
            
        }
    },
    methods:{
        setColor(){
            $(this.$refs.colorwrap).css('borderColor', '#' + this.color);
        },
        resetColor(){
            $(this.$refs.colorwrap).css('borderColor', 'transparent');
        },
        chooseColor(){
            this.$emit('chooseColor', this.color);
        }
    }
}
</script>
<style lang='scss' scoped>
.color-item{
   width: 41px;
   height: 41px;
   align-items: center;
   justify-content: center;
   border: 1px solid transparent;
   transition: all ease 300ms;
   border-radius: 50%; 
   flex-shrink: 0;
}
.color-item__inner{
    display: block;
    width: 35px;
    height: 35px;
    border-radius: 50%;
    background-color: #2196f3; 
}
</style>