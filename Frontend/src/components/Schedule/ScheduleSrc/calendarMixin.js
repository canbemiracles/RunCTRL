import timeFunctions from '../../Common/Mixins/timesFunctions';
import {mapGetters, mapActions, mapMutations, mapState} from 'vuex';
import company from '../../../store/modules/company';
const calendar = {
    mixins: [timeFunctions],
    data: ()=>{
        return {
            arrayTasks: [],
            taskListSty: {
				height: '900px'
            },
            currentTime: null,
            taskStateIntervalFunc: null,
            getTimeFunction: null,
            currentDay: null,
            timeGround: null,
			pageTimeGround: [],
			currentShiftsList: [],
			assignmentsList:[],
			unwatchShifts: null,
			currTask: null,
			intervalFuncGetBranch: null,
			twelvehour: true,
			updateBranchDataEvery: 60000, //обновлять данные по бренчу каждые 60 сек.
        }
	},
    filters:{
		formatTimeGroud(value, vm) {
			if(vm.twelvehour){
					return moment(value, 'HH:mm').format('h A');
				}else{
					return moment(value, 'HH:mm').format('HH');
			}
		},
    },
    watch: {
		timeGround: {
            handler(value) {
                if(value){
                    this.initTimeGroud();
                }
            },
            immediate: true
        }
	},
	computed:{
		...mapGetters(['currentTimeGround', 'company']),
		...mapState({
			taskDetail: state=> state.calendar.taskDetail
		}),
	},
	created(){
		this.getCompanyData().then(()=>{
			if(!_.isEmpty(this.company)){
				if(this.company.time_format && this.company.time_format.time_format == 'hh:mm a'){
						this.twelvehour = true;
					}else{
						this.twelvehour = false;
				}
			}
		});
	},
	mounted(){
		this.$watch('currentShiftsList', function(){
			this.getTimeGround();
		}, { immediate: true, deep: true });
	},
    methods:{
		...mapActions(['fetchShifts', 'getCompanyData']),
		...mapMutations(['setCurrentTaskEdit', 'setCurrentTimeGround', 'setTaskDetail', 'addNewTempTask', 'setTaskDetailIndData']),
        getCurrentDay(){
            let date = new Date(); 
			let day = date.getDate(); //получить текущий день месяца
            var dayWeek = moment().isoWeekday(); // получит текущий день недели 1-7
			this.currentDay = {day, dayWeek};
        },
        getTimeGround(){
			let timeGround = [];
			let minValue, maxValue, minShift, maxShift;
			if(this.currentShiftsList.length){
				minValue=this.currentShiftsList[0].time_open;
				///MAX
				maxValue = this.currentShiftsList[0].time_close;
				this.currentShiftsList.forEach(shift=>{
                    let shiftMinValue=shift.time_open;
					let minTime = moment.min(moment.utc(minValue, 'HH:mm:ss'), moment.utc(shiftMinValue, 'HH:mm:ss')).format('HH:mm:ss');
					if(minTime==shiftMinValue){
						minValue=shiftMinValue;
					}
					//MAX
					let shiftMaxValue=shift.time_close;
					let maxTime = moment.max(moment.utc(maxValue, 'HH:mm:ss'), moment.utc(shiftMaxValue, 'HH:mm:ss')).format('HH:mm:ss');
					if(maxTime==shiftMaxValue){
						maxValue=shiftMaxValue;
					}
				});
				let minTimeObj = moment.utc(minValue, 'HH:mm:ss').toObject();
				let maxTimeObj = moment.utc(maxValue, 'HH:mm:ss').toObject();
				if(minTimeObj.minutes > 0){
					minTimeObj.minutes = 0
				}
				if(maxTimeObj.minutes > 0){
					maxTimeObj = moment(maxTimeObj).add(1, 'hours').toObject();
					maxTimeObj.minutes = 0;
				}
				minValue = moment.utc(minTimeObj);
				maxValue = moment.utc(maxTimeObj);
				
				let timeOpen = minValue.format('YYYY-MM-DD HH:mm:ss');
				let timeclose = maxValue.format('YYYY-MM-DD HH:mm:ss');

				let close_time = moment(timeclose, 'YYYY-MM-DD HH:mm:ss');
				let open_time = moment(timeOpen, 'YYYY-MM-DD HH:mm:ss');
				let nextDay = open_time.clone().add(1, 'day').startOf('date');
				if(close_time.isSameOrAfter(nextDay)){
					close_time = '24:00:00';
				}else{
					close_time = maxValue.format('HH:mm:ss');
				}

				timeGround[0] = minValue.format('HH:mm:ss');
				timeGround[1] = close_time;
				this.timeGround = timeGround;
			}else{
				this.timeGround = ['08:00', '10:00'];
			}
			if(this.getCurrentTime()){
				if(!this.getTimeFunction){
					this.getTimeFunction = setInterval(()=>{
						this.getCurrentTime();
					}, 30000);
				}
			}
			this.setCurrentTimeGround(this.timeGround);
		},
		roundTimeToHours(time){
			let dateRound = moment.utc(time, 'HH:mm:ss').toDate();
			let minutes = dateRound.getUTCMinutes();
			let hours = dateRound.getUTCHours();
			if(minutes > 0){
				hours++
			}
			return dateRound = `${hours}:00:00`;
		},
        initTimeGroud(){
            let value = this.timeGround;
			if(value && value.length == 2){
				let startTime = value[0].split(":")[0] * 1;
				let endTime = value[1].split(":")[0] * 1;
				value = [];
				for(let i = startTime; i <= endTime; i++){
					let hour = i < 10 ? "0" + i : "" + i;
					value.push(hour + ":00");
				}
			}
			this.pageTimeGround =  value;
        },
        initTaskPositions(){
            this.initTimeGroud();
            if(this.pageTimeGround.length){
                let maxTime = this.pageTimeGround[this.pageTimeGround.length - 1];
                let minTime = this.pageTimeGround[0];
                
                //Переводим время в минуты
                let maxMin = this.timeToMinutes(maxTime);
                let minMin = this.timeToMinutes(minTime);
                if(this.taskDetail){
                    for (let i = 0; i < this.taskDetail.length; i++) { //for day tasks
                        if(this.taskDetail[i]){
                            for (let j = 0; j < this.taskDetail[i].length; j++) { //for task in day
                                this.taskDetail[i][j].parent=0;
                                let start_timeStr = moment.utc(this.taskDetail[i][j].start_time_t).format('HH:mm:ss');
                                let end_timeStr = moment.utc(this.taskDetail[i][j].end_time_t).format('HH:mm:ss');
                                let startMin = this.timeToMinutes(start_timeStr);
                                let endMin = this.timeToMinutes(end_timeStr);
                                //Проверка наложениея тасков и формирование массива вложенных тасков
                                if(this.arrayTasks.length){
                                    for(let t = 0; t < this.arrayTasks.length; t++){
                                        let t1 = this.arrayTasks[t].timeRange;
                                        let t2 = { time_open: start_timeStr, time_close: end_timeStr};
                                        if(this.isIntersecting(t1, t2)){
                                            this.taskDetail[i][j].parent++;
                                            if(this.taskDetail[i][j].parent==1){
                                                this.arrayTasks[t].innerArr.push(this.taskDetail[i][j]);
                                            }
                                        }
                                    }
                                }
                                //формируем массив тасков
                                this.arrayTasks.push({timeRange: { time_open: start_timeStr, time_close: end_timeStr}, innerArr: []});
                                let difMin = endMin - startMin; // time range for task in minutes 
                                this.taskDetail[i][j].styleObj = { //определяю положение и размер таска на временной шкале
                                    height: difMin + 'px',
                                    width: '%',
                                    top: (startMin - minMin) + 'px', 
                                };
                            }
                        }
                        
                        for(let t=0; t < this.arrayTasks.length; t++){
                            var task =  this.arrayTasks[t];
                            if(task.innerArr.length!=0){
                                for( let x=0; x< task.innerArr.length; x++){
                                    task.innerArr[x].styleObj.width = 100 - (100/(task.innerArr.length+1))*(x+1) +'%';
                                }
                            }
                        }
                        this.arrayTasks=[];
					}
                }
            }
        },
        initSheduleHeight(){
            this.$nextTick(()=>{
                $('.schedule-column').animate({
                    height : (this.pageTimeGround.length - 1) * 60 + 'px'
                }, 400);
            });
		},
		getCurrentTime(){
			let date = new Date();
			let currentHours = date.getHours();
			let currentMinutes = date.getMinutes();
			let startTime, endTime;
			startTime = this.timeToMinutes(this.timeGround[0]);
			endTime = this.timeToMinutes(this.timeGround[1]);
			let currentTime = currentHours*60 + currentMinutes;
			this.currentTime = currentTime; 
			let timeSeeker = $('#time-seeker');
			let timePosition;
			if(currentTime > startTime && currentTime < endTime){
				timePosition = currentTime-startTime;
			}else{
				timePosition = 0;
			}
			timeSeeker.css({
				top: timePosition + 'px'
			});
			if(timePosition){
				return true;
			}else{
				return false;
			}
		},
		getStateAssignment(){
			this.daysAssignmentsList.forEach(item=>{
				item.assignments.forEach(assign=>{
					let state;
					let start_time = moment.utc(assign.start_time_t).format('YYYY MM DD HH:mm:ss');
					let end_time = moment.utc(assign.end_time_t).format('YYYY MM DD HH:mm:ss');
					if(assign.answers && assign.answers.length){
						//время создания последнего ответа в ассаинтменте
						let createdDate = assign.answers[assign.answers.length-1].created;
						let timeAnswer = moment(createdDate).format('YYYY MM DD HH:mm:ss');
						
						state = moment(timeAnswer, 'YYYY MM DD HH:mm:ss').isAfter(moment(start_time, 'YYYY MM DD HH:mm:ss')) && moment(timeAnswer, 'YYYY MM DD HH:mm:ss').isBefore(moment(end_time, 'YYYY MM DD HH:mm:ss'));
						
						if(state){
							assign.state = 0 //state ready
						}else{
							assign.state = 2 //state cancel
						}
					}else{
						let now = moment().format('YYYY MM DD HH:mm:ss');
						
						state = moment(now, 'YYYY MM DD HH:mm:ss').isAfter(moment(start_time, 'YYYY MM DD HH:mm:ss')) && moment(now, 'YYYY MM DD HH:mm:ss').isBefore(moment(end_time, 'YYYY MM DD HH:mm:ss'));
						if(state){
							assign.state = 1 //state pending
						}else{
							let dateEnd = moment.utc(assign.end_time_t).format('YYYY MM DD HH:mm:ss');
							if(moment().isAfter(moment(dateEnd, 'YYYY MM DD HH:mm:ss') )){
								assign.state = 2 //state cancel
							}else{
								assign.state = 3 //state planning (no-state)
							}
							
						}
					}
				});
			});
        },
		devideAssignmentsForDays(){
			let daysAssignmentsList=[];
			this.assignmentsList.forEach(assign=>{
				if(assign.start_time_t){
					let date = new Date(assign.start_time_t);
					let day = date.getUTCDate();
					day=day > 9 ? day : '0' + day;
					let month = date.getUTCMonth() +1;
					month=month > 9 ? month : '0' + month;
					let year = date.getUTCFullYear();
					let assignDay = `${month}/${day}/${year}`;
					if(daysAssignmentsList.length){
						let found=false;
						daysAssignmentsList.forEach(item=>{
							//если в массиве такой день есть, добавить ассайн в массив ассайнов этого дня
								if(assignDay==item.day){
								item.assignments.push(assign);
								found=true;
								}
							});
							if(!found){
							//иначе добавить как новый день
							daysAssignmentsList.push({
								day: assignDay,
								assignments: [assign]
							});
						}
					}else{
						daysAssignmentsList.push({
							day: assignDay,
							assignments: [assign]
						})
					}
				}
			});
			this.daysAssignmentsList = daysAssignmentsList;
		},
		prepeareAssignmentsList(){
			let assignments=[];
			this.rolesList.forEach(item=>{
				if(item.notifications_role){
					assignments = [...assignments, ...item.notifications_role];
				}
				if(item.notifications_station){
					assignments = [...assignments, ...item.notifications_station];
				}
				if(item.notifications_branch){
					assignments = [...assignments, ...item.notifications_branch];
				}
				if(item.assignments){
					assignments = [...assignments, ...item.assignments];
				}
			});
			this.assignmentsList = assignments;
		},
		setIntervalGetBranch(){
			console.log('start Interval getBranch');
			this.intervalFuncGetBranch = setInterval(()=>{
				console.log('fetxhData');
				this.getTasks();
		  	}, this.updateBranchDataEvery);
		},
		startGetBranchFunction(tasks=true){
			if(tasks){
				this.getTasks();
			}
			this.setIntervalGetBranch();
		},
		clearIntervGetBranch(){
			console.log('clear Interval getBranch');
			clearInterval(this.intervalFuncGetBranch);
		},
		createTask(e, week){
			this.currTask = null;
			if(week.disabled){
				return; 
			}
			let isDragging = false;
			this.clearIntervGetBranch(); // Приостановить обновление календаря
			let minTime = this.currentTimeGround[0];
			let targetCol = e.currentTarget.getBoundingClientRect();
			var offY  = e.clientY - targetCol.top;
			let columnElement = document.elementFromPoint(e.clientX, e.clientY);
			if(!$(columnElement).hasClass('schedule-column')){
				columnElement = $(columnElement).closest('.schedule-column')[0];
			}
			let showTaskFunc = (e)=>{
				if(!isDragging){
					let hours = offY / 60;
					let hoursInt = ~~hours;
					let minutes = Math.round((hours - hoursInt)*60);
					let startTime = moment(minTime, 'HH:mm:ss').add({hours: hoursInt, minutes, seconds: 0}).format('HH:mm:ss');
					let selectDay = moment({date: week.day, months: week.month -1, years: week.year}).toString(); 
					let task = {
						branch: this.currentBranch,
						station: this.currentStation,
						title: '',
						role:{},
						description: {
							title: '',
							info: '',
							checklist: [],
							answers: []
						},
						styleObj: {
							top: offY + 'px'
						},
						start_time: startTime,
						date: selectDay,
						snooze_max: 1,
						snooze_time: 60,
						repeat: false,
						repeat_unit: 1,
						repeat_subunit: 1,
						repeat_months: [],
						repeat_week: 1,
						repeat_week_days: [],
						repeat_month_days:[],
						priority: 1,
						importance: 1
					}
					let ind = $('.schedule-column').index(columnElement);
					if(this.taskDetail[ind] && this.taskDetail[ind].length){
						this.addNewTempTask({ind,task});
					}else {
						this.setTaskDetailIndData({ind, data: []});
						this.addNewTempTask({ind,task});
					}
					this.currTask = this.taskDetail[ind][this.taskDetail[ind].length-1];
					this.$set(this.currTask, 'index', ind);
				}
				isDragging = true;
				this.showTaskArea(e, this.currTask);
			}
			let openModalFunc = (e)=>{
				columnElement.removeEventListener('click', cancelClick);
				columnElement.removeEventListener('mousemove', showTaskFunc);
				if(!isDragging){
					clearTimeout(timeOutListeners);
					if(!this.intervalFuncGetBranch){
						this.startGetBranchFunction();
					}
					return false;
				}
				document.removeEventListener('mouseup', openModalFunc);
				let hoursEnd = (parseInt(this.currTask.styleObj.height) + offY) / 60;
				let hoursIntEnd = ~~hoursEnd;
				let minutesEnd = Math.round((hoursEnd - hoursIntEnd)*60);
				let endTime = moment(minTime, 'HH:mm:ss').add({hours: hoursIntEnd, minutes: minutesEnd, seconds: 0}).format('HH:mm:ss');
				this.$set(this.currTask, 'end_time', endTime);
				this.setCurrentTaskEdit(this.currTask);
				this.$root.$emit('bv::show::modal','taskModal');
				
			}
			let cancelClick = (e)=>{
				return false;
			}
			let timeOutListeners = setTimeout(function(){
				columnElement.addEventListener('mousemove', showTaskFunc,false);
				columnElement.addEventListener('click', cancelClick, false);
			}, 100);
			document.addEventListener('mouseup', openModalFunc, false);
		},
		showTaskArea(e, taskElem){
			let targetCol = e.currentTarget.getBoundingClientRect();
			var offY  = e.clientY - targetCol.top - parseInt(taskElem.styleObj.top);
			this.$set(taskElem.styleObj, 'height', offY + 'px');
		},
    },
    beforeDestroy(){
		clearInterval(this.getTimeFunction);
		clearInterval(this.taskStateIntervalFunc);
		if(this.getTimeFunction){
			clearInterval(this.getTimeFunction);
		}
		this.clearIntervGetBranch();
	},
}
export default calendar;