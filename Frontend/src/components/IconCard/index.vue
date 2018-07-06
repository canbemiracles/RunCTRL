<template>
  <div class="assignment-card">
    <div class="row">
      <div id="standard_task"
        @click="select('standard')"
        :class="{selected: selected === 'standard'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon standard_task"><use xlink:href="images/icons-sprite.svg#standard_task"></use></svg>
        <span class="item-task__desc">Standard task</span>
      </div>
      <b-popover target="standard_task" v-if="popover"
        placement="leftbottom"
        title="Standard task"
        triggers="hover"
        content="Popover!">
      </b-popover>
      <div id="question_yes_no"
        @click="select('question_yes_no')"
        :class="{selected: selected === 'question_yes_no'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle flex-column">
        <svg class="icon question_yes_no"><use xlink:href="images/icons-sprite.svg#question_yes_no"></use></svg>
        <span class="item-task__desc">Yes/No</span>
      </div>
      <b-popover target="question_yes_no" v-if="popover"
        placement="rightbottom"
        title="Yes/No"
        triggers="hover"
        content="Popover!">
      </b-popover>
      <div id="question_4_answers"
        @click="select('question_answer_list')"
        :class="{selected: selected === 'question_answer_list'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon question_4_answers"><use xlink:href="images/icons-sprite.svg#question_4_answers"></use></svg>
        <span class="item-task__desc">4 Answers task</span>
      </div>
      <b-popover target="question_4_answers" v-if="popover"
        placement="rightbottom"
        title="4 Answers task"
        triggers="hover"
        content="Popover!">
      </b-popover>
    </div>
    <div class="row">
      <div id="numeric"
        @click="select('question_numeric')"
        :class="{selected: selected === 'question_numeric'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon question_numeric_input"><use xlink:href="images/icons-sprite.svg#question_numeric_input"></use></svg>
        <span class="item-task__desc">Numeric task</span>
      </div>
      <b-popover target="numeric" v-if="popover"
        placement="leftbottom"
        title="Numeric task"
        triggers="hover"
        content="Popover!">
      </b-popover>
      <div id="range"
        @click="select('question_range')"
        :class="{selected: selected === 'question_range'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon question_range"><use  xlink:href="images/icons-sprite.svg#question_range_from_x_to_y"></use></svg>
        <span class="item-task__desc">Range task</span>
      </div>
      <b-popover target="range" v-if="popover"
        placement="rightbottom"
        title="Range task"
        triggers="hover"
        content="Popover!">
      </b-popover>
      <div id="checklist"
        @click="select('checklist')"
        :class="{selected: selected === 'checklist'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon checklist"><use xlink:href="images/icons-sprite.svg#checklist"></use></svg>
        <span class="item-task__desc">Checklist task</span>
      </div>
      <b-popover target="checklist" v-if="popover"
        placement="rightbottom"
        title="Checklist task"
        triggers="hover"
        content="Popover!">
      </b-popover>
    </div>
    <div class="row">
      <div id="text_input"
        @click="select('question_text')"
        :class="{selected: selected === 'question_text'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon question_text_input"><use xlink:href="images/icons-sprite.svg#question_text_input"></use></svg>
        <span class="item-task__desc">Question task</span>
      </div>
       <b-popover target="text_input" v-if="popover" 
        placement="leftbottom"
        title="Question task"
        triggers="hover"
        content="Popover!">
      </b-popover>
      <div id="notification"
        @click="select('notification')"
        :class="{selected: selected === 'notification'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon notification"><use xlink:href="images/icons-sprite.svg#notification"></use></svg>
        <span class="item-task__desc">Notification task</span>
      </div>
      <b-popover target="notification" v-if="popover"
        placement="rightbottom"
        title="Notification task"
        triggers="hover"
        content="Popover!">
      </b-popover>
      <div id="message_to_employee"
        @click="select('message')"
        :class="{selected: selected === 'message'}"
        class="col-sm-4 item-svg align-items-center justify-content-center d-flex rectangle">
        <svg class="icon message_to_employee"><use xlink:href="images/icons-sprite.svg#message_to_employee"></use></svg>
        <span class="item-task__desc">Message task</span>
      </div>
      <b-popover target="message_to_employee" v-if="popover"
        placement="rightbottom"
        title="Message task"
        triggers="hover"
        content="Popover!">
      </b-popover>
    </div>
  </div>
</template>
<script>
import 'images/sprites/taskIcons/standard_task.svg'
import 'images/sprites/taskIcons/question_yes_no.svg'
import 'images/sprites/taskIcons/question_4_answers.svg'
import 'images/sprites/taskIcons/question_numeric_input.svg'
import 'images/sprites/taskIcons/question_range_from_x_to_y.svg'
import 'images/sprites/taskIcons/checklist.svg'
import 'images/sprites/taskIcons/question_text_input.svg'
import 'images/sprites/taskIcons/notification.svg'
import 'images/sprites/taskIcons/message_to_employee.svg'

export default {
 name: 'IconCard',
  props:{
    reset: {
      type: Boolean,
      default: false
    },
    popover: {
      type: Boolean,
      default: false
    },
  },
   data: ()=>({
    selected: '',
  }),    
  watch:{
    reset(value){
      if(value){
        this.resetSelect();
      }
    }
  },
  methods: {
    select(type) {
      this.selected = type
      this.$emit('selected', type) 
    },
    resetSelect(){
      this.selected = '';
    }
  },
}
</script>
<style lang="scss" src="./style.scss" scoped></style>
