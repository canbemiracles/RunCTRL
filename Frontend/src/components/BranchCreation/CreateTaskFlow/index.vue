<template>
  <div class="slide-section">
    <assignment-type v-model="type"/>
    <task-description :type="type" v-model="description" />
    <roles v-model="assignments" :type="type" v-if="type!='message'"/>
    <employees v-model="assignments" :type="type" v-if="type=='message'"/>
    <assignment-time v-model="assignments" :type="type"
    @timeRequest="timeRequest"
    @complete="$router.push({name:'taskCalendar'})"
    :active="currentSlide >= 3" />
    <section class="slide-section-container flex-column d-flex"  v-if="type.indexOf('notification')==-1 && type!='message'">
      <div class="slide-content d-flex flex-column" >
        <priority v-model="priority"/>
        <importance v-model="importance" 
        @complete="$router.push({name:'taskCalendar'})"
        @duplicate="dublicateTask"/>
      </div>
    </section>

  </div>
</template>

<script>
import { mapMutations } from 'vuex'
import scroll from './../../Common/Mixins/windowScroll';
export default {
  mixins:[scroll],

  data() {
    return {
      id: 0,
      branch: 0,
      type: '',
      description: {
        title: '',
        info: '',
        checklist: [],
        answers: []
      },
      assignments: [],
      timeData: [],
      priority: 1,
      importance: 1,
      copyAssignments: {}
    }
  },

  watch: {
    type: function(type, oldType){
      console.log(type, oldType);
      if(type){
        if(~type.indexOf('notification') 
          || ~oldType.indexOf('notification')
          || type=='message'
          || oldType == 'message'){
          console.log(type, oldType);
          this.$nextTick(function(){
            this.$emit('reInitScroll');
          })
        }
        this.accessToSlides(1);
        this.moveDownTo(1);
      }
    },
    description: {
      handler(value) {
        this.isValid && this.accessToSlides(2)
      },
      deep: true
    },

    assignments: {
      handler(assignments) {
        console.log('asssignments changed');
        this.checkAssignmentsValid();
      },
      deep: true
    },
    importance(value){
      if(value){
        this.sendData({ importance: value }, 6)
      }
    },
    priority(value){
      if(value){
        this.sendData({ priority: value }, 5)
      }
    },
    currentSlide(i, prev) {
      switch (i) {
        case 2: // На слайде "desciption" edit задачу с ранее введенными данными
          if(!_.isEmpty(this.copyAssignments)){
            this.sendData(this.typeData, 2);
          }
          break
        case 3: // На слайде "дата и время" создаем задачу с ранее введенными данными
          this.sendData(this.typeData, 3);
          break

        case 4: // На слайде "приоритет" патчим дату и время
          this.sendData(null, 4);
          break
        case 0: //если вернулись к началу очищаем все поля для создания нового ассаймента
          // this.clearFieldsData();
          // [1,2,3,4,5].forEach(index => this.setValidateArr({ index, val: false }));
          break
      }
    }
  },
  computed: {
    typeData() {
      if(this.type){
        const { title, info, answers, checklist} = this.description

        return {
          standard: { title, description: info },
          question_yes_no: { title },
          question_answer_list: { title, possible_answers: answers },
          question_numeric: { title },
          question_range: { title },
          checklist: { title, tasks: checklist },
          question_text: { title },
          notification: { title, description: info},
          notification_branch: { title, description: info},
          notification_station: { title, description: info},
          notification_role: { title, description: info},
          message: { title, description: info}
        }[this.type]
      }
    },

    // Проверка введены ли все данные на слайде с заголовком
    isValid() {
      if(this.type){
         return Object
        .values(this.typeData)
        .every(v => Array.isArray(v) ? v.length : v)
      }
    }
  },

  methods: {
    ...mapMutations(['setValidateArr', 'setValidate']),
  patchTimeData(timeData){
      let day = moment(timeData.dateStr).format('YYYY-MM-DD');
      let sendData =  {
          start_time: day + ' ' +  timeData.time.time_open,
          end_time: day + ' ' + timeData.time.time_close,
      }
      if(timeData.repeat){
          sendData.repeat_unit = timeData.repeat_unit;
          sendData.repeat_subunit = timeData.repeat_subunit;
          if(timeData.repeat_unit == 2){
              sendData.repeat_week_days =  timeData.repeat_week_days_week.map(day=> ({week_day: day}));
          }
          if(timeData.repeat_unit == 3 && timeData.pickedMonthRepeat == 'day'){
              sendData.repeat_month_days = timeData.month_days.map(day=> ({month_day: day}));
          }
          if(timeData.repeat_unit == 3 && timeData.pickedMonthRepeat == 'week'){
              sendData.repeat_week_days =  timeData.repeat_week_days_month.map(day=> ({week_day: day}));
              sendData.repeat_week =  timeData.repeat_week_month;
          }
          if(timeData.repeat_unit == 4){
              sendData.repeat_months =  timeData.repeat_months; 
          }
          if(timeData.repeat_unit == 4 && timeData.pickedYearRepeatWeek){
              sendData.repeat_week_days =  timeData.repeat_week_days_year.map(day=> ({week_day: day}));
              sendData.repeat_week =  timeData.repeat_week_year;
          }
          if(timeData.repeat_unit == 4 && !timeData.pickedYearRepeatWeek){
              let dates = [];
              timeData.repeat_month_days.forEach(el=>{
                  dates.push(moment(el.month_day, 'MMM D, YYYY').getDate());
              });
              sendData.repeat_month_days = dates;
          }
      }else{
          sendData.repeat_unit = null;
          sendData.repeat_subunit = null;
          sendData.repeat_week = null;
          // timeData.repeat_unit = null;
          // timeData.repeat_subunit = null;
          // timeData.repeat_week = null;
      }
      if(this.type.indexOf('notification')==-1){
          sendData.snooze_max = timeData.snooze_max;
          sendData.snooze_time = timeData.snooze_time;
      }
      return sendData;
    },
    // Доступ к слайдам
    accessToSlides(...indexes) {
      this.setValidate(true)
      indexes.forEach(index => this.setValidateArr({ index, val: true }))
    },
    timeRequest(item, method, sectionNumber){
        let sendData = this.patchTimeData(item.timeData); 
        this.copyDataBeforeSend(sendData, sectionNumber);  
        if(!item.timeData.repeat){
          item.timeData.repeat_month_days.forEach((elem, index) =>{
            item.timeData.dateStr = moment(elem.month_day, 'MMM D, YYYY').toISOString(true);
            let method;
            let sendData = this.patchTimeData(item.timeData);
            if(index==0){
              method = 'patch';
              this.sendSingleData(sendData, item, method);
            }else if(item.copyIDs && item.copyIDs.length){
              method = 'patch';
              let copyItem = _.cloneDeep(item);
              debugger;
              copyItem.id = item.copyIDs[index -1];
              this.sendSingleData(sendData, copyItem, method); 
            }else{
              method = 'post';
              this.$set(item, 'copyIDs', []);
              sendData = _.assign(sendData, this.typeData);
              this.sendSingleData(sendData, item, method, true);
            }
          });
        }else{
          if(item.copyIDs && item.copyIDs.length){
            debugger;
            let copyItem = _.cloneDeep(item);
            item.copyIDs.forEach(elem=>{
              copyItem.id = elem;
              this.sendSingleData(sendData, copyItem, method); 
            });
          }
          this.sendSingleData(sendData, item, method);
        }
    },
    dublicateTask(){
      this.moveUpTo(0);
      setTimeout(()=>{
        this.copyAssignments = [];
      }, 800); //clear copy after animation scroll (800ms)
    },
    makeURL(brId, stId, ending, roleId, emplId) {
      const typeUrl = {
        standard: 'tasks',
        question_yes_no: 'questions/yes_no',
        question_answer_list: 'questions/answer_lists',
        question_numeric: 'questions/numeric',
        question_range: 'questions/range',
        checklist: 'checklists',
        question_text: 'questions/text',
        notification: 'device_notifications',
        notification_branch: 'device_notifications',
        notification_station: 'device_notifications',
        notification_role: 'device_notifications',
        message: 'device_notifications'
      }[this.type]
      if(~this.type.indexOf('notification')){
        if(!stId && !roleId){
          return `api/v1/branches/${brId}/${typeUrl}/${ending}`
        }else if(!roleId){
          return `api/v1/branches/${brId}/stations/${stId}/${typeUrl}/${ending}`
        }else{
          return `api/v1/branches/${brId}/stations/${stId}/roles/${roleId}/${typeUrl}/${ending}`
        }
      }else if(this.type=='message'){
        return `api/v1/branches/${brId}/employees/${emplId}/${typeUrl}/${ending}`
      }else{
        return `api/v1/branches/${brId}/stations/${stId}/assignments/${typeUrl}/${ending}`
      }
    },

    // Создание и патчи задач
    sendData(data, sectionNumber) {
      console.log(data);
      let method;
      let sendData = _.cloneDeep(data);
      let compareAssignments = this.assignments
          .map(({branches, selectedRole, selectedStation, selectedEmployee}) => ({branches, selectedRole: selectedRole ? selectedRole.id : selectedRole, selectedStation, selectedEmployee}));
      for(let i=0; i < this.assignments.length; i++){
        let item = this.assignments[i];
        if(!this.copyAssignments.sectionRole || !this.copyAssignments.sectionRole.length){
          method = 'post'
        }else if(this.copyAssignments.sectionRole.length){
          let itemCompare = compareAssignments[i]
          let itemCopyRole = this.copyAssignments.sectionRole[i];
          //Проверка на изменения
          if(_.isEqual(itemCompare, itemCopyRole)){
            method = 'patch';
            console.log('no changes in  send data of section 4 (roles)' , i);
            if(sectionNumber == 2 || sectionNumber == 3){
              if(_.isEqual(this.typeData, this.copyAssignments.sectionTitle)){
                console.log('no changes in send data of section 2, 3 (title)');
                continue;
              }     
            }
            if(sectionNumber == 5){
              if(_.isEqual({ priority: this.priority }, this.copyAssignments.sectionPriority)){
                console.log('no changes in send data of section 5 (priority)');
                continue;
              }     
            }
            if(sectionNumber == 6){
              if(_.isEqual({ importance: data.importance }, this.copyAssignments.sectionImportance)){
                console.log('no changes in send data of section 6 (importance)');
                continue;
              }     
            }
          }else{
            method = 'post';
            this.copyDataBeforeSend(sendData, sectionNumber);
            console.log('there are changes in  send data of section 4 (roles)' , i);
          }
        }
          console.log(method);
          if(method == 'patch' && (sectionNumber == 4 || sectionNumber == 3)){
            let itemCopyTime = this.copyAssignments.sectionTime[i];
            if(_.isEqual(item.timeData, itemCopyTime)){
              console.log(`no changes in section 3 item ${i} of send time data`);
              //сравниваются объекты слайдa "time"
              continue;
            }else{
              console.log(`there are changes in section 3 item ${i} of send time data`);
              this.timeRequest(item, method, sectionNumber);
              continue;
            }
          }else{
            //Сразу отправляем дефолтное время, priority
            if(method == 'post'){
              sendData = _.cloneDeep(this.typeData);
              let timeData = this.patchTimeData(item.timeData);
              let priorityData = { priority: this.priority , importance: this.importance };
              _.assignIn(sendData, timeData, priorityData);
              this.sendSingleData(sendData, item, method);
            }else{
              if(item.copyIDs){
                this.sendSingleData(sendData, item, method);
                let copyItem = _.cloneDeep(item);
                item.copyIDs.forEach(elem=>{
                  copyItem.id = elem;
                  this.sendSingleData(sendData, copyItem, method); 
                });
              }else{
                this.sendSingleData(sendData, item, method); 
              }
            }            
          }
      }
      if(sectionNumber != 4 && sectionNumber != 3){
        this.copyDataBeforeSend(sendData, sectionNumber);
      }else if(method == 'post'){
        this.copyDataBeforeSend(sendData, 'all');
      }
    },
    copyDataBeforeSend(sendData, sectionNumber){
      let copy_assignments = _.cloneDeep(this.assignments);
      if(sectionNumber == 2){
        this.copyAssignments.sectionTitle = this.typeData;
      }
      if(sectionNumber == 3){
        this.copyAssignments.sectionTitle = this.typeData;
        this.copyAssignments.sectionRole = copy_assignments 
          .map(({branches, selectedRole, selectedStation, selectedEmployee}) => ({branches, selectedRole: selectedRole ? selectedRole.id : selectedRole, selectedStation, selectedEmployee}));
        this.copyAssignments.sectionTime = copy_assignments
          .map(({timeData}) => (timeData));
      }
      if(sectionNumber == 4){
        this.copyAssignments.sectionTime = copy_assignments
          .map(({timeData}) => (timeData));
      }
      if(sectionNumber == 5){
        let copy = { priority: this.priority }
        this.copyAssignments.sectionPriority = _.cloneDeep(copy);
      }
      if(sectionNumber == 6){
        let copy = { importance: this.importance }
        this.copyAssignments.sectionImportance = _.cloneDeep(copy);
      }
       if(sectionNumber == 'all'){
        let copyImportance = { importance: this.importance };
        let copyPriority = { priority: this.priority }
        this.copyAssignments.sectionImportance = _.cloneDeep(copyImportance);
        this.copyAssignments.sectionPriority = _.cloneDeep(copyPriority);
        this.copyAssignments.sectionTitle = _.cloneDeep(this.typeData);
        this.copyAssignments.sectionRole = copy_assignments 
          .map(({branches, selectedRole, selectedStation, selectedEmployee}) => ({branches, selectedRole: selectedRole ? selectedRole.id : selectedRole, selectedStation, selectedEmployee}));
        this.copyAssignments.sectionTime = copy_assignments
          .map(({timeData}) => (timeData));
      }
    },
    sendSingleData(data, item, method, saveInCopyIds = false){
      let selectedRole = item.selectedRole;
      let selRole = null;
      let selStation = null;
      const isPost = method === 'post';
      if(item.selectedRole && item.selectedRole.id){
        selRole = item.selectedRole.id;
        selStation = item.selectedRole.branch_station_id; 
        if (isPost && this.type.indexOf('notification')==-1) {
          data.role = selRole;
        }
      }
      if(~this.type.indexOf('notification')){
        if(!item.branches){
          return; 
        }
        selStation = item.selectedStation;
      }
      if(this.type.indexOf('notification')==-1 && this.type!='message'){
        if(!(item.branches && selStation && selRole)){
          return;
        }
      }else{
        if(this.type =='message' && !item.selectedEmployee){
          return;
        }
      }
      let url2 = this.makeURL(item.branches, selStation, isPost ? 'new' : item.id, selRole, item.selectedEmployee);
      this.$http[method](url2, data, {emulateJSON: true})
        .then(({ body }) => {
          if(isPost){
            if(saveInCopyIds){
              item.copyIDs.push(body.id);
            }else{
              this.$set(item, 'id' , body.id);
            }
          }
        })
        .catch(err => console.log('Error in sendData(): ', err))
    },
    clearFieldsData(){
      this.id = 0;
      this.branch = 0;
      this.description = {
        title: '',
        info: '',
        checklist: [],
        answers: []
      };
      this.assignments = [];
      this.timeData = [];
      this.priority = 1;
      this.importance = 1;
    },
    checkAssignmentsValid(){
      if(this.assignments.length){
        if(~this.type.indexOf('notification')){
          if (this.assignments[0].branches) {
            this.accessToSlides(3, 4, 5)
          }
        }else{
          const selectedRole = this.assignments[0].selectedRole,
            branches = this.assignments[0].branches
          if (selectedRole && branches) {
            this.accessToSlides(3, 4, 5)
          }
        }
      }
    },
  },

  components: {
    assignmentType: require('./AssignmentType'),
    taskDescription: require('./TaskDescription'),
    roles: require('./ToRoles'),
    assignmentTime: require('./Time'),
    priority: require('./Priority'),
    importance: require('./Importance'),
    employees: require('./ToEmployee')
  }
}
</script>
