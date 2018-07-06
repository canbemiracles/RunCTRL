<template>

</template>
<script>
import calendar from './calendarMixin';
import {mapActions, mapGetters, mapState, mapMutations} from 'vuex';
export default {
    mixins: [calendar],
    props:{
		shifts: Array,
		currentBranch: Number,
		currentStation: Number,
		disabledDays: Array,
    },
    data(){
		return {
			daysAssignmentsList: [],
			dayShiftsList: [],
			rolesList: [],
		}
	},
	watch:{
		
	},
	computed:{
		...mapGetters(['currentTimeGround']),
		...mapState({
			taskDetail: state => state.calendar.taskDetail,
			showPreloader: state => state.calendar.showPreloader,
			updateShifts: state => state.calendar.updateShifts,
			updateData: state => state.calendar.updateAssignments,
			startGetBranch: state => state.calendar.startGetBranch,
		})
	},
	mounted(){
    },
}
</script>
<style lang='scss' src='./schedule.scss' scoped></style>