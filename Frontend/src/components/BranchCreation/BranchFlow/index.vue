<template>
<div class="slide-section">
  <shifts-block :shifts="branchData && branchData.shifts" @deleteBranch="showModal"></shifts-block>
  <stationsBlock :stations="branchData && branchData.stations"
                 @saveStation="saveOneStation"
                 @editStation="editStation"
                 :branchId="branchId"></stationsBlock>
  <company-size v-if="!id"
                @saveTemplates="saveTemplates"
                :isSendData="isSendDataTemplate"
                @complete="complete"
                @saveAndCreate="saveAndCreate"
                :branchCount="branchCount">
  </company-size>
  <branch-data @createBranch="saveBranch"
               :branchId="branchId"
               :branchData="branchData"></branch-data>
  <!-- Modal Component -->
  <b-modal id="modalError"
           centered
           :ok-only="true"
           :no-close-on-backdrop="true"
           :no-close-on-esc="true"
           header-class="error-header">
    <div slot="modal-header"
         class="modal-title">
      <svg class="warning-icon"
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="42"
           height="42"
           viewBox="0 0 42 42"><defs><path id="63g8a" d="M117 781a20 20 0 1 1 40 0 20 20 0 0 1-40 0z"/><path id="63g8b" d="M135 783v-12h4v12zm0 7v-4h4v4z"/></defs><g><g transform="translate(-116 -760)"><use fill="#fff" fill-opacity="0" stroke="#f53f3f" stroke-miterlimit="50" stroke-width="1.5" xlink:href="#63g8a"/></g><g transform="translate(-116 -760)"><use fill="#f53f3f" xlink:href="#63g8b"/></g></g></svg>
      <span class="warn-title"> Warning! </span>
    </div>
    <p class="my-4">{{ errorMessage }}</p>
    <div slot="modal-footer"></div>
  </b-modal>
  <!-- preloader -->
  <preloader class="branch-preloader"
             :show="showLoader"></preloader>
  <b-modal id="confirmDelBranch" centered>
    <div slot="modal-header" class="modal-title d-flex">
        <div class="icon-warning-wrap">
            <svg width="42" class="warning-icon">
                <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="42" height="42" viewBox="0 0 42 42"><defs><path id="63g8a" d="M117 781a20 20 0 1 1 40 0 20 20 0 0 1-40 0z"/><path id="63g8b" d="M135 783v-12h4v12zm0 7v-4h4v4z"/></defs><g><g transform="translate(-116 -760)"><use fill="#fff" fill-opacity="0" stroke="#f47070" stroke-miterlimit="50" stroke-width="1.5" xlink:href="#63g8a"/></g><g transform="translate(-116 -760)"><use fill="#f47070" xlink:href="#63g8b"/></g></g></svg>
            </svg>
        </div>
        <span class="warn-title">Confirm delete </span>
        <button type="button" aria-label="Close" class="close" @click="hideModal">×</button>
    </div>
    <p class="my-4">Are you sure you want to delete this branch?</p>
    <div class="modal-buttons" slot="modal-footer">
        <button class="confirm-btn" @click="deleteBranch">ok</button>
        <button class="cancel-btn" @click="hideModal">Cancel</button>
    </div>
  </b-modal>           
</div>
</template>
<script>
import scroll from './../../Common/Mixins/windowScroll';
import { mapActions, mapGetters, mapMutations, mapState } from 'vuex';
import { $eventBus } from '../../../main';
export default {
  mixins: [scroll],
  props: ['id'],
  data: function() {
    return {
      branchId: null,
      branchCount: 1,
      foundShiftsError: false,
      isSendDataTemplate: false,
      errorMessage: '',
      validShiftName: false,
      branchData: null,
      showLoader: false
    }
  },
  mounted() {
    $eventBus.$on('validateShiftName', (valid) => {
      if (!valid) {
        this.foundShiftsError = true;
        this.setValid(false);
      } else {
        this.validateShifts(this.shift_groups);
      }
    });
  },
  computed: {
    ...mapGetters(['shift_groups', 'shifts', 'forDeleteArr', 'stationList', 'getStation', 'branches']),
    ...mapState({
      shiftupdateMode: (state) => (state.shifts.updateMode),
    }),
  },
  watch: {
    //check valid state of BranchShifts
    shifts: {
      handler() {
        if(this.branchId){
          this.setShiftsUpdateMode(true);
        }
      },
      deep: true
    },
    shift_groups: {
      handler: function(value) {
        this.validateShifts(value);
      },
      deep: true,
      immediate: true
    },
    stationList: {
      handler: function(value) {
        if (this.currentSlide == 1) {
          this.validateStations(value);
        }
      },
      deep: true,
      immediate: true
    },
    currentSlide(newValue, oldValue) {
      //send request for BranchShifts
      if (oldValue == 0) {
        //create empty Branch
        if (!this.branchId) {
          this.saveBranchShifts()
        } else if (this.shiftupdateMode) {
          this.sendShiftRequest();
          this.setShiftsUpdateMode(false);
        }
      }
      if (newValue == 1) {
        this.validateStations(this.stationList);
      }
    },
    id: {
      handler(value) {
        if (value) {
          this.branchId = +value;
          this.fetchBranchById(this.branchId)
            .then((branchData) => {
              this.branchData = branchData;
              this.setValidateArr({
                index: this.total - 1,
                val: true
              });
            });
        }
      },
      immediate: true
    }
  },
  components: {
    shiftsBlock: require('./BranchShifts'),
    stationsBlock: require('./SetupStations'),
    companySize: require('./CompanySize'),
    branchData: require('./BranchData'),
    preloader: require('../../Common/Preloader')
  },
  methods: {
    ...mapMutations(['setValidateArr', 'setValidate', 'replaceShiftGroup',
      'setShiftDayId', 'setShiftsUpdateMode', 'setLoadStatus', 'setErrorMessage'
    ]),
    ...mapActions(['addBranch', 'createBranch', 'createQR', 'getPIN', 'initEmptyShiftGroup', 'clearStationsList', 'fetchBranchById']),
    ...mapActions({
        deleteBranchRequest : 'deleteBranch'
    }),
    deleteBranch(){
        this.deleteBranchRequest(this.$route.params.id);
        this.$router.push({name: 'dashboard'});
    },
    showModal(){
        this.$root.$emit('bv::show::modal','confirmDelBranch');
    },
    hideModal(){
          this.$root.$emit('bv::hide::modal','confirmDelBranch');
    },
    setValid(value) {
      this.setValidateArr({
        index: this.currentSlide + 1,
        val: value
      });
      this.setValidate(value);
    },
    watchShiftsUpdate() {
        
    },
    validateShifts(value) {
      this.foundShiftsError = false;
      if (this.shifts.length == 0) {
        this.foundShiftsError = true;

      }
      this.shifts.forEach(element => {
        if (element.error) {
          this.foundShiftsError = true;
        }
      });
      value.forEach(shift => {
        if (!shift.name) {
          this.foundShiftsError = true;
        }
      });
      if (this.foundShiftsError) {
        this.setValid(false);
      } else {
        this.setValid(true);
      }
    },
    validateStations(value) {
      let empty = true;
      value.forEach(item => {
        if (item.name) {
          empty = false;
        }
      });
      if (empty) {
        this.setValid(false);
      } else {
        this.setValid(true);
      }
    },
    hideModal() {
      this.$refs.modalError.hide();
    },
    saveBranch(data) {
      data.branchId = this.branchId;
      this.createBranch(data);
      if (!this.id) {
        this.$router.push({
            name: "branchPage",
            params: { id: this.branchId }
        });
      } else {
        this.$router.push({name: "branchList"});
      }

    },
    saveAndCreate() {
      this.branchId = null;
      this.branchCount++;
      this.replaceShiftGroup([]);
      this.initEmptyShiftGroup();
      this.clearStationsList();
      $eventBus.$emit('clearData');
      this.moveUpTo(0);
    },
    sendShiftRequest(dublicate) {
      let that = this;
      let listBusyDays;
      return new Promise(function(resolve, reject) {
        var last = Promise.resolve();
        that.shifts.forEach((shift, index) => {
          last = last.then(() => {
            let shiftsdata = {};
            that.shift_groups.forEach(elem => {
              if (elem.shift_id == shift.shift_id) {
                listBusyDays = elem.listBusyDays;
                elem.time_rows.forEach(item => {
                  if (item.timeRow_id == shift.timeRow_id) {
                    shiftsdata = {
                      time_close: item.time_close,
                      time_open: item.time_open
                    };
                  }
                });
                shiftsdata.name = elem.name;
              }
            });
            shiftsdata.shift_day = shift.shift_day;
            if (shift.id && !dublicate) {
              that.$http.patch(`api/v1/branches/${that.branchId}/shifts/${shift.id}`,
                  shiftsdata, {
                    emulateJSON: true
                  })
                .then(() => {
                  resolve(true);
                });
            } else {
              that.$http.post(`api/v1/branches/${that.branchId}/shifts/new`,
                  shiftsdata, {
                    emulateJSON: true
                  })
                .then(response => {
                  that.setShiftDayId({
                    index: index,
                    id: response.body.id
                  });
                  listBusyDays.forEach(day=>{
                    if(day.rowId == shift.timeRow_id && day.day == shift.shift_day){
                      that.$set(day, 'savedId', response.body.id);
                    }
                  });
                  resolve(true);
                });
            }
          });
        });
        last.then(function() {
          if (that.forDeleteArr.length) {
            that.forDeleteArr.forEach(id => {
              that.$http.delete(`api/v1/branches/${that.branchId}/shifts/${id}`);
            });
          }
          resolve();
        });
      });
    },
    saveOneStation(data) {
      console.log('saveOneStation', this.branchId);
      let branchId = this.branchId;
      if (branchId) {
        this.$http.post(`api/v1/branches/${branchId}/stations/new`, {
            name: data.name
          }, {
            emulateJSON: true
          })
          .then(response => {
            let station_id = response.body.id;
            let stationItem = this.getStation(data.id);
            this.$set(stationItem, 'id', station_id);

            let send_data = {
                branch_id: branchId,
                station_id: station_id
              },
              qr, pin,
              that = this,
              new_station = this.getStation(station_id);
            let promiseQR = this.createQR(send_data)
              .then(response => {
                qr = response;
                this.$set(new_station, 'qr', response);
              });
            let promisePIN = this.getPIN(send_data)
              .then(response => {
                pin = response;
                this.$set(new_station, 'pin', response);
              })
            if (new_station.roles && new_station.roles.length) {
              new_station.roles.forEach((element, index) => {
                that.saveStationsRoles(element, send_data.branch_id, send_data.station_id, index);
              });
            }
            Promise.all([promiseQR, promisePIN])
              .then(() => {
                $eventBus.$emit('stationSaved', {
                  id: station_id,
                  qr,
                  pin
                });
              });
          });
      }

    },
    saveStations() {
      //сохраняем дубликаты станций для новой ветки
      let that = this;
      let branchId = that.branchId;
      return new Promise(function(resolve, reject) {
        var last = Promise.resolve();
        that.stationList.forEach((item, index) => {
          if (item.name) {
            let data = {
              name: item.name
            }
            last = last.then(() => {
              return new Promise(function(resolve, reject) {
                that.$http.post(`api/v1/branches/${branchId}/stations/new`, data, {
                    emulateJSON: true
                  })
                  .then(response => {
                    let station_id = response.body.id;
                    that.getStation(item.id)
                      .id = station_id;
                    if (item.roles.length) {
                      var promises = [];
                      item.roles.forEach((element, index) => {
                        promises.push(that.saveStationsRoles(element, branchId, station_id, index, true));
                      });
                      return Promise.all(promises)
                        .then(() => {
                          resolve()
                        });
                    }
                  });
              });
            });
          }
        });
        last.then(function() {
          resolve();
        });
      });
    },
    editStation(data){
      if(data.name){
        this.editStationName(data);
      }
      if(data.roles){
        data.roles.forEach((element, index) => {
          this.saveStationsRoles(element, this.branchId, data.station_id, index);
        });
      }
    },
    editStationName(data) {
      this.$http.patch(`api/v1/branches/${this.branchId}/stations/${data.station_id}`, {
        name: data.name
      }, {
        emulateJSON: true
      });
    },
    saveStationsRoles(element, branchId, station_id, index, dublicate = false) {
      let that = this;
      return new Promise(function(resolve, reject) {
        let dataRole = {
          role: element.name,
          color: element.color
        }
        if (element.role_id && !dublicate) {
          that.$http.patch(`api/v1/branches/${branchId}/stations/${station_id}/origin_roles/${element.role_id}`,
            dataRole, {
              emulateJSON: true
            });
        } else {
          that.$http.post(`api/v1/branches/${branchId}/stations/${station_id}/origin_roles/new`,
              dataRole, {
                emulateJSON: true
              })
            .then((response) => {
              that.$set(that.getStation(station_id)
                .roles[index], 'role_id', response.body.id);
              resolve();
            });
        }
      });
    },
    saveBranchShifts(dublicate = false) {
      let that = this;
      if (!dublicate) this.showLoader = true;
      return new Promise(function(resolve, reject) {
        let data = {
          company: that.$auth.user()
            .company_id
        };
        console.log('saveBranchShifts');
        that.$http.post('api/v1/branches/new', data, {
            emulateJSON: true
          })
          .then(response => {
            that.branchId = response.body.id;
            if (!dublicate) that.showLoader = false;
            console.log(that.branchId);
            if (that.branchId) {
              if (dublicate) {
                let shiftsSend = that.sendShiftRequest(true);
                let stationsSend = that.saveStations();
                return Promise.all([shiftsSend, stationsSend]);
              } else {
                return that.sendShiftRequest(false);
              }
            }
          }, (err) => {
            that.errorMessage = err.body.message;
            that.showLoader = false;
            that.setErrorMessage(that.errorMessage);
            that.$root.$emit('bv::show::modal', 'modalError');
            that.setLoadStatus(true);
            reject(false);

          })
          .then(() => {
            resolve(true);
          });
      });
    },
    saveTemplates(count) {
      let that = this;
      var last = Promise.resolve();
      for (let i = 0; i < count-1; i++) {
        last = last.then((response) => {
            console.log(response);
            return that.saveBranchShifts(true);
          })
          .then((response) => {
            console.log('template send', response);
            that.isSendDataTemplate = true;
            if (i == 0 && response) {
              //редирект после отправки первого запроса
              that.$router.push({name: "branchList"});
              // if  (this.branches.length) {
              //   that.$router.push({
              //     name: "dashboard"
              //   });
              // } else {
              //   that.$router.push({
              //     name: "branchPage",
              //     params: { id: this.branchId }
              //   });
              // }

            }
          });
      }
      last.then(function() {
        console.log('all send');
        that.setLoadStatus(true);
      });
    },
    complete(count) {
      if (count == 1) {
        this.setValid(true);
        this.moveDownTo(this.total - 1);
      } else {
        // this.$router.push({name: "branchList"});
        if  (this.branches.length) {
          that.$router.push({
            name: "dashboard"
          });
        } else {
          that.$router.push({
            name: "branchPage",
            params: { id: this.branchId }
          });
        }
      }
    }
  }
}
</script>
<style lang="scss" scoped>
.btn-info {
    background-color: $blue;
    border: none;
    border-radius: 100px;
    padding: 5px 25px;
    font-size: 16px;
    &:hover {
        background-color: $blue;
        border: none;
        outline: none;
        box-shadow: none;
    }
}
.slide-section .branch-preloader {
    position: fixed;
    /deep/ .loader {
        margin-top: 90px;
    }
}
</style>
<style lang="scss">
.modal-content {
    box-shadow: 2px 6px 10px rgba(28, 60, 77, 0.1);
    border: none;
    color: $lightgray;
    text-align: center;
}
.modal-backdrop {
    background-color: #d9dedead;
}
.modal-title {
    color: $cancelRed;
    width: 100%;
    display: flex;
    align-items: center;
}
.warning-icon {
    width: 42px;
    height: 42px;
    margin-right: 15px;
}
.warn-title {
    font-family: 'Roboto-Medium', sans-serif;
}
.modal-footer {
    .btn.btn-primary {
        background-color: $blue;
        border: none;
        border-radius: 100px;
        padding: 0.3em 1.8em;
        &:active,
        &:focus {
            box-shadow: none;
            outline: none;
            border: none;
            background-color: lighten($blue, 20);
        }
    }
}

#confirmDelBranch{
  .icon-warning-wrap{
    margin-right: 14px;
    width: 42px;
    height: 42px;
  }
  .warning-icon{
      width: 100%;
      height: 100%;
  }
  .modal-title{
      color: $gray;
      width: 100%;
      display: flex;
      align-items: center;
  }
  .modal-header{
    background: #e9eff2;
    border: 5px solid #fff;
  }
  .modal-footer{
    display: flex;
    .modal-buttons{
      width: 100%;
      display: flex;
    }
    .confirm-btn, .cancel-btn{
        border: none;
        width: 120px;
        border-radius: 110px;
        padding: 0.3em 1.8em;
        color: #fff;
        text-transform: uppercase;
        transition: all ease 300ms;
        &:hover, &:focus, &:active{
            box-shadow: none;
            outline: none;
            border: none;
        }
    }
    .confirm-btn{
        background-color: lighten($blue, 10);
        margin-right: auto;
        &:hover, &:focus, &:active{
            background-color: $blue;
        }
    }
    .cancel-btn{
        background-color: lighten($lightgray, 20);
        &:hover, &:focus, &:active{
            background-color: $lightgray;
        }
    }
  } 
}
#confirmDelBranch{
    .close{
        margin-left: auto;
    }
    .warning-message{
        color: #5d5d5d;
        font-size: 14px;
    }
    .modal-content{
        box-shadow: 2px 6px 10px rgba(28, 60, 77, 0.1);
        border: none;
        color: $lightgray;
        text-align: center;
    }
}
</style>
