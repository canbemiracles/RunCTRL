<template>
    <li class="employee-item d-flex flex-column" @click="selectItem">
        <div class="user-picture">
            <img :src="employee.avatar ? $http.options.root +'/'+ employee.avatar.path : require('images/user_default.jpg')" alt="" class="user-image">
            <div class="selected" :class="{active: employee.selected}"></div>
        </div>
        <div class="user-name">
            {{employee.first_name}} <br> {{employee.last_name}}
        </div>
    </li>
</template>
<script>
export default {
    props:{
        employee: Object
    },
    methods:{
        selectItem(){
            this.$emit('selected', this.employee.id);
        }
    }
}
</script>
<style lang='scss' scoped>
.employee-item{
    flex: 1;
    max-width: 100px;
    padding: 15px 20px;
    cursor: pointer;
}
.user-picture{
   width: 60px;
   height: 60px;
   border-radius: 50%;
   overflow: hidden;
   position: relative;
   margin-bottom: 5px;
}
.selected{
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    opacity: 0;
    transition: all ease 400ms;
    background-color: rgba($blue, .9);
    border-radius: 50%; 
    &:after{
        content: url('data:image/svg+xml; utf8, <svg width="100%" height="100%" viewBox="0 0 1792 1792" fill="#fff" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"/></svg>');
        width: 22px;
        flex-shrink: 0;
        position: relative;
        top: 2px;
    }
    &.active{
        opacity: 1;
    }
} 
.user-image{
    width: 100%;
}
.user-name{
  font-size: 15px;
  text-align: center;
  color: #505d63;
  line-height: 1.2;
}

</style>