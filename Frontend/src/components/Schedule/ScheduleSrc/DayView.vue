<template>
    <div class="day-wrap">
        <div class="time-ground">
    		<ul class="list-time-ground">
    			<li v-for="(time, index) in pageTimeGround" :key="index" class="list-time-ground__item">
    				<span class="time">{{time | formatTimeGroud(this)}}</span>
					<ul v-if="index!=(pageTimeGround.length-1)" class="minutes-line-list">
    					<li class="minutes-line__item" v-for="n in 4" :key="n"></li>
    				</ul>	
    			</li>
    		</ul>
			<ul v-if="currentShiftsList.length" class="shifts-layer">
				<li class="shift-item" v-for="shift in currentShiftsList" :key="shift.shift_id" :style="shift.styleObj"></li>
			</ul>
			<div class="time-seeker" id="time-seeker"></div>
			<preloader key="preloader" :show="showPreloader"></preloader>
    	</div>
    	<div class="task-ground">
				<ul class="task-ground-list">
					<li class="task-list-day">
						<div class="dayWeek current-day text-center justify-content-center" v-if="day">{{day.name}} - {{ currentDayFormated }}</div>	
						<transition-group name="fade" tag="ul" class="schedule-column" 
						:class="{disabled: day && day.disabled}"
						@mousedown.native="createTask($event, day)"
						>
							<template v-if="taskDetail">	
								<task v-for="(detail, ind) in taskDetail[0]" 
								:key="ind"
								:detail="detail"
								@openTaskModal="openTask"
								></task>
							</template>
						</transition-group>
					</li>
				</ul>
    	</div>
		<transition name="modal">
			<task-edit :detail="detail" :branchId="currentBranch" :stationId="currentStation" ref="taskEdit"></task-edit>
		</transition>
    </div>
</template>
<script>
import calendar from './calendarMixin';
import pallete from '../../../stylesheets'
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
			day: null,
			daysAssignmentsList: [],
			dayShiftsList: [],
			rolesList: [],
			detail: null,
		}
	},
	watch:{
		updateShifts:{
			handler(val){
				if(val){
					console.log('updateShifts');
					this.daysAssignmentsList=[];
					this.initCurrentDay();
					this.setTaskDetail([]);
					this.initShiftsList();
					this.getTimeGround();
					if(this.currentStation){
						this.getTasks(true);
					}
				}
			},
		},
		updateData:{
			handler(val){
				if(val){
					let updateshifts = false;
					if(!this.currentShiftsList.length){
						updateshifts = true;
						this.initShiftsList();
						this.getTimeGround();
					}
					console.log('getTasks call');
					this.getTasks(updateshifts);
					if(!this.intervalFuncGetBranch){
						this.startGetBranchFunction(false);
					}
				}
			},
		},
		startGetBranch(val){
			if(val){
				this.startGetBranchFunction();
				this.setStartGetBranchStatus(false);
			}
		},
	},
	computed:{
		...mapGetters(['currentTimeGround', 'dateFormat']),
		...mapState({
			taskDetail: state => state.calendar.taskDetail,
			showPreloader: state => state.calendar.showPreloader,
			updateShifts: state => state.calendar.updateShifts,
			updateData: state => state.calendar.updateAssignments,
			startGetBranch: state => state.calendar.startGetBranch,
		}),
		currentDayFormated(){
			let day = moment({ y: this.day.year, M: this.day.month, d: this.day.day });
			if(this.dateFormat){
				let fullDate = day.format(this.dateFormat);
				let separator = fullDate.match(/\D/);
				let monthDayStr =  this.dateFormat.replace(RegExp(`${separator}?y+${separator}?`, 'ig'), '');
				return day.format(monthDayStr);
			}else{
				return `${this.day.month}/${this.day.day}`
			}
		}
	},
	mounted(){
		this.setTaskDetail([]);
		this.setPreloaderState(true);
		this.initCurrentDay();
		this.initShiftsList();
		this.getTimeGround();
		this.getTasks(true);
		this.startGetBranchFunction(false);
    },
    methods: {
		...mapActions(['getTasksByStation', 'getNotificationsBranchMessage']),
		...mapMutations(['setPreloaderState', 'setUpdateShiftsStatus', 'setUpdateAssignmentsStatus', 'setStartGetBranchStatus', 'setTaskDetail']),
        getTasks(updateShifts=false){
            if(!this.currentDay){
				this.getCurrentDay();
			}
			let currDate = moment().format('YYYY-MM-DD');
			let start = currDate +' '+ this.currentTimeGround[0];
			let end = currDate +' '+ this.currentTimeGround[1];
			if(this.currentBranch && this.currentStation){
				let data={
					branch_id: this.currentBranch,
					station_id: this.currentStation, 
					start,
					end 
				}
				let tasksAll = this.getTasksByStation(data).then(res=>{
					this.rolesList = [...res.body.roles, ...[{ notifications_station: [...res.body.notifications_station] }]];
				});
				let notifyList = [];
				let notify = this.getNotificationsBranchMessage(data).then(res=>{
					notifyList = [...[{ notifications_branch: [...res.body.notifications_branch] }], ...[{ notifications_message: [...res.body.notifications_message] }]];
				});
				Promise.all([tasksAll, notify]).then(res=>{
					this.rolesList = [...this.rolesList, ...notifyList];
					this.prepeareAssignmentsList();
					this.devideAssignmentsForDays();
					this.getStateAssignment();
					this.initTasksList();
					this.initTaskPositions();
					if(updateShifts){
						this.showShifts();	
					}
					this.initSheduleHeight();
					this.setPreloaderState(false);
					if(this.updateData){
						this.setUpdateAssignmentsStatus(false);
					}
					if(this.updateShifts){
						this.setUpdateShiftsStatus(false);
					}
				});
			}
		},
		initTasksList(){
			let taskDetail = []; 
			if(this.daysAssignmentsList.length){
				taskDetail[0] = this.daysAssignmentsList[0].assignments;
			}
			this.setTaskDetail(taskDetail);
		},
		initShiftsList(){
			console.log('init current shift');
			if(!this.currentDay){
				this.getCurrentDay();
			}
			this.dayShiftsList = [];
			this.shifts.forEach(shift =>{
				if(shift.shift_day.day == this.currentDay.dayWeek){
					this.dayShiftsList.push(shift);
				}
			});
			this.currentShiftsList = this.dayShiftsList.map(a=> ({...a}));
			this.currentShiftsList.forEach(shift=>{
				shift.time_open = moment.utc(shift.time_open_custom).format('HH:mm:ss');

				let timeOpen = moment.utc(shift.time_open_custom).format('YYYY-MM-DD HH:mm:ss');
				let timeclose = moment.utc(shift.time_close_custom).format('YYYY-MM-DD HH:mm:ss');

				let close_time = moment(timeclose, 'YYYY-MM-DD HH:mm:ss');
				let open_time = moment(timeOpen, 'YYYY-MM-DD HH:mm:ss');
				let nextDay = open_time.clone().add(1, 'day').startOf('date');
				if(close_time.isAfter(nextDay)){
					close_time = '24:00:00';
				}else{
					close_time = close_time.format('HH:mm:ss');
				}
				shift.time_close = close_time;
			});
		},
		showShifts(){
			let colors = Object.values(pallete);
			if(!this.currentDay){
				this.getCurrentDay();
			}
			let copyColors = colors.slice();
			this.currentShiftsList.forEach(shift=>{
				let minTime = this.pageTimeGround[0];
				let minMin = this.timeToMinutes(minTime);
				let start_timeStr = shift.time_open;
				let end_timeStr = shift.time_close;
				let startMin = this.timeToMinutes(start_timeStr);
				let endMin = this.timeToMinutes(end_timeStr);
				let difMin = endMin - startMin;
				console.log(difMin);
				let randColor = copyColors[~~(Math.random() * copyColors.length)];
				shift.styleObj = {
					height: difMin + 'px',
					top: (startMin - minMin) + 'px',
					backgroundColor: randColor
				}
				copyColors.splice(copyColors.indexOf(randColor), 1);
			});
			
		},
		initCurrentDay(){
			let day = moment();
			let dayWeek = day.day();
			let disbledIndex = dayWeek == 0 ? 7 : dayWeek;
			this.day={
				name: day.format('dddd'),
				day: day.format('D'),
				month: day.format('M'),
				year: day.format('YYYY'),
				disabled: this.disabledDays.indexOf(disbledIndex)!=-1
			}
		},
		openTask(task){
			this.detail = task;
			console.log(this.$refs.taskEdit);
			this.$refs.taskEdit.show = true;
		}
	},
	beforeDestroy(){
		if(this.unwatchShifts){
			this.unwatchShifts();
		}
	},
	components: {
		task: require('./Task'),
		preloader: require('../../Common/Preloader'),
		taskEdit: require('./TaskModal')
	},
}
</script>
<style lang='scss' src='./schedule.scss' scoped></style>