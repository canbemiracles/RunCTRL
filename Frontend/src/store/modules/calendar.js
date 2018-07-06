import timeFunc from '../../components/Common/Mixins/timesFunctions';
import Vue from 'vue';
const calendar = {
    state: {
        typeModal: 'choose',
        currentTaskEdit: null,
        currentTimeGround: null,
        taskDetail: [],
        showPreloader: false,
        updateShifts: false,
        updateAssignments: false,
        startGetBranch: false,
        editModeTask: false
    },
    getters: {
        typeModal: ({ typeModal }) => (typeModal),
        currentTaskEdit: ({ currentTaskEdit }) => (currentTaskEdit),
        currentTimeGround: ({ currentTimeGround }) => (currentTimeGround),
    },
    mutations: {
        setTypeModal(state, data) {
            state.typeModal = data
        },
        resetTypeModal(state, data){
            state.typeModal = 'choose';
        },
        setCurrentTaskEdit(state, data) {
            state.currentTaskEdit = data
        },
        setCurrentTimeGround(state, data) {
            state.currentTimeGround = data
        },
        setTaskDetail(state, data){
            state.taskDetail = data;
        },
		deleteTempTask(state, ind){
			state.taskDetail[ind].splice(state.taskDetail[ind].length-1, 1);
        },
        addNewTempTask(state, data){
            state.taskDetail[data.ind].push(data.task);
        },
        setTaskDetailIndData(state, data){
            Vue.set(state.taskDetail, data.ind, data.data); 
        },
        setPreloaderState(state, value){
            state.showPreloader = value;
        },
        setUpdateShiftsStatus(state, value){
            state.updateShifts = value;
        },
        setUpdateAssignmentsStatus(state, value){
            state.updateAssignments = value;
        },
        setStartGetBranchStatus(state, value){
            state.startGetBranch = value;
        },
        setEditModeTask(state, value){
            state.editModeTask = value;
        },
    },
    actions: {
        resizeTaskFromInput({state}, taskObj){
            let minTime = state.currentTimeGround[0];
            let minMin = timeFunc.methods.timeToMinutes(minTime);
            let start_timeStr = taskObj.start_time;
            let end_timeStr = taskObj.end_time;
            let startMin = timeFunc.methods.timeToMinutes(start_timeStr);
			let endMin = timeFunc.methods.timeToMinutes(end_timeStr);
            let difMin = endMin - startMin;
            Vue.set(taskObj, 'styleObj' , {
                height: difMin + 'px',
                top: (startMin - minMin) + 'px',
            });
        },
        getTasksByStation({state, commit, rootGetters}, data){
            const { $http } = rootGetters;
            return $http.get(`api/v1/branches/${data.branch_id}/stations/${data.station_id}/get_assignments_and_notifications?date_start=${data.start}&date_end=${data.end}`)    
        },
        getNotificationsBranchMessage({state, commit, rootGetters}, data){
            const { $http } = rootGetters;
            return $http.get(`api/v1/branches/${data.branch_id}/get_assignments_and_notifications?date_start=${data.start}&date_end=${data.end}`)    
        }, 
    }
}

export default calendar;