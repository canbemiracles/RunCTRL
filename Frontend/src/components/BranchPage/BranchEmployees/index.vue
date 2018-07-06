<template>
    <section class="branch-employees">
        <div class="section-title d-flex">
            <h2 class="heading-2">Branch Employees
                <span class="amount-employees">({{employeersList ? employeersList.length : '0'}})</span>
            </h2>
            <svg @click="collapseSection = !collapseSection" class="collapse-arrow" :class="{ active: !collapseSection}" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1395 736q0 13-10 23l-466 466q-10 10-23 10t-23-10l-466-466q-10-10-10-23t10-23l50-50q10-10 23-10t23 10l393 393 393-393q10-10 23-10t23 10l50 50q10 10 10 23z" fill="#97a7af"/></svg>
            <app-multi-select v-if="selectOptions" class="select-employees" 
            dropDownClass="employees-dropdown" 
            :suggestions="selectOptions"
            @changeSelectedItems="changeSelectedItems"
            fieldName="text"
            id="searchInputSelect"
            >
            <div class="create-employee-btn" slot="lastField" @click="showEmployeeDialog = true">
                <span class="icon-add-plus">+</span> Create New User
            </div>
            </app-multi-select>
        </div>
        <transition-group v-if="collapseSection" tag="div" name="fade"  class="employees-list d-flex flex-wrap">
            <role v-for="(employee, index) in employeersList" :key="index" :role="employee" 
            :type="'employee'" :selected="employee.shiftDone" :employee_work_time="employee.work_time"></role>
        </transition-group>
        <!-- <div class="view-more d-flex">
            <router-link :to="{name: 'employeesList'}" class="view-all">View All Employees</router-link>           
        </div> -->
        <create-employee :show.sync="showEmployeeDialog"
                   @updateList="updateEmplList" />
    </section>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
export default {
    props: {
        employeersList: Array,
        branchId: Number
    },
    data: function(){
        return {
            selectedEmployeers: [],
            selectOptions: [],
            showEmployeeDialog: false,
            collapseSection: true
        }
    },
    
    created(){
        this.fetchEmployees();
    },
    mounted(){
        this.getLiveDataEmployeesShift(this.branchId);
        let unwatch = this.$watch('employeersList', function(value){
            if(!$.isEmptyObject(value)){
                this.initSelectedItems();
                this.$watch('employeesLiveData', function(livedata){
                    if(livedata &&  livedata.length){
                        this.initShiftsDoneItems();
                    }
                }, { immediate: true });
            }
        }, {immediate: true, deep: true});
        let unwatchEmpl = this.$watch('employees', function(value){
            if(!$.isEmptyObject(value)){
                this.getSelectOptions();
            }
        }, {immediate: true});
        
    },
    computed:{
        ...mapGetters(['employees', 'employeesLiveData']),
    },
    methods:{
        ...mapActions(['fetchEmployees', 'getLiveDataEmployeesShift']),
        changeSelectedItems(selected, currentSelect){
            this.selectedEmployeers = selected.map(item => item.id);
            if(currentSelect[0]){
                //request for attach
                this.$emit('attachEmployeeToBranch', currentSelect[1].id)
            }else{
                //request for dettach
                this.$emit('detachEmployeeToBranch', currentSelect[1].id)
            }
        },
        initSelectedItems(){
            let initIDs = this.employeersList.map(item => item.id);
            this.selectedEmployeers = initIDs;
        },
        initShiftsDoneItems(){
            if(this.employeesLiveData && this.employeesLiveData.length){
                for (let employee of this.employeersList){
                    for(let empl of this.employeesLiveData){
                        if(empl.history && (employee.id == empl.history.employee.id)){
                            this.$set(employee, 'shiftDone',  true);

                            let time_open = empl.history.date_start;
                            let time_close = empl.history.date_end;
                            let durationWork = new Date(time_close).getTime() - new Date(time_open).getTime();
                            durationWork = moment.duration(durationWork, "milliseconds").format("HH:mm", {
                                trim: false
                            });
                            this.$set(employee, 'work_time',  durationWork);
                            this.$set(employee, 'ready',  empl.done_tasks);
                            this.$set(employee, 'pending',  empl.pending_tasks);
                            this.$set(employee, 'cancel',  empl.problem_tasks);
                        }
                    }
                    if(!employee.shiftDone) {
                        this.$set(employee, 'shiftDone',  false); 
                    }
                }
            }
        },
        checkSelected(id){
            if(this.selectedEmployeers.indexOf(id)!= -1){
                return true;
            }else{
                return false;
            }
        },
        getSelectOptions(){
            let options=[];
            if(this.employees.items.length){
                this.employees.items.forEach(element => {
                    options.push({
                        value: element.id,
                        icon: element.avatar ? this.$http.options.root + '/' + element.avatar.path : 'src/assets/images/user_default.jpg',
                        text: `${element.first_name}  ${element.last_name}`,
                        id: element.id,
                        selected: this.checkSelected(element.id)
                    })
                });
            this.selectOptions = options;
            }
        },
        updateEmplList(){

        }
    },
    components:{
        role: require('../Roles'),
        appMultiSelect: require('../../Common/MultiSelect'),
        createEmployee: require('../../EmployeesList/CreateEmployee')
    },
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
<style>
.employees-dropdown{
    max-height: 385px;
}
</style>

