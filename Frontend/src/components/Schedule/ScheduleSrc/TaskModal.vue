<template>
    
		<b-modal class="task-modal"
        v-model="show" 
		:hide-header="true"
        :lazy="true"
        centered
		:hide-footer="true"
		@hide="closeModal"
		>		
            <div class="task-list-item" v-if="detail">
                <span class="color-line" v-if="detail.role" v-color:bg="detail.role.color"></span>
                <div class="task-content">
                    <h3 class="task-list-item__title">{{detail.title}}</h3>
                    <p v-if="detail.role" v-color="detail.role.color" class="task-list-item__post">{{detail.role.role}}</p>
                    <div class="time-row">
                        {{ day }} {{ timeRange }}
                    </div>   
                </div>
                <div class="bottom-row">
                    <div class="btn-group">
                        <div class="delete-icon" @click="deleteAssignment">
                            <svg class="icon" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M704 1376v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm-544-992h448l-48-117q-7-9-17-11h-317q-10 2-17 11zm928 32v64q0 14-9 23t-23 9h-96v948q0 83-47 143.5t-113 60.5h-832q-66 0-113-58.5t-47-141.5v-952h-96q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h309l70-167q15-37 54-63t79-26h320q40 0 79 26t54 63l70 167h309q14 0 23 9t9 23z"/></svg>
                        </div>
                        <div class="edit-icon" @click="editAssignment">
                            <svg class="icon" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M491 1536l91-91-235-235-91 91v107h128v128h107zm523-928q0-22-22-22-10 0-17 7l-542 542q-7 7-7 17 0 22 22 22 10 0 17-7l542-542q7-7 7-17zm-54-192l416 416-832 832h-416v-416zm683 96q0 53-37 90l-166 166-416-416 166-165q36-38 90-38 53 0 91 38l235 234q37 39 37 91z"/></svg>
                        </div>
                    </div>
                    <div class="priority-importance-wrap">
                        <div class="star" v-if="detail.priority"><svg class="star-icon" :class="checkTypePriority(detail.priority)" width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg></div>
                        <div class="circle" v-if="detail.importance" :class="checkTypeImportance(detail.importance)"></div>
                    </div>
                </div>
	        </div>
		</b-modal>
</template>
<script>
import { mapGetters, mapMutations } from 'vuex';
export default {
    props:{
        detail: Object,
        branchId: Number,
        stationId: Number
    },
    data(){
        return{
            show: false,
        }
    },
    computed:{
        ...mapGetters(['timeFormat', 'dateFormat']),
        day(){
            let defaultDateFormat = 'MMM D, YYYY';
            return moment.utc(this.detail.start_time_t).format(this.dateFormat ? this.dateFormat : defaultDateFormat);   
        },
        formatTime(){
            return this.timeFormat ? this.timeFormat : 'hh:mm A'
        },
        timeRange(){
            let start = moment.utc(this.detail.start_time_t).format(this.formatTime);
            let end = moment.utc(this.detail.end_time_t).format(this.formatTime);
            return `${start} to ${end}`
        },
    },
    methods:{
        ...mapMutations(['setPreloaderState', 'setCurrentTaskEdit', 'setTypeModal', 'setEditModeTask']),
        closeModal(){
            this.show=false;
        },
        checkTypePriority(priority) {
            switch (priority) {
                case 1:
                    return 'manager'
                case 2:
                    return 'supervisor'
                case 3:
                    return 'owner'
            }
		},
		checkTypeImportance(importance) {
            switch (importance) {
                case 1:
                    return 'low'
                case 2:
                    return 'medium'
                case 3:
                    return 'high'
            }
        },
        deleteAssignment(){
            this.show = false;
            this.setPreloaderState(true);
            let roleId = this.detail.role && this.detail.role.id;
            let url = this.makeURL(this.detail.id, ~this.detail.type.indexOf('notification') && roleId);
            return this.$http.delete(url)
                .then(()=>{
                    this.$parent.startGetBranchFunction();
                    console.log(this.$parent);
                })
                .catch(err => console.log('Error in deleteTask(): ', err))
        },
        
        editAssignment(){
            this.show = false;
            this.setEditModeTask(true);
            this.setTypeModal(this.detail.type);
            let task={
                id: this.detail.id,
                branch: this.branchId,
                station: this.stationId,
                title: this.detail.title,
                role: this.detail.role,
                description: {
                    title: '',
                    info: this.detail.description ? this.detail.description : '',
                    checklist: this.detail.tasks ? this.detail.tasks.map(({task})=>({task})) : [],
                    answers: this.detail.possible_answers ? this.detail.possible_answers.map(({answer})=>({answer})) : []
                },
                styleObj: this.detail.styleObj,
                start_time: moment.utc(this.detail.start_time_t).format('HH:mm:ss'),
                end_time: moment.utc(this.detail.end_time_t).format('HH:mm:ss'),
                date: moment.utc(this.detail.start_time_t).startOf('day').toString(),
                snooze_max: this.detail.snooze_max,
                snooze_time: this.detail.snooze_time,
                repeat: !!this.detail.repeat_unit,
                repeat_unit: this.detail.repeat_unit,
                repeat_subunit: this.detail.repeat_subunit,
                repeat_months: this.detail.repeat_months ? this.detail.repeat_months : [],
                repeat_week: this.detail.repeat_week,
                repeat_week_days: this.detail.repeat_week_days ? this.detail.repeat_week_days : [],
                repeat_month_days:this.detail.repeat_month_days ? this.detail.repeat_month_days : [], 
                priority: this.detail.priority,
                importance: this.detail.importance
			}
            this.setCurrentTaskEdit(task);
            this.$root.$emit('bv::show::modal','taskModal');
        },
        makeURL(taskId, roleId, emplId) {
            let brId = this.branchId;
            let stId =this.stationId;
            if(~this.detail.type.indexOf('notification')){
                if(this.detail.type=='notification_branch'){
                    return `api/v1/branches/${brId}/device_notifications/${taskId}`
                }else if(this.detail.type=='notification_station'){
                    return `api/v1/branches/${brId}/stations/${stId}/device_notifications/${taskId}`
                }else{
                    return `api/v1/branches/${brId}/stations/${stId}/roles/${roleId}/device_notifications/${taskId}`
                }
            }else if(this.detail.type=='message'){
                    return `api/v1/branches/${brId}/employees/${emplId}/device_notifications/${taskId}`
            }else{
                const typeUrl = {
                    standard: 'tasks',
                    question_yes_no: 'questions/yes_no',
                    question_answer_list: 'questions/answer_lists',
                    question_numeric: 'questions/numeric',
                    question_range: 'questions/range',
                    checklist: 'checklists',
                    question_text: 'questions/text'
                }[this.detail.type];
                return `api/v1/branches/${brId}/stations/${stId}/assignments/${typeUrl}/${taskId}` 
            } 
        },
    }

}
</script>
<style lang="scss" src="./schedule.scss" scoped></style>
<style lang='scss' scoped>
.task-list-item{
    position: static;
    background-color: #fff;
    width: 100%;
    height: 100%;
    padding: 20px 15px 15px 20px;
    display: flex;
    flex-direction: column;
}
.task-list-item__title{
    font-size: 18px;
}
.task-list-item__post{
    font-size: 14px;
}
.bottom-row{
    display: flex;
    margin-top: auto;
}
.priority-importance-wrap{
    margin-left: auto;
    display: flex;
    align-items: center;
}
.delete-icon, .edit-icon{
   width: 30px;
   height: 30px;
   border-radius: 50%;
   background-color: #f1f5f6;
   display: flex;
   align-items: center;
   justify-content: center;
   cursor: pointer;
   .icon{
       fill: #8b9da6; 
   }
   &:hover{
      background-color: darken(#e9eff2, 10);
      .icon{
          fill: #fff;
      } 
   }  
}
.edit-icon{
    margin-left: 10px;
    .icon{
        width: 14px;
        height: 14px;
    }
}
.delete-icon .icon{
    width: 13px;
    height: 16px;
}
.star{
    position: static;
    width: 22px;
    height: 22px;
}
.circle{
    position: static;
    width: 20px;
    height: 20px;
}
.star{
    margin-right: 10px;
}
</style>
<style lang="scss">
.task-modal .modal-backdrop.show{
    opacity: 0.2;
}
.task-modal{
    .modal-content{
        border: none;
        width: 100%;
        max-width: 355px;
        height: 185px;
        background: none;
        margin: auto;
    },
    .modal-body{
        padding: 0;
        width: 100%;
        height: 100%;
    }
}  
</style>

