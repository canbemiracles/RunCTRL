<template>
    <li class="autocomplete__item" @click.stop="check"
    :class="{'active': $parent.isActive(index)}"> 
        <div v-if="suggestion.imgSrc" class="picture-placeholder" :style="{backgroundImage: `url(${suggestion.imgSrc})`}"></div>
        <span>{{ suggestion[fieldName] }}</span> 
        <span class="label-hint" v-if="labelField && suggestion[labelField]">
            &ndash;&nbsp; {{ suggestion[labelField] }}
        </span>
        <div class="add-btn" :class="{check: selected}"></div>
    </li>
</template>
<script>
export default {
    props:{
        index: Number,
        suggestion: Object,
        labelField: String,
        fieldName: String,
        selected: Boolean
    },
    methods:{
        check(){
            let arr = [!this.selected, this.suggestion]
            this.$emit('itemClick', arr);
        },
    },
}
</script>

<style lang="scss" scoped>
.picture-placeholder{
    width: 25px;
    height: 25px;
    margin-right: 0.5em;
    overflow: hidden;
    border-radius: 50%;
    background-size: cover;
    background-repeat: no-repeat;
    flex-shrink: 0;
}
.text{
    width: 100%;
    padding: 0 10px;
}
.add-btn{
    width: 20px;
    height: 20px;
    margin-left: auto;
    font-family: "Roboto-Regular";
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background-color: transparent;
    flex-shrink: 0;
    transition: all ease 300ms;
    &:after{
        content: "+";
        display: block;
        font-size: 18px;
        color: #97a7af;
    }
}
.add-btn.check{
    transition: all ease 300ms;
    background-color: $blue;
    &:after{
        content: url('data:image/svg+xml; utf8, <svg width="100%" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"  fill="#fff"/></svg>');
        width: 10px;
    }
   
}

</style>