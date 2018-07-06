<template>
<div>
  <div class="profile_wrapper">
    <div class="profile">
      <div class="profile__left">

        <div class="photo">
          <div v-if="employee.avatar"
               :style="{ backgroundImage: `url(${$http.options.root}/${employee.avatar.path}`}" />
          <div v-else
               :style="{ backgroundImage: 'url(' + require('images/user_default.jpg')+')'}" />
        </div>

        <div class="actions">
          <button @click.prevent="showDeletePopup = true"
                  :class="{ active: showDeletePopup }">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M381.6 65.5h-53.9V54C327.7 24.2 303.5 0 273.7 0h-35.3c-29.8 0-54 24.2-54 54v11.5h-53.9c-34.8 0-63 28.3-63 63v16H444.7v-16C444.7 93.8 416.4 65.5 381.6 65.5zM297.3 65.5H214.7V54c0-13 10.6-23.6 23.6-23.6h35.3c13 0 23.6 10.6 23.6 23.6V65.5z" class="a"/><path d="M88.5 175v306.1c0 17.1 13.9 30.9 30.9 30.9h273.2c17.1 0 30.9-13.9 30.9-30.9V175H88.5zM197.5 466.2h-30.4V220.8h30.4V466.2zM271.2 466.2h-30.4V220.8h30.4V466.2zM344.9 466.2h-30.4V220.8h30.4V466.2z"/></svg>
            </button>
          <button @click.prevent="showEmployeeDialog = true"
                  :class="{ active: showEmployeeDialog }">
<!--             @click.prevent="showPersonalInfo = !showPersonalInfo"
              :class="{ active: showPersonalInfo }" -->
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 350 350"><path d="M175 171.2c38.9 0 70.5-38.3 70.5-85.6C245.5 38.3 235.1 0 175 0s-70.5 38.3-70.5 85.6C104.5 132.9 136.1 171.2 175 171.2z"/><path d="M41.9 301.9C41.9 299 41.9 301 41.9 301.9L41.9 301.9z"/><path d="M308.1 304.1C308.1 303.3 308.1 298.6 308.1 304.1L308.1 304.1z"/><path d="M307.9 298.4c-1.3-82.3-12.1-105.8-94.4-120.7 0 0-11.6 14.8-38.6 14.8s-38.6-14.8-38.6-14.8c-81.4 14.7-92.8 37.8-94.3 118 -0.1 6.5-0.2 6.9-0.2 6.1 0 1.4 0 4.1 0 8.7 0 0 19.6 39.5 133.1 39.5 113.5 0 133.1-39.5 133.1-39.5 0-3 0-5 0-6.4C308.1 304.6 308 303.7 307.9 298.4z"/></svg>
            </button>
        </div>

      </div>

      <div class="profile__right">

        <h4>{{ employee.first_name }}  {{employee.last_name}}</h4>

        <div class="role-head">
          <h6 class="assign-to-roles">Assigned to Roles</h6>
        </div>

        <div class="d-flex flex-wrap roles-wrap">
          <roles-item v-for="role in assignedRoles"
                      :key="role.id"
                      :name="role.role"
                      :remove="true"
                      :color="role.color ? role.color : false"
                      @remove="removeRole(role)" />
          <app-multi-select class="add-role" 
              dropDownClass="roles-dropdown" 
              valueFieldName="id"
              textFieldName="role"
              align="left"
              :options="roles"
              :selectedOptions="assignedRoles.map(({id}) => id)"
              @changeActiveSelect="addRole"
              :roleIcon="true"
              >
                <div slot="no-data">{{ assignedBranches.length ? 'No roles in selected branches' : 'Please select branches first' }}</div> 
          </app-multi-select>
        </div>

        <template v-if="employee.branches">
            <h6>Assigned to Branches</h6>

            <badge
              v-for="(branch, index) in assignedBranches" :key="branch.id"
              v-if="branch.geographical_address && branch.geographical_address.street_address"
              :text="branch.geographical_address.street_address"
              @close="removeBranch(branch, index)">
            </badge>
            <button-plus
              v-if="!showAddBranch"
              class="btn_plus_small"
              @click="showAddBranch = true"
            />

            <autocomplete
              v-if="showAddBranch"
              placeholder="Select branch"
              :options="branchesToSelect"
              field-name="text"
              @select="addBranch"
            />
          </template>

      </div>
    </div>

    <div class="counters">
      <counter label="Worked this month"
               :count="$moment(data.total_work_time) | moment('HH:MM')" />
      <counter label="Avg. hours"
               :count="$moment(data.avg_worked_time) | moment('HH:MM')" />
      <counter label="Shifts"
               :count="data.total_shifts" />
      <counter label="Salary"
               :count="Math.round(data.total_salary)"
               :sub="`${Math.round(data.hourly_rate)}/h`" />

      <donut-chart :values="tasksCounts"
                   id="all">
        <div class="total">
          <span>{{ totalTasks }}</span>
          <p>TASKS</p>
        </div>
      </donut-chart>
      <b-popover v-if="allTasks && allTasks.length"
                 target="all"
                 triggers="hover focus"
                 placement="bottom"
                 class="pop">
        <ul class="tasks">
          <li v-for="item in allTasks"
              :class="item.type ? item.type : 'problems'">{{item.start_time | moment('HH:MMA')}} {{item.title}}</li>
        </ul>
      </b-popover>

      <counter label="Completed"
               label-color="green"
               :count="data.total_done_tasks"
               id="completed" />
      <b-popover v-if="data.completed_tasks && data.completed_tasks.length"
                 target="completed"
                 triggers="hover focus"
                 placement="bottom"
                 class="pop">
        <ul class="tasks">
          <li v-for="item in data.completed_tasks"
              class="completed">{{item.start_time | moment('HH:MMA')}} {{item.title}}</li>
        </ul>
      </b-popover>
      <counter label="Pending"
               label-color="yellow"
               :count="data.total_pending_tasks"
               id="pending" />
      <b-popover v-if="data.pending_tasks && data.pending_tasks.length"
                 target="pending"
                 triggers="hover focus"
                 placement="bottom"
                 class="pop">
        <ul class="tasks">
          <li v-for="item in 8"
              class="pending">{{item.start_time | moment('HH:MMA')}} {{item.title}}</li>
        </ul>
      </b-popover>
      <counter label="Problems"
               label-color="red"
               :count="data.total_problem_tasks"
               id="problems" />
      <b-popover v-if="data.problem_tasks && data.problem_tasks.length"
                 target="problems"
                 triggers="hover focus"
                 placement="bottom"
                 class="pop">
        <ul class="tasks">
          <li v-for="item in data.problem_tasks"
              class="problems">{{item.start_time | moment('HH:MMA')}} {{item.title}}</li>
        </ul>
      </b-popover>

    </div>
  </div>


  <confirm :active="showDeletePopup"
           title="Delete employee"
           @confirm="deleteEmployee"
           @close="showDeletePopup = false">
    <p>Are you sure you want to delete employee?</p>
  </confirm>

  <edit-employee :show.sync="showEmployeeDialog"
                 :editEmployee="employee" @updateData="updateEmployeeData" />


</div>
</template>

<script>
import {
  mapGetters, mapActions
} from 'vuex'

export default {

  props: {
    id: {
      required: true
    },
    data: {
      required: true
    }
  },

  data() {
    return {
      showPersonalInfo: false,
      showDeletePopup: false,
      showAddBranch: false,
      showEmployeeDialog: false,
      showAddRole: false,
      roles: [],
      employee: {},
      assignedBranches: [],
      assignedRoles: [],
      branchesToSelect: []
    }
  },

  computed: {
    ...mapGetters(['branches', 'employees']),

    tasksCounts() {
      let arr = [this.data.total_done_tasks, parseInt(this.data.total_pending_tasks), this.data.total_problem_tasks]
      return arr
    },

    totalTasks() {
      return this.data.total_done_tasks + parseInt(this.data.total_pending_tasks) + this.data.total_problem_tasks
    },

    allTasks() {
      if (this.data && this.data.completed_tasks && this.data.pending_tasks && this.data.problem_task) {
        // this.data.problem_task.forEach((item, i, arr) => arr[i].type = 'problems')
        // this.data.completed_tasks.forEach((item, i, arr) => {if (arr[i]) arr[i].type = 'completed'})
        // this.data.pending_tasks.forEach((item, i, arr) => {if (arr[i]) arr[i].type = 'pending'})
        return this.data.completed_tasks.concat(this.data.pending_tasks, this.data.problem_task)
          .sort((a, b) => (a.start_time > b.start_time) ? 1 : ((b.start_time > a.start_time) ? -1 : 0))
          .slice(0, 10)
      } else {
        return []
      }

    }
  },

  methods: {
    ...mapActions(['fetchBranches']),
    deleteEmployee() {
      this.$http.delete(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}`)
        .then(
          responce => this.$router.push({
            path: `/employees-list`
          }),
          responce => {
            console.error(responce.statusText, responce.status)
          }
        )
    },
    updateEmployeeData(data){
      this.employee = data;
      this.assignedRoles = data.roles_info.map((item) => {
        return {
          color: item.color,
          id: item.id,
          branch_id: item.id_branch,
          role: item.role
        }
      });
      this.assignedBranches = data.branches;
      this.setBranchesToSelect();
    },
    getRoles() {
      this.roles=[];
      this.assignedBranches.forEach(branch=>{
          branch.stations.forEach(station=>{
            this.roles = [...this.roles, ...station.origin_roles]
          });
      });
    },
    addBranch(item) {
      this.assignedBranches.push({
        geographical_address: {
          street_address: item.text
        },
        id: item.id,
        stations: item.stations
      });
      this.branchesToSelect = this.branchesToSelect.filter((branch)=> branch.id != item.id);
      this.getRoles();
      this.showAddBranch = false;
      this.patchBranch()
    },
    patchBranch() {
      let employee = {
        branches: this.assignedBranches.map(({id}) => id)
      }
      if(employee.branches)
      this.$http.patch(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}`, employee)

    },

    patchRole() {
      let employee = {
        roles: this.assignedRoles.map(({
          id
        }) => id)
      }
      this.$http.patch(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}`, employee)
    },
    patchBranchAndRole(){
      let employee = {
        roles: this.assignedRoles.map(({id}) => id),
        branches: this.assignedBranches.map(({id}) => id)
      }
      this.$http.patch(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}`, employee)
    },
    addRole(role) {
      if (role[0]) {
        this.assignedRoles.push(role[1]);
        this.$set(this.employee, 'roles_info', this.assignedRoles);
        this.patchRole();
      } else {
        this.removeRole(role[1]);
        this.patchRole();
      }
    },
    removeRole(role) {
      this.assignedRoles.splice(this.assignedRoles.indexOf(this.assignedRoles.find(item => item.id === role.id)), 1);
      this.$set(this.employee, 'roles_info', this.assignedRoles);
      this.patchRole();
    },
    removeBranch(branch, index){
      this.assignedBranches.splice(index, 1);
      this.branchesToSelect.push({
        id: branch.id,
        stations: branch.stations,
        text: branch.geographical_address.street_address
      });
      this.assignedRoles = this.assignedRoles.filter((item) => item.branch_id != branch.id);
      let detachBranch = false;
      if(this.assignedBranches.length==0){
        this.roles=[];
        this.assignedRoles = [];
        detachBranch = branch.id;
      }else{
        this.removeBranchRoles(branch.id);
       
      }
      this.patchBranchAndRole(detachBranch);
    },
    removeBranchRoles(branchId){
      this.roles = this.roles.filter(({branch_id})=> branch_id != branchId);
    },
    setBranchesToSelect() {
      this.branchesToSelect = _.differenceBy(this.branches, this.assignedBranches, 'id');
      this.branchesToSelect = this.branchesToSelect
        .filter(branch => branch.geographical_area && branch.geographical_area.street_address)
        .map(branch => ({
          text: branch.geographical_area.street_address,
          id: branch.id,
          stations: branch.stations
        }))
    },
  },

  created() {
    this.fetchBranches().then(()=>{
      this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.id}`)
        .then(res => {
          this.employee = res.body;
          this.employee.family_situation = this.employee.family_situation 
            ? this.employee.family_situation 
            : { family_status: 'Family Status' };
          this.assignedRoles = this.employee.roles_info.map((item) => {
            return {
              color: item.color,
              id: item.id,
              branch_id: item.id_branch,
              role: item.role
            }
          });
          this.employee.branches.forEach(el =>{
            let branch = this.branches.find(item=>{
              return item.id == el.id
            });
            this.assignedBranches.push({
              geographical_address: el.geographical_address,
              id: el.id,
              stations: branch.stations
            });
          });
          this.employee.branches = this.assignedBranches; // add stations info
          this.setBranchesToSelect();
          this.getRoles();
        })
    });
  },

  components: {
    rolesItem: require('../../BranchCreation/BranchFlow/SetupStations/RolesList/RolesItem'),
    buttonPlus: require('../../Common/ButtonPlus'),
    badge: require('../../Common/Badge'),
    autocomplete: require('../../Common/Autocomplete2'),
    counter: require('../../Common/LabelCount'),
    donutChart: require('../../Common/DonutChart'),
    closeBtn: require('../../Common/CloseBtn'),
    confirm: require('../../Common/Confirm'),
    smallBtn: require('../../Common/SmallBtn'),
    editEmployee: require('../../EmployeesList/CreateEmployee'),
    appMultiSelect: require('../../Common/MultiSelect2')
  }
}
</script>

<style lang="scss" src="./style.scss" scoped>
</style > <style lang="scss" >
.role-item {
  .roles-label {
    overflow: visible !important;
    text-overflow: clip !important;
      // white-space: nowrap;
  }
}

.popover-body {
    padding: 10px;
    box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
    background-color: #ffffff;
    border: none;
    border-radius: none;
    outline: none;
    overflow: hidden;
    width: 340px;
    li {
        height: 40px;
        border-radius: 2px;
        color: #505d63;
        font-size: 15px;
        font-weight: 500;
        margin: 3px 0;
        line-height: 40px;
        padding-left: 10px;
        overflow: hidden;
        white-space: nowrap;
        text-overflow: ellipsis;
    }
    .completed {
        background-color: #e4ffd8;
    }
    .pending {
        background-color: #fff6d7;
    }
    .problems {
        background-color: #ffecec;
    }
}

.tasks {
    width: 100%;
    list-style: none;
    padding-left: 0;
    margin-bottom: 0;
}
</style>
