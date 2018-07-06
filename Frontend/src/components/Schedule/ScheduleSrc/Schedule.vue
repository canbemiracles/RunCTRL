<template>
	<div class="task-agenda">
		<div class="section-header" v-if="!branchId">
			<div class="section-title d-flex justify-content-between">
				<h2 class="heading-2">Tasks</h2>
				<div class="btn-group">
					<a href="#" class="user-btn-white btn-circle btn-circle-big">
						<svg class="user-icon" width="12" height="14" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1329 784q47 14 89.5 38t89 73 79.5 115.5 55 172 22 236.5q0 154-100 263.5t-241 109.5h-854q-141 0-241-109.5t-100-263.5q0-131 22-236.5t55-172 79.5-115.5 89-73 89.5-38q-79-125-79-272 0-104 40.5-198.5t109.5-163.5 163.5-109.5 198.5-40.5 198.5 40.5 163.5 109.5 109.5 163.5 40.5 198.5q0 147-79 272zm-433-656q-159 0-271.5 112.5t-112.5 271.5 112.5 271.5 271.5 112.5 271.5-112.5 112.5-271.5-112.5-271.5-271.5-112.5zm427 1536q88 0 150.5-71.5t62.5-173.5q0-239-78.5-377t-225.5-145q-145 127-336 127t-336-127q-147 7-225.5 145t-78.5 377q0 102 62.5 173.5t150.5 71.5h854z"/></svg>
					</a>
					<router-link :to="{ name: 'createTaskFlow'}" class="add-btn btn-circle btn-circle-big">+</router-link>
				</div>
          	</div>
		</div>
		<div class="task-page-container">
			<div class="row no-gutters agenda-header">
				<div class="left-block d-flex" v-if="branchId">
					<h2 class="heading-2">View Tasks Agenda</h2>
					<a href="#" class="user-btn btn-circle">
					<svg class="user-icon" width="12" height="14" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1329 784q47 14 89.5 38t89 73 79.5 115.5 55 172 22 236.5q0 154-100 263.5t-241 109.5h-854q-141 0-241-109.5t-100-263.5q0-131 22-236.5t55-172 79.5-115.5 89-73 89.5-38q-79-125-79-272 0-104 40.5-198.5t109.5-163.5 163.5-109.5 198.5-40.5 198.5 40.5 163.5 109.5 109.5 163.5 40.5 198.5q0 147-79 272zm-433-656q-159 0-271.5 112.5t-112.5 271.5 112.5 271.5 271.5 112.5 271.5-112.5 112.5-271.5-112.5-271.5-271.5-112.5zm427 1536q88 0 150.5-71.5t62.5-173.5q0-239-78.5-377t-225.5-145q-145 127-336 127t-336-127q-147 7-225.5 145t-78.5 377q0 102 62.5 173.5t150.5 71.5h854z"/></svg>
					</a>
					<router-link :to="{ name: 'createTaskFlow'}" class="add-btn btn-circle">+</router-link>
				</div>
				<div class="left-block col-2 d-flex" v-else>
					<div class="branch-select-single" v-if="branchesList && branchesList.length==1"><div class="branch-icon"></div> <span>{{ branchesList[0].text }}</span> </div>
					<app-select v-if="branchesList && branchesList.length>1"
					:options="branchesList"
					selectClass="branch-select"
					dropDownClass="branch-select-dropdown"
					@changeSelection="selectBranch">
						<div class="branch-icon" slot="icon" slot-scope="icon"></div>
					</app-select>
				</div>
				<div class="right-block d-flex">
					<b-tabs pills class="stations" :class="{'stations-align-left' : !branchId, 'stations-align-right' : branchId}" v-model="stationCurrentIndex" v-if="stationsList">
						<b-tab v-for="station in stationsList" :key="station.id" :title="station.name"></b-tab>
					</b-tabs>
					<div class="datepick" v-if="type=='full'">
						<div id="reportrange" class="datepick-input">
							<svg class="calendar-icon" width="14" height="19" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M192 1664h288v-288h-288v288zm352 0h320v-288h-320v288zm-352-352h288v-320h-288v320zm352 0h320v-320h-320v320zm-352-384h288v-288h-288v288zm736 736h320v-288h-320v288zm-384-736h320v-288h-320v288zm768 736h288v-288h-288v288zm-384-352h320v-320h-320v320zm-352-864v-288q0-13-9.5-22.5t-22.5-9.5h-64q-13 0-22.5 9.5t-9.5 22.5v288q0 13 9.5 22.5t22.5 9.5h64q13 0 22.5-9.5t9.5-22.5zm736 864h288v-320h-288v320zm-384-384h320v-288h-320v288zm384 0h288v-288h-288v288zm32-480v-288q0-13-9.5-22.5t-22.5-9.5h-64q-13 0-22.5 9.5t-9.5 22.5v288q0 13 9.5 22.5t22.5 9.5h64q13 0 22.5-9.5t9.5-22.5zm384-64v1280q0 52-38 90t-90 38h-1408q-52 0-90-38t-38-90v-1280q0-52 38-90t90-38h128v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h384v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h128q52 0 90 38t38 90z"/></svg>
							<span class="caret"></span>
						</div>
					</div>
					<div class="filter">
					<b-tabs pills class="time-filter" v-model="currentViewIndex">
						<b-tab :title="view" v-for="(view, ind) in calendarView" :key="ind" ></b-tab>
					</b-tabs>
					</div>
				</div>
			</div>
			<div class="schedule-wrap">
				<div class="schedule" id="schedule">
					<transition name="fade" mode="out-in">
						<component :is="currentView"
							:daysAssignmenstList="daysAssignmentsList"
							:disabledDays="disabledDays"
							:currentBranch=" branchId || selectedBranchId"
							:currentStation="stationsList.length && stationsList[stationCurrentIndex].id"
							:shifts="shifts">
						</component>
					</transition>
				</div>
				<div class="view-all-wrap d-flex">
					<router-link :to="{name: 'taskCalendar'}" class="view-all" v-if="branchId">View All Agenda</router-link>
				</div>
				<modal-task :task="currentTaskEdit"
				:disabledDays="disabledDays"></modal-task>
			</div>
		</div>
    </div>
</template>

<script>
import calendar from './calendarMixin';
import daterangepicker from "bootstrap-daterangepicker";
import {mapActions, mapGetters, mapState, mapMutations} from 'vuex';

export default {
	name: 'Schedule',
	mixins: [calendar],
	props: {
		branchId: Number,
		type: String
	},
	data() {
		return {
			stationCurrentIndex: 0,
			calendarView: ['Day', 'Week', this.full ? 'Month' : 'Agenda'],
			currentViewIndex: 0 ,
			daysAssignmentsList: [],
			stationsList: [],
			selectedBranchId: null,
			disabledDays: [],
			stationUpdate: false,
			deleteTempTask: null,
		}
	},
	components:{
		weekView: require('./WeekView'),
		dayView: require('./DayView'),
		monthView: require('./MonthView'),
		appSelect: require('../../Common/Select'),
		modalTask: require('./Modal'),
		
	},
	watch:{
		stationCurrentIndex: {
			handler: function(value){
				this.setPreloaderState(true);
				this.setUpdateAssignmentsStatus(true); //get assignments for calendar
			}
		},
		currentViewIndex(index){
			if(!this.full && index==2){
				this.$router.push({name: 'taskCalendar'});
			}else{
				console.log('currentViewIndex change');
				this.setTaskDetail([]);
				this.setPreloaderState(true);
				this.setUpdateAssignmentsStatus(true);	
			}
		},
		selectedBranchId(value){
			this.setPreloaderState(true);
			this.getBranchData();
			this.getBranchShifts(value).then(()=>{
				console.log('updateScheduleShidts');
				this.getDisabledWeekDays();
				this.setUpdateShiftsStatus(true);
			});
		},
		branchId:{
			handler(id){
				if(id){
					this.getBranchShifts(id).then(()=>{
						this.getDisabledWeekDays();
					});
				}
			},
			immediate: true
		},
  	},
	computed:{
		...mapGetters(['branches', 'currentTaskEdit', 'shifts', 'dateFormat']),
		...mapState({
			showPreloader: state => state.calendar.showPreloader,
		}),
		currentView(){
			 switch (this.currentViewIndex) {
                case 0:
					return 'day-view'
                case 1:
                    return 'week-view'
                case 2:
                    return 'month-view'
            }
		},
		branchesList(){
			if(this.branches.length){
				let list=[];
				this.branches.forEach((element)=>{
					list.push({
						value: element.id,
						text: element.geographical_area ? element.geographical_area.street_address : ''
					})
				});
				return list;
			}
		},
		
	},
	created(){
		this.getBranchData();
	},
	mounted(){
		if(this.type=='full'){
			this.fetchBranches().then(()=>{
				this.selectedBranchId = this.branches[0].id;
				this.getBranchData();
			});
			var start = moment().subtract(29, 'days');
			var end = moment();
			let vm = this;
			function cb(start, end) {
				$('#reportrange span').html(vm.dayRangeFormated(start, end));
			}

			$('#reportrange').daterangepicker({
				startDate: start,
				endDate: end,
				"opens": "left",
				ranges: {
				'Today': [moment(), moment()],
				'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				'This Month': [moment().startOf('month'), moment().endOf('month')],
				'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				}
			}, cb);

			cb(start, end);
		}else{
			this.fetchBranches();
		}
	},
	methods:{
		...mapActions(['fetchBranchById', 'fetchBranches', 'getBranchShifts']),
		...mapMutations(['setPreloaderState', 'setTaskDetail', 'setUpdateShiftsStatus', 'setUpdateAssignmentsStatus']),
		getBranchData(){
			if(this.branchId || this.selectedBranchId){
				this.fetchBranchById(this.branchId || this.selectedBranchId).then(res=>{
					this.stationsList = res.stations;
					this.setUpdateAssignmentsStatus(true);
				});
			}
		},
		dayRangeFormated(start, end){
			let start_f, end_f;
			if(this.dateFormat){
				let fullDate = start.format(this.dateFormat);
				let separator = fullDate.match(/\D/);
				let monthDayStr =  this.dateFormat.replace(RegExp(`${separator}?y+${separator}?`, 'ig'), '');
				start_f =  start.format(monthDayStr);
				end_f =  end.format(this.dateFormat);
			}else{
				start_f =  start.format('MMM D');
				end_f = end.format('MMM D, YYYY');
			}
			return start_f + ' - ' + end_f;
		},
		selectBranch(option){
			this.selectedBranchId = option.value;
		},
		getDisabledWeekDays(){
			this.disabledDays =[];
			let shiftDays = this.shifts.map(({shift_day})=>{
                let day = shift_day.day;
                if(day == 7){
                    day=0;
                }
                return day;
            });
            
            this.disabledDays = _.difference(_.range(7), shiftDays);
		}
	}
}
</script>
<style lang="scss" src="./schedule.scss" scoped></style>
<style lang="scss">
.branch-select-dropdown{
    max-height: 200px;
}
.stations .nav-pills, .time-filter .nav-pills{
    padding: 0;
    list-style: none;
    margin: 0;
}
.stations .nav-item{
    margin-right: 5px;
}
.stations .nav-pills .nav-link{
    border: 1px solid #d1d9de;
    border-radius: 2px;
    padding: 4px 10px;
    color: #627680;
    font-size: 15px;
    text-decoration: none;
    transition: all ease 300ms;
    font-family: "Roboto-Light";
    &:hover{
        background-color: #0ecdee;
        border: 1px solid #0ecdee;
        text-decoration: none;
        color: #fff;
    }
}
.stations .nav-pills .nav-link.active{
    background-color: $blue;
    border: 1px solid $blue;
    color: #fff;
}

.time-filter .nav-item{
    margin-right: 5px;
}
.time-filter .nav-pills .nav-link{
    border: 1px solid #d1d9de;
    border-radius: 2px;
    padding: 4px 10px;
    color: #627680;
    font-size: 15px;
    text-decoration: none;
    transition: all ease 300ms;
    font-family: "Roboto-Light";
    &:hover{
        background-color: $blue;
        border: 1px solid $blue;
        text-decoration: none;
        color: #fff;
    }
}
.time-filter  .nav-pills .nav-link.active{
    background-color: $blue;
    border: 1px solid $blue;
    color: #fff;
    position: relative;
    &:after{
        border-right: 6px solid transparent;
        border-left: 6px solid transparent;
        border-top: 6px solid #ccc;
        position: absolute;
        bottom: -6px;
        left: 50%;
        margin-left: -6px;
        display: inline-block;
        border-top-color:$blue;
        content: '';
    }
}
</style>
