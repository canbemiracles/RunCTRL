<template>
    <div class="task-card">
        <div class="task-title">
            <input class="task-input" placeholder="Enter task title" v-model="task.title">
            <div class="task-icon-wrap">
                <svg class="icon standard_task"><use :xlink:href="'images/icons-sprite.svg#'+ taskIcon"></use></svg>
            </div>
        </div>
        <div class="description" v-if="typeModal == 'message' || typeModal == 'standard' || ~typeModal.indexOf('notification')">
            <input class="task-input" placeholder="Task description" v-model="task.description.info">
        </div>
        <div class="answers-task" v-if="typeModal == 'question_answer_list'">
            <input
            type="text" 
            v-for="(item, index) in task.description.answers"
            class="task-input answer-item"
            :key="index"
            :placeholder="'Option ' + (index + 1)"
            v-model="item.answer">
        </div>
        <div class="checklist d-flex" v-if="typeModal == 'checklist'">
            <input
            v-for="(item, index) in task.description.checklist"
            class="task-input check-item"
            :key="index"
            v-model="item.task"
            type="text"
            placeholder="Add new task to the checklist">
            <div class="check-item-btn-wrap">
               <add-btn class="medium-btn" @click="addToChecklist"></add-btn> 
            </div>
        </div>
        <div class="date-time-section">
            <div class="calendar-section d-flex">
                <div class="calendar-item d-flex">
                    <svg class="calendar-icon" width="14" height="19" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M192 1664h288v-288h-288v288zm352 0h320v-288h-320v288zm-352-352h288v-320h-288v320zm352 0h320v-320h-320v320zm-352-384h288v-288h-288v288zm736 736h320v-288h-320v288zm-384-736h320v-288h-320v288zm768 736h288v-288h-288v288zm-384-352h320v-320h-320v320zm-352-864v-288q0-13-9.5-22.5t-22.5-9.5h-64q-13 0-22.5 9.5t-9.5 22.5v288q0 13 9.5 22.5t22.5 9.5h64q13 0 22.5-9.5t9.5-22.5zm736 864h288v-320h-288v320zm-384-384h320v-288h-320v288zm384 0h288v-288h-288v288zm32-480v-288q0-13-9.5-22.5t-22.5-9.5h-64q-13 0-22.5 9.5t-9.5 22.5v288q0 13 9.5 22.5t22.5 9.5h64q13 0 22.5-9.5t9.5-22.5zm384-64v1280q0 52-38 90t-90 38h-1408q-52 0-90-38t-38-90v-1280q0-52 38-90t90-38h128v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h384v-96q0-66 47-113t113-47h64q66 0 113 47t47 113v96h128q52 0 90 38t38 90z"/></svg>
                    <div @click="togglePickers" class="calendar-date-time">{{ selectedDays }} {{ selectedTimeRange }}</div>  
                </div>
                <div class="pickers" v-show="showCalendarPickers">
                    <div class="calendar-wrap"  ref="datePicker"></div>
                    
                    <div class="time-pickers-input-wrap">
                        <transition name="fade">
                            <div class="time-picker-wrap" v-show="showTimeInputStart || showTimeInputEnd">
                                <div class="start-time-picker-wrap" v-show="showTimeInputStart"></div>
                                <div class="end-time-picker-wrap" v-show="showTimeInputEnd"></div>
                            </div>
                        </transition>
                        <div class="time-input d-flex">
                            <div class="start-time time-wrap">
                                <svg class="clock-icon" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1024 544v448q0 14-9 23t-23 9h-320q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h224v-352q0-14 9-23t23-9h64q14 0 23 9t9 23zm416 352q0-148-73-273t-198-198-273-73-273 73-198 198-73 273 73 273 198 198 273 73 273-73 198-198 73-273zm224 0q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z"/></svg>
                                <span class="clockpicker-span-hours">{{ $moment(task.start_time, 'HH:mm:ss').format(twelvehour ? 'hh' : 'HH')}}</span>
                                <span class="separator">:</span>
                                <span class="clockpicker-span-minutes">{{ $moment(task.start_time, 'HH:mm:ss').format('mm')}} </span>
                                <div class="clockpicker-am-pm-wrap" v-if="twelvehour">
                                    <span class="clockpicker-btn-am" :class="{active: $moment(task.start_time, 'HH:mm:ss').format('a') =='am'}">AM</span>
                                    <span class="clockpicker-btn-pm" :class="{active: $moment(task.start_time, 'HH:mm:ss').format('a') =='pm'}">PM</span>
                                </div> 
                            </div>
                            <div class="end-time time-wrap">
                                <svg class="clock-icon" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1024 544v448q0 14-9 23t-23 9h-320q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h224v-352q0-14 9-23t23-9h64q14 0 23 9t9 23zm416 352q0-148-73-273t-198-198-273-73-273 73-198 198-73 273 73 273 198 198 273 73 273-73 198-198 73-273zm224 0q0 209-103 385.5t-279.5 279.5-385.5 103-385.5-103-279.5-279.5-103-385.5 103-385.5 279.5-279.5 385.5-103 385.5 103 279.5 279.5 103 385.5z"/></svg>
                                <span class="clockpicker-span-hours">{{ $moment(task.end_time, 'HH:mm:ss').format(twelvehour ? 'hh' : 'HH')}}</span>
                                <span class="separator">:</span>
                                <span class="clockpicker-span-minutes">{{ $moment(task.end_time, 'HH:mm:ss').format('mm')}} </span>
                                <div class="clockpicker-am-pm-wrap" v-if="twelvehour">
                                    <span class="clockpicker-btn-am" :class="{active: $moment(task.end_time, 'HH:mm:ss').format('a') =='am'}">AM</span>
                                    <span class="clockpicker-btn-pm" :class="{active: $moment(task.end_time, 'HH:mm:ss').format('a') =='pm'}">PM</span>
                                </div> 
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
            <div class="snooze-section" >
                <div class="snooze-row d-flex">
                    <template v-if="typeModal.indexOf('notification')==-1">
                        <svg class="snooze-icon" width="16" height="16" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 20 21"><defs><path id="xlgfa" d="M65.88 2070.36l-4.59 3.8-1.29-1.5 4.6-3.81zm14.12 2.3l-1.29 1.52-4.6-3.82 1.29-1.51zm-10-1.7c4.97 0 9 3.99 9 8.9s-4.03 8.9-9 8.9-9-3.99-9-8.9 4.03-8.9 9-8.9zm0 15.82c3.87 0 7-3.1 7-6.92a6.96 6.96 0 0 0-7-6.92c-3.87 0-7 3.1-7 6.92a6.96 6.96 0 0 0 7 6.92zm-3-10.87h6v1.77l-3.63 4.16H73v1.97h-6v-1.78l3.63-4.15H67z"/></defs><g><g transform="translate(-60 -2068)"><use fill="#8b9da6" xlink:href="#xlgfa"/></g></g></svg>
                        <span class="snooze__text">Snooze:</span>
                        <counter class="counter counter-item" :init="task.snooze_time / 60" :min="0" @changeValue="setSnoozeCountMin"></counter> 
                        <span class="label-snooze-counter">min - </span>
                        <counter class="counter counter-item" :init="task.snooze_max" :min="0" @changeValue="setSnoozeCountTimes"></counter>
                        <span class="label-snooze-counter">times</span>
                    </template>
                    <div class="repeat-wrap">
                        <span class="label-snooze-counter">Repeat</span>
                        <repeat v-model="task.repeat" index="modalRepeatSwitch"></repeat>
                    </div>
                </div>
                <template v-if="task.repeat">    
                    <div class="repeat-collapse" >
                        <small-btn @click="repeatPeriod = 1" text="Daily" :active="repeatPeriod === 1"/>
                        <small-btn @click="repeatPeriod = 2" text="Weekly" :active="repeatPeriod === 2"/>
                        <small-btn @click="repeatPeriod = 3" text="Monthly" :active="repeatPeriod === 3"/>
                        <small-btn @click="repeatPeriod = 4" text="Yearly" :active="repeatPeriod === 4"/>
                    </div>
                    <div class="repeat-collapse-content d-flex">
                        <div class="repeat-row d-flex">
                            <span class="label-snooze-counter">Every</span>
                            <counter class="counter counter-item repeat-counter" :init="task.repeat_subunit" :min="1" @changeValue="setReapeatPeriod"></counter>     
                            <span class="label-snooze-counter">{{ repeatUnit }}{{ task.repeat_subunit > 1 ? 's': ''}} {{ task.repeat_unit == 4 ? 'in:' : ''  }}</span>
                            
                            <week-days-bar v-if="task.repeat_unit == 2" 
                            @selectWeekDay = "selectWeekDayWeek"
                            :selectedWeekDays="repeat_week_days_week" 
                            :disabledDays="disabledDays" 
                            @removeWeekDay = "removeWeekDayWeek">
                            </week-days-bar>
                            <div class="radio-buttons" v-if="task.repeat_unit == 3">
                                <div class="radio-item">
                                    <input type="radio" id="monthDay" value="day" v-model="pickedMonthRepeat">
                                    <label for="monthDay" class="radio-input"></label>
                                    <label for="monthDay" class="radio-label">month day</label>
                                </div>
                                <div class="radio-item">
                                    <input type="radio" id="monthWeek" value="week" v-model="pickedMonthRepeat">
                                    <label for="monthWeek" class="radio-input"></label>
                                    <label for="monthWeek" class="radio-label">week day</label>
                                </div>
                            </div>
                        </div>
                        <div class="repeat-row d-flex" v-if="pickedMonthRepeat == 'week' && task.repeat_unit == 3">
                            <app-select :options="numberWeekList"
                            @changeSelection="changeNumberWeek($event, 3)"
                            class="task-select"
                            dropDownClass="select-dropdown"
                            :initValue="repeat_week_month"
                            ></app-select>
                            <week-days-bar
                            :oneSelect="true" 
                            :disabledDays="disabledDays" 
                            :selectedWeekDays="repeat_week_days_month" 
                            @selectWeekDay = "selectWeekDayMonth"
                            @removeWeekDay = "removeWeekDayMonth">
                            </week-days-bar>
                        </div>
                        <div class="repeat-row d-flex" v-if="pickedMonthRepeat == 'day' && task.repeat_unit == 3">
                            <month-days-picker 
                            :selectedDays="month_days"
                            @selectMonthDay="chooseMonthDay"
                            @removeMonthDay="removeMonthDay"></month-days-picker>
                        </div>
                        <template v-if="task.repeat_unit == 4">
                            <div class="repeat-row d-flex">
                                <month-choose-bar
                                :selectedMonths="task.repeat_months"
                                @selectMonth = "selectMonth"
                                @removeMonth = "removeMonth"
                                ></month-choose-bar>
                            </div>
                            <div class="repeat-row d-flex">
                                <div class="checkbox">
                                    <input type="checkbox" id="yearRepeatWeek" value="yearRepeat" v-model="pickedYearRepeatWeek">
                                    <label for="yearRepeatWeek" class="radio-input"></label>
                                    <label for="yearRepeatWeek" class="radio-label">On the: </label>
                                </div>
                            </div>
                            <div class="repeat-row d-flex">
                                <app-select :options="numberWeekList"
                                @changeSelection="changeNumberWeek($event, 4)"
                                :disabled="!pickedYearRepeatWeek"
                                class="task-select"
                                dropDownClass="select-dropdown"
                                :initValue="repeat_week_year"
                                ></app-select>
                                <week-days-bar
                                :oneSelect="true"
                                :disabled="!pickedYearRepeatWeek" 
                                :disabledDays="disabledDays"
                                :selectedWeekDays="repeat_week_days_year"  
                                @selectWeekDay = "selectWeekDayYear"
                                @removeWeekDay = "removeWeekDayYear">
                                </week-days-bar>
                            </div>
                        </template>    
                    </div>
                </template>
            </div>
            <div class="attach-role-wrap" v-if="typeModal!='message_to_employee'">
                <div class="attach-row">
                    <div class="add-wrap d-flex">
                        <add-btn class="small-btn" v-if="!task.branch && !allBranch"></add-btn>
                        <div class="branch-icon" v-else></div>
                        <autocomplete :suggestions="branchList"
                            ref="branchAutocomplete" 
                            fieldName="street_address"
                            id="branch"
                            :iconSearch="false"
                            :init="task.branch"
                            placeholder="Add Branch"
                            labelField="country"
                            @suggestionClick="setBranch"
                            @noSelection="resetBranch"
                        >
                        <div v-if="typeModal =='notification'" slot="firstField" class="autocomplete-first-field"  @click="setAllBranch">Send to All Branches</div>
                        </autocomplete>
                    </div>
                </div>
                <div class="attach-row" :class="{disabled: !task.branch}">
                    <div class="add-wrap d-flex">
                        <add-btn class="small-btn" v-if="!task.station"></add-btn>
                        <svg v-else class="icon-station" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 34 34"><path d="M27.1 0H6.9C5.6 0 4.6 1 4.6 2.3v29.4c0 1.3 1 2.3 2.3 2.3h20.3c1.3 0 2.3-1 2.3-2.3V2.3C29.4 1 28.4 0 27.1 0zM17 33.1c-0.8 0-1.4-0.6-1.4-1.4 0-0.8 0.6-1.4 1.4-1.4 0.8 0 1.4 0.6 1.4 1.4C18.4 32.5 17.8 33.1 17 33.1zM27.1 29.2H6.9V2.8h20.2V29.2z" fill="#8a9ea8"/></svg>
                        <autocomplete :suggestions="stationsList" 
                            fieldName="name"
                            id="station"
                            ref="stationAutocomplete"
                            :iconSearch="false"
                            :disabled="!task.branch"
                            :init="task.station"
                            placeholder="Add Station"
                            @suggestionClick="setStation"
                            @noSelection="resetStation"
                        ></autocomplete>
                    </div>
                </div>
                <div class="attach-row" :class="{disabled: !task.station}">
                    <div class="add-wrap d-flex">
                        <add-btn class="small-btn" v-if="task.role && !task.role.role"></add-btn>
                        <div class="role-icon" v-else v-color:bg="task.role ? task.role.color : ''"></div>
                        <autocomplete :suggestions="rolesList" 
                            fieldName="role"
                            id="role"
                            ref="roleAutocomplete"
                            :iconSearch="false"
                            :disabled="!task.station || roleDisabled"
                            placeholder="Add Role"
                            :init="task.role ? task.role.id : null"
                            @suggestionClick="setRole"
                            @noSelection="resetRole"
                            :roleIcon="true"
                        >
                        </autocomplete> 
                    </div>
                </div>   
            </div>
            <div class="attach-to-employee" v-else>
                <div class="attach-row">
                    <div class="add-wrap d-flex">
                        <add-btn class="small-btn" v-if="!task.employee && employeesList.length"></add-btn>
                        <div class="employee-icon" v-if="task.employee">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="18" viewBox="0 0 16 19"><defs><path id="ks7ha" d="M65.7 1346.83a9.2 9.2 0 0 1-3.2-2.96c.16-1.27 1.1-2.25 2.81-2.93a12.92 12.92 0 0 1 4.69-1.01c1.4 0 2.97.33 4.69 1.01 1.72.68 2.65 1.66 2.81 2.93A9.2 9.2 0 0 1 70 1348a9.1 9.1 0 0 1-4.3-1.17zm6.94-15.87c.7.71 1.05 1.62 1.05 2.72s-.35 2-1.05 2.72a3.54 3.54 0 0 1-2.64 1.07 3.54 3.54 0 0 1-2.64-1.07 3.73 3.73 0 0 1-1.05-2.72c0-1.1.35-2 1.05-2.72a3.54 3.54 0 0 1 2.64-1.08c1.05 0 1.93.36 2.64 1.08z"/></defs><g><g transform="translate(-62 -1329)"><use fill="#fff" xlink:href="#ks7ha"/></g></g></svg>
                        </div>
                        <autocomplete :suggestions="employeesList" v-if="employeesList.length" 
                            fieldName="employee_name"
                            id="employee"
                            :iconSearch="false"
                            placeholder="Add Employee"
                            labelField="label"
                            @suggestionClick="setEmployee"
                        ></autocomplete>
                        <div class="no-employee" v-else>
                            No employees on the current shift
                        </div>
                    </div>
                </div>
            </div>
            <div class="priority-importance-wrap d-flex" v-if="typeModal != 'notification'">
                <div class="priority-importance-item d-flex">
                    <div class="icon-circle blue" @click="task.priority = 1" :class="{active: task.priority >= 1}">
                        <svg class="icon-priority-star" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg>
                    </div>
                    <div class="icon-circle orange" @click="task.priority = 2" :class="{active: task.priority >= 2}">
                        <svg class="icon-priority-star" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg>
                    </div>
                    <div class="icon-circle red"  @click="task.priority = 3" :class="{active: task.priority == 3}">
                        <svg class="icon-priority-star" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1728 647q0 22-26 48l-363 354 86 500q1 7 1 20 0 21-10.5 35.5t-30.5 14.5q-19 0-40-12l-449-236-449 236q-22 12-40 12-21 0-31.5-14.5t-10.5-35.5q0-6 2-20l86-500-364-354q-25-27-25-48 0-37 56-46l502-73 225-455q19-41 49-41t49 41l225 455 502 73q56 9 56 46z"/></svg>
                    </div>
                </div>
                <div class="priority-importance-item d-flex">
                    <div class="icon-circle blue" @click="task.importance = 1" :class="{active: task.importance >= 1}"></div>
                    <div class="icon-circle orange" @click="task.importance = 2" :class="{active: task.importance >= 2}"></div>
                    <div class="icon-circle red" @click="task.importance = 3" :class="{active: task.importance == 3}"></div>
                </div>   
            </div>
            
        </div>
        <preloader :show="showPreloader"></preloader>
    </div>
</template>
<script>
import {mapActions, mapGetters, mapState, mapMutations} from 'vuex';

export default {
    name: "TaskCard",
    props: ['task', 'close', 'disabledDays'],
    data: ()=>({
        copyTask: {},
        showTimeInputStart: false,
        showTimeInputEnd: false,
        repeatPeriod: 1,
        pickedMonthRepeat: 'day',
        pickedYearRepeatWeek: false,
        stationsList: [],
        rolesList: [],
        showCalendarPickers: false,
        twelvehour: true,
        employeesList: [],
        allBranch: false,
        roleDisabled: false,
        month_days: [],
        repeat_week_days_month: [],
        repeat_week_days_year: [],
        repeat_week_days_week: [],
        repeat_week_year: 1,
        repeat_week_month: 1,
        showPreloader: false,
        icon: ''
    }),
    watch:{
        repeatPeriod(){
            this.$set(this.task, 'repeat_unit', this.repeatPeriod);
        },
        'task.title':{
            handler(val){
                this.task.description.title = val;
            },
            immediate: true
        },
        close(val){
            if(val){
                if(this.isValid){
                    let data;
                    let method = this.edit ? 'patch' : 'post';
                    if(!this.task.repeat){
                            this.task.repeat_month_days.forEach((day, index)=>{
                                data = this.patchTimeData(day.month_day);
                                if(this.edit){
                                    if(index==0){
                                        method = 'patch';  
                                    }else{
                                        method = 'post';
                                    }
                                }
                                this.sendData(data, method).then(()=>{
                                    if(index==0){
                                      this.$emit('sendData');  
                                    }
                                });
                            });
                    }else{
                        data = this.patchTimeData(this.task.repeat_month_days[0].month_day);
                        console.log(method);
                        this.sendData(data, method).then(()=>{
                            if(this.edit){
                              this.setEditModeTask(false);  
                            }
                            this.$emit('sendData');
                        });
                    }
                }else{
                   this.$emit('validFalse');
                }
            }
        },
    },
    computed:{
        ...mapGetters(['branches', 'typeModal', 'language', 'shifts', 'timeFormat', 'dateFormat']),
        ...mapState({
            weekStart: state => state.shifts.day_start,
            edit: state => state.calendar.editModeTask,
        }),
        branchList(){
            if(this.branches.length){
                let list = this.branches.map(({stations, geographical_area, company, id }) =>{
                    let country= null;
                    if(company && company.geographical_area && company.geographical_area.country){
                        country = company.geographical_area.country.name;
                    }
                    return {
                        stations, 
                        street_address: geographical_area ? geographical_area.street_address: geographical_area, 
                        country,
                        id 
                    }
                }).filter(item => item.street_address);
                return list;
            }
        },
        taskIcon(){
            switch(this.typeModal){
                case 'standard':
                    return 'standard_task';
                case 'question_yes_no':
                    return 'question_yes_no';
                case 'question_answer_list':
                    return 'question_4_answers';
                case 'question_numeric':
                    return 'question_numeric_input';
                case 'question_range':
                    return 'question_range_from_x_to_y.svg';
                case 'checklist':
                    return 'checklist.svg';
                case 'question_text':
                    return 'question_text_input';
                case 'notification':
                case 'notification_branch':
                case 'notification_station':
                case 'notification_role':
                    return 'notification';
                case 'message':
                    return 'message_to_employee';
            }
        },
        timeObj(){
            let timeObj = {}
            if(this.task){
                if(this.task.start_time){
                    timeObj.start = this.task.start_time;
                }else{
                    timeObj.start = '10:00:00';
                }
                if(this.task.end_time){
                    timeObj.end = this.task.end_time;
                }else{
                    timeObj.end = '11:00:00';
                }
            }
            return timeObj;
        },
        selectedTimeRange(){
            let start = moment(this.task.start_time, 'HH:mm:ss').format(this.twelvehour ? 'hh:mm A' : 'HH:mm');
            let end = moment(this.task.end_time, 'HH:mm:ss').format(this.twelvehour ? 'hh:mm A' : 'HH:mm');
            return `${start} to ${end}`
        },
        repeatUnit(){
            switch(this.repeatPeriod){
                case 1:
                    return 'day'
                case 2:
                    return 'week'
                case 3:
                    return 'month'
                case 4:
                    return 'year'
            }
        },
        numberWeekList(){
            let list = [
                { value: 1, text: 'first'},
                { value: 2, text: 'second'}, 
                { value: 3, text: 'third'}, 
                { value: 4, text: 'fourth'},
            ];
            let date = moment(this.task.repeat_month_days[0], 'MMM D, YYYY');
            let lastDay = date.daysInMonth();
            let year = date.year();
            let month = date.month();
            if(this.weekOfMonth(moment({year, month, day: lastDay}))== 5){
                list.push({ value: 5, text: 'fifth'});
            }
            return list;  
        },
        typeData() {
            if(this.typeModal){
                const { title, info, answers, checklist} = this.task.description
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
                }[this.typeModal]
            }
        },
        isValid() {
            if(this.typeModal){
                let setAllTextInput = Object
                .values(this.typeData)
                .every(v => Array.isArray(v) ? v.length : v);
                let setAttachTarget = false;
                if(this.typeModal.indexOf('notification')==-1){
                    setAttachTarget = this.task.branch && this.task.station && this.task.role.role;
                }else if(~this.typeModal.indexOf('notification')){
                    if(this.allBranch){
                        setAttachTarget = true;
                    }else{
                       setAttachTarget = !!(this.task.branch || this.task.station || this.task.role.role); 
                    }
                }
                console.log(setAllTextInput, setAttachTarget);
                return setAllTextInput && setAttachTarget;
            }
        },
        selectedDays(){
            let daysArr = '';
            this.task.repeat_month_days.forEach((el, ind)=>{
                let formatdate = this.dateFormat ? moment(el.month_day, 'MMM DD, YYYY').format(this.dateFormat) : el.month_day;
                if(ind==0){
                    daysArr += formatdate;
                }else{
                  daysArr += ', ' + formatdate;  
                }
            });
            return daysArr;
        },
        
    },
    created(){
        if(this.typeModal=='message'){
            this.getCompanyEmployeesCurrentShift().then((res)=>{
                if(res.body.length){
                    this.employeesList = res.body.map(item=>{
                        item.employee_name = `${item.employee.first_name} ${item.employee.last_name}`;
                        if(item.employee.branches && item.employee.branches.geographical_address){
                            item.label =   `${item.role.role}, ${item.employee.branches.geographical_address.street_address}`;
                        }
                        return item;
                    })
                }else{
                    this.employeesList=[];
                }
            })
        }
    },
    mounted(){
        let vm = this;
        if(this.typeModal == 'question_answer_list' && !this.edit){
            this.$set(this.task.description, 'answers', []);
            for(let i=0;i<4; i++){
                this.task.description.answers.push({ answer: '' });
            } 
        }
        if(this.typeModal == 'checklist' && !this.edit){
            this.$set(this.task.description, 'checklist', []);
            this.addToChecklist();
        }
        this.fetchWeekStart().then(()=>{
            if(this.timeFormat == 'hh:mm a'){
                this.twelvehour = true;
            }else{
               this.twelvehour = false;
            }
            $('.start-time').clockpicker({
                container: '.start-time-picker-wrap',
                placement: false,
                twelvehour: this.twelvehour,
                afterDone: this.setStartTime,
                beforeShow: function(){
                    vm.showTimeInputStart = true;
                    $('.end-time').clockpicker('hide');
                },
                beforeHide: function(){
                    vm.showTimeInputStart = false;
                }
            });
            $('.end-time').clockpicker({
                container: '.end-time-picker-wrap',
                placement: false,
                twelvehour: this.twelvehour,
                afterDone: this.setEndTime,
                beforeShow: ()=>{
                    vm.showTimeInputEnd = true;
                    $('.start-time').clockpicker('hide');
                },
                beforeHide: ()=>{
                    vm.showTimeInputEnd = false;
                }
            });
        });
        let start =  moment();
        $.fn.datepicker.dates['en'].daysMin = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
        $(vm.$refs.datePicker).datepicker({
            multidate: true,
            container: '.calendar-wrap',
            format: 'MMM D, YYYY',
            weekStart: vm.weekStart,
            language: vm.language,
            daysOfWeekDisabled: this.disabledDays,
            maxViewMode: 2,
            templates:{
                leftArrow: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="9" viewBox="0 0 10 9"><defs><path id="edkua" d="M4482.45 234.1l-4.54-4.5-.91.9 4.55 4.5z"/><path id="edkub" d="M4477 230.5l4.55-4.5.9.9-4.54 4.5z"/><path id="edkuc" d="M4477.91 229.83h9.1v1.35h-9.1z"/></defs><g><g transform="translate(-4477 -226)"><g><use fill="#adadad" xlink:href="#edkua"/></g><g><use fill="#adadad" xlink:href="#edkub"/></g><g><use fill="#adadad" xlink:href="#edkuc"/></g></g></g></svg>',
                rightArrow: '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="10" height="9" viewBox="0 0 10 9"><defs><path id="bzyba" d="M4772.45 234.1l-4.54-4.5-.91.9 4.55 4.5z"/><path id="bzybb" d="M4767 230.5l4.55-4.5.9.9-4.54 4.5z"/><path id="bzybc" d="M4767.91 229.83h9.1v1.35h-9.1z"/></defs><g><g transform="matrix(-1 0 0 1 4777 -226)"><g><use fill="#adadad" xlink:href="#bzyba"/></g><g><use fill="#adadad" xlink:href="#bzybb"/></g><g><use fill="#adadad" xlink:href="#bzybc"/></g></g></g></svg>' 
            }
        }).on('changeDate', (e)=>{
            console.log('changeDate', e);
            let dates = e.dates.map(date=>{
                return moment(date).format('MMM D, YYYY');
            });
            vm.$set(vm.task, 'repeat_month_days', []);
            if(!vm.edit){
                vm.$set(vm, 'month_days', []);
            }
            dates.forEach(el=>{
                this.selectMonthDay(el);
                if(!vm.edit){
                    vm.month_days.push(moment(el, 'MMM D, YYYY').date());
                }
            });
        });
        $(vm.$refs.datePicker).datepicker('setDates', new Date(vm.task.date));
        //COPY TASK DATA
        if(this.edit){
            this.copyTask = _.cloneDeep(this.task); 
            if(!this.task.repeat_unit){
                this.task.repeat_unit = 1;
                this.task.repeat_subunit = 1;
            }
            this.repeatPeriod = this.task.repeat_unit;
            let week_days = this.task.repeat_week_days.map(({week_day})=>(week_day));
            if(this.repeatPeriod==3){
                if(!this.task.repeat_week){
                   this.month_days = this.task.repeat_month_days.map(({month_day})=>(month_day)); 
                }else{
                    this.repeat_week_days_month = week_days;
                }
            }
            if(this.repeatPeriod==4){
                if(this.task.repeat_week){
                    this.repeat_week_days_year = week_days;
                }
            }
            if(this.repeatPeriod==2){
                this.repeat_week_days_week = week_days;
            }
        }
    },
    methods:{
        ...mapActions(['resizeTaskFromInput', 'fetchWeekStart', 'getBranchShifts', 'getCompanyData', 'getCompanyEmployeesCurrentShift']),
        ...mapMutations(['setEditModeTask']),
        togglePickers(){
            this.showCalendarPickers = !this.showCalendarPickers;
        },
        addToChecklist() {
			this.task.description.checklist.push({ task: '' });
		},
        setStartTime(){
            let hours = $('.start-time').find('.clockpicker-span-hours').text();
            let minutes = $('.start-time').find('.clockpicker-span-minutes').text();
            let time;
            if(this.twelvehour){
                let amORpm =  $('.start-time').find('.clockpicker-am-pm-wrap .active').text();
                time = moment(`${hours}:${minutes} ${amORpm}`, 'hh:mm a').format('HH:mm:ss'); 
            }else{
                time = `${hours}:${minutes}:00`;
            }
            this.task.start_time = time;
            this.resizeTaskFromInput(this.task);
        },
        setEndTime(){
            let hours = $('.end-time').find('.clockpicker-span-hours').text();
            let minutes = $('.end-time').find('.clockpicker-span-minutes').text();
            let time;
            if(this.twelvehour){
                let amORpm =  $('.end-time').find('.clockpicker-am-pm-wrap .active').text();
                time = moment(`${hours}:${minutes} ${amORpm}`, 'hh:mm a').format('HH:mm:ss'); 
            }else{
                time = `${hours}:${minutes}:00`;
            }
            this.task.end_time = time;
            this.resizeTaskFromInput(this.task);
        },
        setSnoozeCountMin(value){
            this.$set(this.task, 'snooze_time', value * 60);
        },
        setSnoozeCountTimes(value){
            this.$set(this.task, 'snooze_max', value);
        },
        showTimeChoose(){
            this.showTimeInputs = !this.showTimeInputs; 
        },
        setReapeatPeriod(value){
            this.$set(this.task, 'repeat_subunit', value);
        },
        selectWeekDayMonth(id, oneSelect){
            if(oneSelect){
                this.repeat_week_days_month = [];
            }
            this.repeat_week_days_month.push(id);
        },
        removeWeekDayMonth(id){
           this.repeat_week_days_month = this.repeat_week_days_month.filter((week_day)=>{
               return id != week_day;
           }); 
        },
        selectWeekDayYear(id, oneSelect){
            if(oneSelect){
                this.repeat_week_days_year = [];
            }
            this.repeat_week_days_year.push(id);
        },
        removeWeekDayYear(id){
           this.repeat_week_days_year = this.repeat_week_days_year.filter((week_day)=>{
               return id != week_day;
           }); 
        },
        selectWeekDayWeek(id, oneSelect){
            if(oneSelect){
                this.repeat_week_days_week = [];
            }
            this.repeat_week_days_week.push(id);
        },
        removeWeekDayWeek(id){
           this.repeat_week_days_week = this.repeat_week_days_week.filter((week_day)=>{
               return id != week_day;
           }); 
        },
        selectMonth(id){
            this.task.repeat_months.push({ month: id});
        },
        removeMonth(id){
           this.task.repeat_months = this.task.repeat_months.filter(({month})=>{
               return id != month;
           }); 
        },
        selectMonthDay(date){
            this.task.repeat_month_days.push({ month_day: date});
            let month = moment(date, 'MMM D, YYYY').month() + 1;
            let existMonth = false;
            this.task.repeat_months.forEach(el=>{
                if(el.month == month){
                    existMonth = true;
                }
            });
            console.log(existMonth);
            if(!existMonth){
                console.log(month);
                this.task.repeat_months.push({month});
            } 
        },
        chooseMonthDay(date){
            this.month_days.push(date);
        },
        removeMonthDay(date){
            this.month_days = this.month_days.filter((day)=>(day!=date));
        },
        weekOfMonth(m) {
           return m.week() - moment(m).startOf('month').week() + 1;
        },
        changeNumberWeek(option, unit){
            if(unit==3){
              this.repeat_week_month = option.value;  
            }else if(unit==4){
             this.repeat_week_year = option.value;   
            }
        },
        setBranch(option){
            this.$set(this.task, 'branch', option.id);
            this.branchList.forEach(branch=>{
                if(branch.id == option.id){
                    this.stationsList = branch.stations;
                }
            });
        },
        setAllBranch(){
            console.log('slotWorked');
            this.allBranch = true;
            this.$refs.branchAutocomplete.selection = 'Send to All Branches';
            this.$refs.stationAutocomplete.selection = '';
            this.roleDisabled = true;
            this.task.branch = null;
        },
        setStation(option){
            this.$set(this.task, 'station', option.id);
            this.stationsList.forEach(station=>{
                if(station.id == option.id){
                    this.rolesList = station.origin_roles;
                }
            });
        },
        resetBranch(){
            this.task.branch = null;
            this.task.station = null;
            this.$set(this.task.role, 'role', null);
            this.$set(this.task.role, 'color', null);
        },
        resetStation(){
            this.task.station = null;
            this.$set(this.task.role, 'role', null);
            this.$set(this.task.role, 'color', null);
        },
        resetRole(){
            this.$set(this.task.role, 'role', null);
            this.$set(this.task.role, 'color', null);
        },
        setRole(option){
            this.$set(this.task, 'role', {
                id: option.id,
                color: option.color,
                role: option.role
            });
        },
        setEmployee(option){
            this.$set(this.task, 'employee', option.id);
        },
        patchTimeData(date) {
            let day = moment(date, 'MMM D, YYYY').format('YYYY-MM-DD');
            let sendData =  {
                start_time: day + ' ' +  this.task.start_time,
                end_time: day + ' ' + this.task.end_time,
            }
            if(this.task.repeat){
                sendData.repeat_unit = this.task.repeat_unit;
                sendData.repeat_subunit = this.task.repeat_subunit;
                if(this.task.repeat_unit == 2){
                    sendData.repeat_week_days =  this.repeat_week_days_week.map(day=> ({week_day: day}));
                }
                if(this.task.repeat_unit == 3 && this.pickedMonthRepeat == 'day'){
                    sendData.repeat_month_days = this.month_days.map(day=> ({month_day: day}));
                }
                if(this.task.repeat_unit == 3 && this.pickedMonthRepeat == 'week'){
                    sendData.repeat_week_days =  this.repeat_week_days_month.map(day=> ({week_day: day}));
                    sendData.repeat_week =  this.repeat_week_month;
                }
                if(this.task.repeat_unit == 4){
                    sendData.repeat_months =  this.task.repeat_months; 
                }
                if(this.task.repeat_unit == 4 && this.pickedYearRepeatWeek){
                    sendData.repeat_week_days =  this.repeat_week_days_year.map(day=> ({week_day: day}));
                    sendData.repeat_week =  this.repeat_week_year;
                }
                if(this.task.repeat_unit == 4 && !this.pickedYearRepeatWeek){
                    let dates = [];
                    this.task.repeat_month_days.forEach(el=>{
                        dates.push(moment(el.month_day, 'MMM D, YYYY').getDate());
                    });
                    sendData.repeat_month_days = dates;
                }
            }else{
                sendData.repeat_unit = null;
                sendData.repeat_subunit = null;
                sendData.repeat_week = null;
                this.task.repeat_unit = null;
                this.task.repeat_subunit = null;
                this.task.repeat_week = null;
            }

            if(this.typeModal.indexOf('notification')==-1){
                sendData.snooze_max = this.task.snooze_max;
                sendData.snooze_time = this.task.snooze_time;
            }
            return sendData;
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
            }[this.typeModal]
            if(~this.typeModal.indexOf('notification')){
                if(!stId && !roleId){
                    return `api/v1/branches/${brId}/${typeUrl}/${ending}`
                }else if(!roleId){
                    return `api/v1/branches/${brId}/stations/${stId}/${typeUrl}/${ending}`
                }else{
                    return `api/v1/branches/${brId}/stations/${stId}/roles/${roleId}/${typeUrl}/${ending}`
                }
            }else if(this.typeModal=='message'){
                return `api/v1/branches/${brId}/employees/${emplId}/${typeUrl}/${ending}`
            }else{
                return `api/v1/branches/${brId}/stations/${stId}/assignments/${typeUrl}/${ending}`
            }
        },
        sendData(data, method = 'patch') {
            let fields = _.keys(this.typeData);
            fields.forEach(field=>{
                data[field] = this.typeData[field];
            });
            if(this.edit){
                 let compareData1 = _.pick(this.copyTask, [..._.keys(data), 'repeat_month_days']);
                let compareData2 = _.pick(this.task,[..._.keys(data), 'repeat_month_days']);
                console.log(compareData1, compareData2);
                if(_.isEqual(compareData1, compareData2)){
                    console.log('no change in send data');
                    return Promise.resolve();
                }
            }
            this.showPreloader = true;
            const isPost = method === 'post';
            if(this.task.role && this.task.role.id){
                if (isPost && this.typeModal.indexOf('notification')==-1) {
                    data.role = this.task.role.id;
                }
                if (this.typeModal.indexOf('notification')==-1) {
                    data.importance = this.task.importance;
                    data.priority = this.task.priority;
                }
                
                let url2 = this.makeURL(this.task.branch, this.task.station, isPost ? 'new' : this.task.id, ~this.typeModal.indexOf('notification') && this.task.role.id ? this.task.role.id : undefined);
                return this.$http[method](url2, data, {emulateJSON: true})
                    .then(({ body }) => {
                        isPost && (this.$set(this.task, 'id', body.id));
                        this.showPreloader = false;
                    })
                    .catch(err => {
                        console.log('Error in sendData(): ', err);
                        this.showPreloader = false;
                    })
                
            }else if(~this.typeModal.indexOf('notification')){
                if(this.allBranch){
                    return new Promise(resolve=>{
                        this.branchList.forEach(branch=>{
                            this.sendDataNotifications(branch.id, null, data, method, isPost).then(()=>{
                                this.showPreloader = false;
                                resolve();
                            });
                        });
                    })
                }else{
                    return this.sendDataNotifications(this.task.branch, this.task.station, data, method, isPost).then(()=>{
                        this.showPreloader = false;    
                    });
                }
            }
        },
        sendDataNotifications(branch, station, data, method, isPost){
            let url2 = this.makeURL(branch, station, isPost ? 'new' : this.task.id);
            return this.$http[method](url2, data, {emulateJSON: true})
                .then(({ body }) => isPost && (this.$set(this.task, 'id', body.id)))
                .catch(err => console.log('Error in sendData(): ', err))
        }
    },
    components: {
        timeRange: require('../TimeRange'),
        snooze: require('../BranchCreation/CreateTaskFlow/Time/Snooze'),
        counter: require('../Common/Counter'),
        repeat: require('../BranchCreation/CreateTaskFlow/Time/RepeatTask'),
        smallBtn: require('../Common/SmallBtn'),
        weekDaysBar: require('../WeekDaysListChooseBar'),
        appSelect: require('../Common/Select'),
        monthChooseBar: require('../MonthChooseBar'),
        addBtn: require('../Common/ButtonPlus'),
        autocomplete: require('../Common/Autocomplete'),
        monthDaysPicker: require('../MonthDaysChooseBar'),
        preloader: require('../Common/Preloader')
    }
}
</script>
<style lang="scss" src="./style.scss" scoped></style>
<style lang="scss">
    .time-picker-wrap .popover.clockpicker-popover{
        position: static;
        max-width: none;
        box-shadow: none;
        border: none;
    }
    .time-picker-wrap .popover > .arrow {
        display: none;
    }
</style>

