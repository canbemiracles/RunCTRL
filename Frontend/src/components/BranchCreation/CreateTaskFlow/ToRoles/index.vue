<template>
<section class="slide-section-container d-flex">
  <div class="slide-content d-flex flex-column">
    <div class="row">
      <h3 class="title-header col-md-12"
          v-if="type!='notification'">Assign to Roles</h3>
      <h3 class="title-header col-md-12"
          v-else>Assign to Branches, to Stations, to Roles</h3>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="wrap"
             v-for="(item, i) in assignments"
             :key="i"
             :style="'z-index: ' + (assignments.length - i)">

          <!-- Удаление блока -->
          <a v-if="i > 0"
             href="#"
             class="remove_btn"
             @click.prevent="assignments.splice(i, 1)"
             v-text="'+'"></a>

          <div class="top">
            <div class="branch-icon"></div>
            <div class="autocomplete-row" v-if="branchesList && branchesList.length==1">{{branchesList[0].address}}</div>
            <autocomplete v-if="branchesList && branchesList.length > 1"
                          placeholder="Select branches..."
                          class="autocomplete-row"
                          :iconSearch="false"
                          dropDownClass="autocomplete-drop"
                          :suggestions="branchesList"
                          fieldName="address"
                          id="branchSelect"
                          :clearField="clearFields"
                          @clearField="clearFields=false"
                          @suggestionClick="setBranches($event, i)"
                          @noSelection="resetBranchData($event, i)" />
          </div>
          <div class="bottom">
            <div class="station d-flex input-row"
                 :class="{disabled: !assignments[i].stations.length}">
              <svg class="icon"
                   xmlns="http://www.w3.org/2000/svg"
                   viewBox="0 0 34 34"><path d="M27.1 0H6.9C5.6 0 4.6 1 4.6 2.3v29.4c0 1.3 1 2.3 2.3 2.3h20.3c1.3 0 2.3-1 2.3-2.3V2.3C29.4 1 28.4 0 27.1 0zM17 33.1c-0.8 0-1.4-0.6-1.4-1.4 0-0.8 0.6-1.4 1.4-1.4 0.8 0 1.4 0.6 1.4 1.4C18.4 32.5 17.8 33.1 17 33.1zM27.1 29.2H6.9V2.8h20.2V29.2z" fill="#8a9ea8"/></svg>
              <div class="autocomplete-row single" v-if="assignments[i].stations.length==1">{{ assignments[i].stations[0].name }}</div>
              <autocomplete v-if="assignments[i].stations.length>1" :iconSearch="false"
                            :disabled="!assignments[i].stations.length"
                            class="autocomplete-row"
                            :suggestions="assignments[i].stations"
                            fieldName="name"
                            dropDownClass="autocomplete-drop"
                            placeholder="Stations..."
                            @noSelection="resetStation($event, i)"
                            @suggestionClick="setStation($event, i)" />
            </div>
            <div class="role d-flex input-row"
                 :class="{disabled: !assignments[i].roles.length}">
                <div class="role-icon"
                    v-color:bg="assignments[i].selectedRole ? assignments[i].selectedRole.color : ''"></div>
                <div  v-if="assignments[i].roles.length==1" class="autocomplete-row role-single" >{{ assignments[i].roles[0].role }}</div>
                <autocomplete v-if="assignments[i].roles.length > 1" :iconSearch="false"
                              :disabled="!assignments[i].roles.length"
                              class="autocomplete-row"
                              dropDownClass="autocomplete-drop"
                              :suggestions="assignments[i].roles"
                              fieldName="role"
                              placeholder="Role..."
                              @noSelection="resetRole($event, i)"
                              @suggestionClick="setRole($event, i)" />
                         
            </div>
          </div>

        </div>

        <div class="add-role"
             @click="addRole"
             v-if="assignments.length < 3">
          <button-plus />
          <span v-if="type != 'notification'">Add Role</span>
          <span v-else>Add More</span>
        </div>

      </div>
    </div>
  </div>
  <recommend>
    <div slot="recommend">
      Analytics only available on paid plans. Please upgrade your account to see.
    </div>
  </recommend>
</section>
</template>

<script>
import 'images/sprites/combined-shape.svg'
import {
  mapGetters
} from 'vuex'

export default {
  props: {
    value: Array,
    type: String,
  },
  data() {
    return {
      assignments: [],
      clearFields: false
    }
  },

  watch: {
    assignments(items) {
      if (items.length) {
        this.$emit('input', items)
      }
    },
    value(value) {
      if (!value.length) {
        this.assignments = [];
        this.clearFields = true;
        this.addRole();
      }
    }
  },

  computed: {
    ...mapGetters(['branches']),
    branchesList() {
      return this.branches
        .filter(({
          geographical_area
        }) => geographical_area && geographical_area.street_address)
        .map(({
          id,
          geographical_area
        }) => {
          return {
            id,
            address: geographical_area.street_address
          }
        });
    },
  },

  methods: {
    setRole(role, i) {
      if (role) {
        this.$set(this.assignments[i], 'selectedRole', role);
      }
    },
    setStation(station, i) {
      if (station) {
        this.assignments[i].roles = station.origin_roles;
        this.$set(this.assignments[i], 'selectedStation', station.id);
        if(station.origin_roles.length==1){
          this.setRole(station.origin_roles[0], i);
        }
      }
    },
    setBranches(branches, i) {
      this.assignments[i].branches = branches.id;
      this.assignments[i].branchName = branches.address;
      this.assignments[i].stations = this.fetchStationsBranch(branches.id, i);
    },
    addRole() {
      this.assignments.push({
        roles: [],
        branches: null,
        branchName: '',
        stations: [],
        selectedRole: null,
        selectedStation: null,
        timeData:  {
          day: '',
          date: '',
          time: {
            time_open: moment().format('HH:mm:ss'),
            time_close: moment().add(30, 'minutes').format('HH:mm:ss')
          },
          viewTime: 1,
          snooze_max: 1,
          snooze_time: 60,
          repeat: false,
          repeat_unit: 1,
          repeat_subunit: 1,
          repeat_months: [],
          repeat_week: 1,
          repeat_week_days: [],
          repeat_month_days:[],
          month_days: [],
          repeat_week_days_month: [],
          repeat_week_days_year: [],
          repeat_week_days_week: [],
          repeat_week_year: 1,
          repeat_week_month: 1,
          pickedMonthRepeat: 'day',
          pickedYearRepeatWeek: false,
        }
      });
      if(this.branchesList.length==1){
        this.setBranches(this.branchesList[0], this.assignments.length-1);
      }
    },
    fetchStationsBranch(branch_id, i) {
      let branchCurrent = this.branches.filter(({id}) => id == branch_id);
      let stations = branchCurrent[0].stations;
      if(stations.length==1){
        this.setStation(stations[0], i);
      }
      return stations;
    },
    resetBranchData(event, i) {
      this.assignments[i].branches = null;
      this.assignments[i].branchName = '';
      this.assignments[i].stations = [];
      this.assignments[i].roles = [];
      this.assignments[i].selectedRole = null;
      this.assignments[i].selectedStation = null;
    },
    resetStation(event, i){
      this.assignments[i].selectedStation = null;
    },
    resetRole(event, i){
      this.assignments[i].selectedRole = null;
    }
  },

  created() {
    this.$store
      .dispatch('fetchBranches')
      .then(this.addRole)
  },

  components: {
    recommend: require('../../../SidebarRecommend'),
    autocomplete: require('../../../Common/Autocomplete'),
    buttonPlus: require('../../../Common/ButtonPlus')
  }
}
</script>

<style lang='scss' scoped>
.title-header {
    margin-bottom: 25px;

    text-align: center;

    font-size: 22px;
    color: #033040;
}
.branch-icon {
    width: 20px;
    height: 20px;
    border: 3px solid $blue;
    border-radius: 50%;
    flex-shrink: 0;
    margin-right: 10px;
    align-self: center;
}
.role-icon {
    width: 20px;
    height: 20px;
    background-color: $cancelRed;
    border-radius: 50%;
    align-self: center;
    flex-shrink: 0;
}
.wrap {
    background: #fff;
    height: 140px;
    position: relative;
    box-shadow: 0 0 10px #ccc;

    .remove_btn {
        position: absolute;
        top: 0;
        right: 10px;
        opacity: 0.5;
        color: $lightgray;
        font-family: 'Roboto-Light';
        text-decoration: none;
        font-size: 2em;
        transform: rotate(45deg);
        z-index: 20;
    }

    .icon {
        margin-right: 7px;
        align-self: center;
        width: 20px;
        height: 20px;

    }

    .icon.user_icon {
        background-color: #1dcdee;
        border-radius: 50%;
    }

    .top {
        height: 50%;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #e6ecee;
        padding-left: 30px;
    }

    .bottom {
        height: 50%;
        display: flex;
        justify-content: space-between;
        > div {
            width: 50%;
            padding-left: 30px;
            display: flex;
            align-items: center;
        }
        .station {
            line-height: 40px;
            border-right: 1px solid #e6ecee;
        }
        .role-single{
          margin-left: 10px;
          padding-left: 15px;
        }
        .single{
          padding-left: 15px;
        }
    }
}

.add-role {
    background-color: #fbfcfc;
    height: 70px;
    padding: 0 30px;
    position: relative;
    cursor: pointer;
    box-shadow: 0 0 10px #ccc;

    &:hover .add-btn {
        color: $white;
        background-color: darken(#e9eff2, 10);
    }

    line-height: 70px;
    color: #97a7af;

    .add-btn {
        display: inline-flex;
        width: 22px;
        height: 22px;
        margin-right: 7px;
        font-size: 16px;
    }
}
.autocomplete-row {
    /deep/ &.autocomplete-wrap {
        width: 100%;
        height: 100%;
        .form_input {
            width: 100%;
            height: 100%;
            border: none;
            outline: none;
            font-size: 16px;
            border-bottom: 1px solid transparent;
            &:focus {
                border-color: $blue;
            }
        }
        .search_icon {
            right: 30px;
        }
    }
    /deep/ .autocomplete-drop {
        max-height: 200px;
    }
}

.input-row.disabled {
    position: relative;
    &:after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        height: 100%;
        width: 100%;
        background-color: rgba(#ccc, .3);
        z-index: 20;
    }
    .icon {
        fill: $lightgray;
    }
    .role-icon {
        background-color: $lightgray;
    }
}
</style>
