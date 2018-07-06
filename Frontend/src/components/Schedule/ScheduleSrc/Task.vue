<template>
    <li class="task-list-item"
		:class="{'cancel-task' : detail.state==2}" 
		:style="detail.styleObj">
        <div class="task-inner"  @click.stop="openTaskDetail">
            <span class="color-line" v-color:bg="detail.role.color" v-if="detail.state!=2 && detail.role"></span>
            <div class="task-state" v-if="detail.state==0">
                <state-ready></state-ready>
            </div>
            <div class="task-state" v-if="detail.state==1">
                <state-pending></state-pending>
            </div>
            <div class="task-state" v-if="detail.state==2">
                <state-cancel></state-cancel>
            </div>
            <div class="task-content">
                <h3 class="task-list-item__title">{{detail.title}}</h3>
                <p v-if="detail.role" v-color="detail.role.color" class="task-list-item__post">{{detail.role.role}}</p>
            </div>
            <div class="star" v-if="detail.priority"><svg class="star-icon" :class="checkTypePriority(detail.priority)" width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg></div>
            <div class="circle" v-if="detail.importance" :class="checkTypeImportance(detail.importance)"></div>
            <div class="drag-resize" ref="dragResize" @mousedown.stop="resizeTask($event, detail)"></div>
        </div>
        
    </li>
</template>
<script>
export default {
    props:{
        detail: Object
    },
    methods:{
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
        resizeTask(e, taskObj){
            let columnElement = document.elementFromPoint(e.clientX, e.clientY);
			if(!$(columnElement).hasClass('schedule-column')){
				columnElement = $(columnElement).closest('.schedule-column')[0];
			}
            let showTaskFunc = (e)=>{
                e.stopPropagation();
                let targetCol = e.currentTarget.getBoundingClientRect();
                var offY  = e.clientY - targetCol.top - parseInt(taskObj.styleObj.top);
                this.$set(taskObj.styleObj, 'height', offY + 'px');
			}
            columnElement.addEventListener('mousemove', showTaskFunc);
			document.addEventListener('mouseup', ()=>{
                if(columnElement){
                   columnElement.removeEventListener('mousemove', showTaskFunc); 
                }
			});
        },
        openTaskDetail(){
            console.log('open task');
            this.$parent.$parent.clearIntervGetBranch();
            this.$emit('openTaskModal', this.detail);
        } 
    },
    components:{
        stateReady: require('../../StateTask/ready'),
		statePending: require('../../StateTask/pending'),
        stateCancel: require('../../StateTask/cancel'),
        taskEdit: require('./TaskModal')
    }
};
</script>
<style lang="scss" src="./schedule.scss" scoped></style>
<style lang="scss" scoped>
.drag-resize{
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 50%;
    max-height: 16px;
    cursor: ns-resize;
    display: flex;
}
</style>



