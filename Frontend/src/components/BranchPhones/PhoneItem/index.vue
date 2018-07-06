<template>
    <li class="item-phone d-flex" :class="{active: focus, error: validation.hasError('phone')}">
            <div class="item-phone-content d-flex">
                <img :src="phoneItem.icon" width="20" class="emergency-icon" />
                <div class="number-phone">
                    <input type="text"
                    v-model="phone"
                    @change="inputPhone" 
                    class="input-phone"
                    placeholder="Enter phone" @focus="focus=true"
                    @blur="focus=false">
                </div>
                <button type="button" class="close" v-if="phone" aria-label="Close" @click="resetField">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="error-message">{{validation.firstError('phone')}}</div>
    </li>
</template>
<script>
import { Validator } from "simple-vue-validator";
export default {
    mixins: [require("simple-vue-validator").mixin],
    props:{
        phoneItem: Object
    },
    data: ()=>{
        return {
           phone: "",
           focus: false
        } 
    },
     validators: {
        "phone"(value) {
            return Validator.value(value).integer();
        },
    },
    mounted(){
        let unwatch = this.$watch('phoneItem.phone', function(phone){
            if(phone){
                this.phone = phone;
                this.$validate();  
            }
        }, { immediate: true });
        setTimeout(()=>{
          unwatch();  
        }, 10000);
    },
    methods:{
       inputPhone(){
           this.$validate().then((success)=>{
                if(!success) {
                    this.$parent.$emit('inputValid', false);
                }else{
                    this.$parent.$emit('inputValid', true);
                    this.$parent.$emit('inputPhone', this.phone, this.phoneItem.name); 
                }
               
           })
       },
       resetField(){
           this.phone="";
       } 
    }
}
</script>
<style lang='scss' scoped>
.item-phone-content{
    padding: 15px;
    position: relative;
    padding-right: 30px;
    width: 100%;
    height: 100%;
    flex: 1;
    align-items: center;
}
.error-message{
    position: absolute;
    bottom: -27px;
}
.item-phone{
    border-radius: 2px;
    background-color: $white;
    border: 1px solid #ccd7dd;
    height: 50px;
    margin-right: 10px;
    position: relative;
    transition: ease 300ms all;
    &:last-child{
        margin-right: 0;
    }
    &.active{
      border: 1px solid $blue;  
    }
    &.error{
        margin-bottom: 10px;
    }
}
.emergency-icon{
    width: 20px;
    height: 20px;
    margin-right: 15px;
}
.number-phone{
    flex-grow: 1;
}
.input-phone{
    border: 0;
    font-size: 18px;
    padding: 0;
    &:focus{
        border: 0;
        outline: none;
        box-shadow: none;
    }
}
.close{
    position: absolute;
    right: 15px;
    top: 0;
    bottom: 0;
}
.close:focus{
    outline: none;
    box-shadow: none;
    border: none;
}
</style>