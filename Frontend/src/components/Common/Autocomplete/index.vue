<template>
    <div class="autocomplete-wrap" ref="autocomplete" :class="[{'open':openSuggestion}, {disabled : disabled}]">
        <input :class="{'field-error': isError}" type="text"
            autocomplete="off"
            :disabled="disabled"
            :placeholder="placeholder" class="input_placeholder form_input"
            v-model="selection"
            @keydown.enter = 'enter'
            @keydown.down = 'down'
            @keydown.up = 'up'
            @keydown.esc="esc"
            @click.stop='openDrop'
            @input = 'change($event)'
            @change="changeInput">
        <svg v-if="iconSearch" xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" width="16px" height="17px" viewBox="0 0 612.08 612.08" style="enable-background:new 0 0 612.08 612.08;" xml:space="preserve" class="search_icon">
            <path d="M237.927,0C106.555,0,0.035,106.52,0.035,237.893c0,131.373,106.52,237.893,237.893,237.893   c50.518,0,97.368-15.757,135.879-42.597l0.028-0.028l176.432,176.433c3.274,3.274,8.48,3.358,11.839,0l47.551-47.551   c3.274-3.274,3.106-8.703-0.028-11.838L433.223,373.8c26.84-38.539,42.597-85.39,42.597-135.907C475.82,106.52,369.3,0,237.927,0z    M237.927,419.811c-100.475,0-181.918-81.443-181.918-181.918S137.453,55.975,237.927,55.975s181.918,81.443,181.918,181.918   S338.402,419.811,237.927,419.811z" fill="#93aab5" />
        </svg>
        <div v-show="openSuggestion" :class="dropDownClass" ref="scroll" class="scroll-pane select-options autocomplete-dropdown">
            
            <ul class="select-options-list" ref="dropdownMenu">
                <slot name="firstField"></slot>
                <li class="autocomplete__item"
                v-for="(suggestion, index) in matches" 
                :key="index"
                :class="{'active': isActive(index)}"
                @click.stop="suggestionClick(suggestion)"
                > 
                    <div class="role-icon" v-if="roleIcon" v-color:bg="suggestion.color ? suggestion.color : ''"></div>
                    <span>{{ suggestion[fieldName] }}</span> 
                    <span class="label-hint" v-if="labelField && suggestion[labelField]">
                      &ndash;&nbsp; {{ suggestion[labelField] }}
                    </span>
                </li>
            </ul>
        </div>

    </div>
</template>
<script>
import pointerScroll from "../Mixins/pointerScroll";
import autocomplete from '../Mixins/autocompleteMixin'
export default {
  mixins: [pointerScroll, autocomplete],
  props: {
    id: String,
    placeholder: {
      default: 'Search'
    },
    suggestions: {
      type: Array,
      required: true
    },
    isError: {
      default: false
    },
    dropDownClass: "",
    fieldName: {
      type: String
    },
    init: {
      type: Number,
      default: null
    },
    text: {
      type: String,
      required: false
    },
    disabled: false,
    iconSearch: {
      type: Boolean,
      default: true
    },
    clearField: {
      type: Boolean,
      default: false
    },
    labelField:{
      type: Boolean/String,
      default: false
    },
    close:{
      type: Boolean,
      default: false
    },
    roleIcon: {
      type: Boolean,
      default: false
    },
  },
  watch: {
    selection(value) {
      if (!value) {
        this.$emit("noSelection", this.id);
      }
      let that = this;
      this.matches.forEach(function(element) {
        if (element[that.fieldName].toLowerCase() != value.toLowerCase()) {
          that.$emit("noSelection", that.id);
        }
      });
    }, 
    clearField(value){
      if(value){
        this.selection = '';
        this.$emit('clearField');
      }
    },   
    init: {
      handler(value) {
        if (value) {
          this.$watch("suggestions", function(newVal, oldVal) {
                this.initField(value);
            },
            {
              immediate: true
            }
          );
        }
      },
      immediate: true
    },
    text(value) {
      if (value && value != '') {
        this.selection = value
      }
    },
    suggestions(value){
      if(!value.length){
        this.selection='';
      }
    },
    close(val){
      if(val){
        this.open = false;
      }
    }
  },
  methods:{
     //When one of the suggestion is clicked
    suggestionClick(suggestion, init=false) {
      if(init){
        this.matches.forEach(elem=>{
          if(elem.id == suggestion){
            this.selection = elem[this.fieldName];
            this.$emit("suggestionClick", elem);
          }
        });
      }else{
        this.selection = suggestion[this.fieldName];
        this.open = false;
        this.$emit("suggestionClick", suggestion);
      }
      
    },
    initField(id) {
      this.suggestionClick(id, true);
    },
    //When enter pressed on the input
    enter() {
      if(!this.open){
        this.openDrop();
      }else{
        this.selection = this.matches[this.current][this.fieldName];
        this.open = false;
        this.$emit("suggestionClick", this.matches[this.current]);
      }
    },
    changeInput(){
      this.$emit('changeSearch', this.selection);
    }
  }
};
</script>
<style lang='scss' src='./style.scss' scoped></style>