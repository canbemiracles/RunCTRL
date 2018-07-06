<template>
    <div class="week-wrap">
        <div class="time-ground">
    		<ul class="list-time-ground">
    			<li v-for="(time, index) in pageTimeGround" :key="index" class="list-time-ground__item">
    				<span class="time">{{time | formatTimeGroud(this) }}</span>
					<ul v-if="index!=(pageTimeGround.length-1)" class="minutes-line-list">
    					<li class="minutes-line__item" v-for="n in 4" :key="n"></li>
    				</ul>	
    			</li>
    		</ul>
			<div class="time-seeker" id="time-seeker"></div>
			<preloader :show="showPreloader"></preloader>
    	</div>
    	<div class="task-ground">
				<ul class="task-ground-list">
					<li v-for="(week, index) in weekGround" :key="index" class="task-list">
						<div class="dayWeek" :class="{'current-day': week.day==currentDay.day}">{{week.name}} - {{ weekDayFormated(week) }}</div>	
						<transition-group name="fade" tag="ul" v-show="!showPreloader" 
							class="schedule-column"
							:class="{'current-day-column': week.day==currentDay.day, disabled: week.disabled}"
							@mousedown.native="createTask($event, week)"
							>
							<template v-if="taskDetail.length">	
								<task v-for="(detail, ind) in taskDetail[index]" 
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
import {mapActions, mapState, mapGetters, mapMutations} from 'vuex';
export default {
    mixins: [calendar],
    props: {
		shifts: Array,
		currentBranch: Number,
		currentStation: Number,
		disabledDays: Array,
    },
    data: function (){
        return {
            firstDayWeek: null,
			weekGround:[],
			rolesList:[],
			daysAssignmentsList: [],
			detail: null,
        }
	},
	computed: {
		...mapState({
			weekStart: state => state.shifts.day_start,
			taskDetail: state => state.calendar.taskDetail,
			showPreloader: state => state.calendar.showPreloader,
			updateShifts: state => state.calendar.updateShifts,
			updateData: state => state.calendar.updateAssignments,
			startGetBranch: state => state.calendar.startGetBranch,
		}),
		...mapGetters(['currentTimeGround', 'dateFormat'])
	},
	created(){
		this.fetchWeekStart();
	},
	watch: {
		updateData:{
			handler(val){
				if(val){
					this.getTasks();
				}
			},
		},
		updateShifts:{
			handler(val){
				if(val){
					console.log('updateShifts');
					this.daysAssignmentsList=[];
					this.setTaskDetail([]);
					this.initWeekGround();
					this.initShiftsList();
					this.getTimeGround();
					this.getTasks();
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
    mounted(){
		this.setTaskDetail([]);
        var unwatch = this.$watch('weekStart', (value)=>{
			if(value){
				this.unwatchShifts = this.$watch('shifts', (shift)=>{
					if(shift.length){
						this.daysAssignmentsList=[];
						this.initWeekGround();
						this.initShiftsList();
						this.getTimeGround();
						if(this.currentStation){
							this.getTasks();
							this.startGetBranchFunction(false);
						}
					}
				}, {immediate: true, deep: true});
            }
        },
        {
            immediate: true, 
        });
		
		if(this.currentShiftsList.length){
				this.unwatchShifts();
		}else{
			let shiftsWatchInterval = setInterval(()=>{
				if(this.currentShiftsList.length && this.unwatchShifts){
					this.unwatchShifts();
					clearInterval(shiftsWatchInterval);
				}
			}, 20000);
		}
    },
    methods:{
		...mapActions(['fetchWeekStart', 'getTasksByStation', 'getNotificationsBranchMessage']),
		...mapMutations(['setTaskDetail', 'setPreloaderState', 'setUpdateShiftsStatus', 'setUpdateAssignmentsStatus', 'setStartGetBranchStatus']),
        initTasksList(){
			let taskDetail = [];
			for (let i = 0; i < 7; i++) {
				this.daysAssignmentsList.forEach(element=>{
					if(element.day==this.firstDayWeek.clone().add(i, 'days').format('MM/DD/YYYY')){
						let copy = element.assignments.map(a => ({...a}));
						taskDetail[i]= copy;
					}
				})
			}
			this.setTaskDetail(taskDetail);
        },
        initWeekGround(){
			this.weekGround=[];
			this.getCurrentDay();
			let weekSatrtOn = this.weekStart; //get from backend
			let weekList = [
				"Monday", 
				"Tuesday", 
				"Wednesday", 
				"Thursday", 
				"Friday", 
				"Saturday", 
				"Sunday"
			];
			
			let diff = this.currentDay.dayWeek - weekSatrtOn; 
			if(diff >=0){
				this.firstDayWeek= moment().subtract(diff, 'days');
			}else{
				this.firstDayWeek= moment().subtract( 7 + diff, 'days');
			}

		
			// получаем массив с днями недели
			let start = weekSatrtOn - 1;
			let weekDays = [];
			for (let i = 0; i < 7; i++) {
				if (start > 6) {start = 0;}
				let dayObj = this.firstDayWeek.clone().add(i, 'days').toDate();
				let w_day =dayObj.getDate();
				let w_month = dayObj.getMonth() + 1;
				let w_year = dayObj.getFullYear();
				let disbledIndex = start+1 == 7 ? 0 : start+1;
				let disabled = this.disabledDays.indexOf(disbledIndex)!=-1;
				this.weekGround.push({
					name: weekList[start],
					day: w_day,
					month: w_month,
					year: w_year,
					disabled,
				})
				start++;
			}
        },
        getTasks(){
			let startDayWeek = this.firstDayWeek.format('YYYY-MM-DD');
			let endDayWeek = this.firstDayWeek.clone().add(6, 'days').format('YYYY-MM-DD');
			let start = startDayWeek +' '+ this.currentTimeGround[0];
			let end = endDayWeek +' '+ this.currentTimeGround[1];
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
					this.setPreloaderState(false);
					this.initSheduleHeight();
					if(this.updateData){
						this.setUpdateAssignmentsStatus(false);
						if(this.updateShifts){
							this.setUpdateShiftsStatus(false);
						}
					}
				});
			}
		},
		weekDayFormated(weekday){
			let day = moment({ y: weekday.year, M: weekday.month, d: weekday.day });
			if(this.dateFormat){
				let fullDate = day.format(this.dateFormat);
				let separator = fullDate.match(/\D/);
				let monthDayStr =  this.dateFormat.replace(RegExp(`${separator}?y+${separator}?`, 'ig'), '');
				return day.format(monthDayStr);
			}else{
				return `${weekday.month}/${weekday.day}`
			}
		},
		initShiftsList(){
			let shiftsList = this.shifts.map(a=>({...a}));
			shiftsList.forEach(shift=>{
				shift.time_open = moment.utc(shift.time_open_custom).format('HH:mm:ss');
				shift.time_close = moment.utc(shift.time_close_custom).format('HH:mm:ss');
			});
			this.currentShiftsList = shiftsList;
		},
		openTask(task){
			this.detail = task;
			console.log(this.$refs.taskEdit);
			this.$refs.taskEdit.show = true;
		}
    },
	components: {
		task: require('./Task'),
		preloader: require('../../Common/Preloader'),
		taskEdit: require('./TaskModal')
	},
	
}
</script>
<style lang="scss" scoped src="./schedule.scss"></style>

