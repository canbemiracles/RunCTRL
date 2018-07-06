<template>
    <div class="roles-item"
        @mouseover="show=true"
        @mouseleave="show=false">
        <div class="role-item__inner" @click="clickHendler">
            <div class="roles-icon" v-color:bg="color">
                {{abbr}}
            </div>
            <div class="roles-label">
                {{name}}
            </div>
        </div>
        <div class="remove-role-item" v-if="remove" v-show="show">
            <close-btn @click="removeItem" class="close-btn"></close-btn>
        </div>
    </div>
</template>
<script>
import {abbrName} from '../../../../../Common/utils.js'
export default {
    props:{
        name: String,
        color: {
            type: String/Boolean,
            default: false
        },
        remove: false
    },
    data(){
        return {
            show: false,
        }
    },
    computed:{
        abbr(){
            return abbrName(this.name);
        }
    },
    methods:{
        clickHendler(){
           this.$emit('click');
        },
        removeItem(){
            this.$emit('remove');
        }
    },
    components:{
        closeBtn: require('../../../../../Common/CloseBtn')
    }
}
</script>
<style lang='scss' scoped>
.roles-item{
    max-width: 55px;
    width: calc(33.333% - 6px);
    margin-bottom: 20px;
    margin-right: 3px;
    margin-left: 3px;
    flex-shrink: 0;
    cursor: pointer;
    position: relative;
    text-align: center;
}
.roles-icon{
  font-size: 25px;
  display: inline-flex;
  flex-shrink: 0;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  text-align: center;
  text-transform: uppercase;
  font-family: 'Roboto-Light';
  color: $white;
  background-color: #f44336;
  &:before{
      content: "";
      padding-top: 100%;
  }
}
.roles-label{
  font-size: 12px;
  color: #505d63;
  line-height: 1.2;
  text-align: center;
  margin-top: 8px;
  text-transform: capitalize;
  overflow: hidden;
  text-overflow: ellipsis;
}
.remove-role-item{
   position: absolute;
   top: -10px;
   right: -9px;
   border-radius: 50%;
   width: 20px;
   height: 20px;
   display: flex;
   align-items: center;
   justify-content: center;
   .close-btn{
        width: 10px;
        height: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
   }
   & /deep/ .icon-close{
        width: 6px;
        height: 6px;
        stroke: $gray;
        stroke-width: 4px;
   }
}
</style>
