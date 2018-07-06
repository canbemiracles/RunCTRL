<template>
  <div class="container-fluid d-flex flex-column p-0 branchpage-container">
    <app-header :reports="16"></app-header>
    <section class="map-container">
      <google-map 
        :name="name" 
        :address="branchData ? branchData.geographical_area : null"></google-map>
      <back class="back" v-if="branches.length > 1"></back>
      <topInfo v-if="branchData" 
        :geo="branchData.geographical_area"
        :manager="branchData.branch_manager"
        :supervisor="branchData.supervisor"
        :firePhone="branchData.fire_phone"
        :ambulancePhone="branchData.ambulance_phone"
        :policePhone="branchData.police_phone"
        :branchId="currentBranchId" 
        ></topInfo>
    </section>
    <calendar
      :branchId="currentBranchId"
    ></calendar>
    <section class="roles">
      <div class="section-title d-flex">
        <h2 class="heading-2">Roles</h2>
        <svg @click="collapseSectionRoles = !collapseSectionRoles" class="collapse-arrow" :class="{ active: collapseSectionRoles}" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1395 736q0 13-10 23l-466 466q-10 10-23 10t-23-10l-466-466q-10-10-10-23t10-23l50-50q10-10 23-10t23 10l393 393 393-393q10-10 23-10t23 10l50 50q10 10 10 23z" fill="#97a7af"/></svg>
      </div>
      <div class="roles-list d-flex flex-wrap" v-if="!collapseSectionRoles">
        <role
          v-for="(role, index) in branchRoles"
          :key="index"
          :role="role"
          type="role"
        ></role>  
      </div>
    </section>
    <branch-employees 
      :employeersList="employeersList"
      @attachEmployeeToBranch="attachEmployee"
      @detachEmployeeToBranch="detachEmployee"
      :branchId="currentBranchId"
    ></branch-employees>
    <div class="section-analytics-wrap">
      <analytics v-if="plan !='free' && info" :info="info" :branchID="id"></analytics>
      <template v-if="plan =='free'">    
        <div class="blur analytics"></div>
        <div class="section-content">
          <h2 class="heading-2 no-transform">Want to see your company analytics?</h2>
          <div class="describe-txt">Analytics only available on paid plans. Please upgrade your account to see.</div>
          <router-link :to="{name: 'Registration4'}" tag="button" class="small-btn submit-btn" >Upgrade Plan</router-link>
        </div>
      </template>
    </div>
    <div class="reports-wrap">
      <reports :functionsBar="false"
      :branchID="id"
      @checkBranchStatus="showBanchStatus"
      @getBranchLiveData="setInfoData"
      ></reports>
      <branch-status-pop-up :branchData="branchData"></branch-status-pop-up>  
    </div>
    <div class="section-analytics-wrap" :class="{ graph : plan=='free' }">
      <income v-if="plan !='free'" :brnachId="id"></income>
      <template v-else>    
        <div class="blur graph"></div>
        <div class="section-content">
          <h2 class="heading-2 no-transform">Want to see your company analytics?</h2>
          <div class="describe-txt">Analytics only available on paid plans. Please upgrade your account to see.</div>
          <router-link :to="{name: 'Registration4'}" tag="button" class="small-btn submit-btn">Upgrade Plan</router-link>
        </div>
      </template>
    </div>
    <app-footer></app-footer>
  </div>
</template>
<script>
import { $eventBus } from '../../main.js';
import {mapActions, mapGetters} from 'vuex';
import timesFunctions from '../Common/Mixins/timesFunctions'
import {loadJs} from '../Common/utils.js'
export default {
  mixins: [timesFunctions],
  name: 'branch',
  props: ['id'],
  data: function() {
    return {
      name: 'map',
			branchData: null,
      info: null,
      branchRoles: [],
      plan: 'free',
      updateRolesData: 30000,
      intervalRolesUpadte: null,
      collapseSectionRoles: false
    }
  },
  computed:{
      ...mapGetters(['roles', 'apiKey', 'language', 'branches']),
      employeersList(){
        if(this.branchData && this.branchData.employees.length){
            return this.branchData.employees
        }
      },
      currentBranchId(){
        return  parseInt(this.id);
      }
  },
  created(){
      this.fetchRoles(this.currentBranchId);
      this.getBranchData();
  },
  methods: {
    ...mapActions(['fetchRoles', 'fetchBranchById', 'attachEmployeeToBranch', 'detachEmployeeBranch']),
    getBranchData(){
			this.fetchBranchById(this.currentBranchId).then(res=>{
				this.branchData = res;
        this.weekStart = res.company.week_start_on;
        this.plan = res.company.plan;
			});
		},
    getBranchRoles(){
      let rolesList=[];
      this.roles.forEach(role=>{
        if(role.shift){
          let date_start, 
              date_end,
              first_name,
              last_name,
              avatar;
          
          rolesList.push({
            id: role.id,
            color: role.color,
            role: role.name,
            first_name: role.shift.employee.first_name,
            last_name: role.shift.employee.last_name,
            avatar: role.shift.employee.avatar,
            time_open: role.shift.branch_shift.time_open,
            time_close: role.shift.branch_shift.time_close,
            date_start: role.shift.date_start,
            date_end: role.shift.date_end,
            ready: role.done_tasks,
            pending: role.pending_tasks,
            cancel: role.problem_tasks,
            hourly_rate: role.shift.employee.hourly_rate,
            problem_reports: role.problem_reports
          })
        }else{
          rolesList.push({
            id: role.id,
            role: role.name,
            color: role.color,
            ready: role.done_tasks,
            pending: role.pending_tasks,
            cancel: role.problem_tasks,
          })
        }
      });
      this.branchRoles = rolesList;
    },

    attachEmployee(employee_id){
      this.attachEmployeeToBranch({
        branch_id: this.currentBranchId,
        employee_id
      }).then(()=>{
        this.getBranchData();
      });
    },
    detachEmployee(employee_id){
      this.detachEmployeeBranch({
        branch_id: this.currentBranchId,
        employee_id
      }).then(()=>{
        this.getBranchData();
      });
    },
    showBanchStatus(){
      this.$root.$emit('bv::show::modal','branchStatus')
    },
    setInfoData(data){
      this.info = data;
    }
  },
  mounted() {
    if(!window.google){
       loadJs(`https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&language=${this.language}&region=${this.language}`, function() {
        $eventBus.$emit('googleload');
      });
    }else{
      $eventBus.$emit('googleload');
    }
   
    let unwatchRoles = this.$watch('roles', (value)=>{
      if(value.length){
        this.getBranchRoles();
      }
    });
    this.intervalRolesUpadte = setInterval(()=>{
			this.fetchRoles(this.currentBranchId);
		}, this.updateRolesData);
  },
  beforeDestroy(){
    clearInterval(this.intervalRolesUpadte);
  },
  components: {
    appHeader: require('../Header'),
    appFooter: require('../Footer'),
    googleMap: require('../GoogleMap'),
    back: require('./Back'),
    topInfo: require('./TopInfo'),
    calendar: require('../Schedule'),
    role: require('./Roles'),
    branchEmployees: require('./BranchEmployees'),
    analytics: require('../DashboardPage/InfoPanel'),
    reports: require('../DashboardPage/Reports'),
    income: require('../DashboardPage/Income'),
    branchStatusPopUp: require('../ReportPopups/BranchStatus')
  },

}
</script>

<style lang="scss" src="./style.scss" scoped ></style>