<template>
    <li
    :class="[{disable: option[valueFieldName]==null}, {'active': $parent.isActiveElement(index)}]"
        @click.stop="changeActiveSelect($event, option)" class="select-option-item" 
        >
        <div class="role-icon" v-if="roleIcon" v-color:bg="option.color ? option.color : ''">
            <svg class="check-icon" v-if="selected" width="16" height="12" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"/></svg>
        </div>
        <img v-if="img" :src="option[imgFieldName]" class="select-icon" />
        <div v-if="option[textFieldName]"> {{option[textFieldName]}}</div>    
    </li>
</template>
<script>
export default {
    props:{
        option: Object/Array,
        index: Number,
        img: Boolean,
        selected: Boolean,
        textFieldName: String,
        valueFieldName: String,
        imgFieldName: String,
        roleIcon: Boolean
    },
    methods:{
        changeActiveSelect(e, option){
            if(e){
               e.stopImmediatePropagation();  
            }
            if(option[this.valueFieldName] == null){
                return false;
            }
            let arr=[!this.selected, this.option]
            this.$emit('changeSelection', arr);
        }
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>