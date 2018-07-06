<template>
<transition-group tag="section"
                  name="fade-slide"
                  class="d-flex flex-column table-row">
  <tr key="content"
      class="branch-row d-flex justify-content-between">
    <td class="branch-td branch-td__id">
      <slot></slot>
    </td>
    <td class="branch-td">
      <input class="branch branch__address"
             type="text"
             placeholder="Enter Branch Address..."
             v-model="address"
             id="route"
             ref="address"
             @change="patchAddress">
      <preloader class="small-preloader" :show="showPreloadAddress"></preloader>       
    </td>
    <td class="branch-td">
      <input class="branch branch__address"
             type="text"
             ref="region"
             id="administrative_area_level_1"
             placeholder="Region"
             v-model="region"
             @change="patchRegion">
      <preloader class="small-preloader" :show="showPreloadRegion"></preloader> 
    </td>
    <td class="branch-td">
      <div class="branch__btn-add">
        <svg width="18"
             height="16"
             class="icon-user-plus"
             viewBox="0 0 2048 1792"
             xmlns="http://www.w3.org/2000/svg"><path d="M704 896q-159 0-271.5-112.5t-112.5-271.5 112.5-271.5 271.5-112.5 271.5 112.5 112.5 271.5-112.5 271.5-271.5 112.5zm960 128h352q13 0 22.5 9.5t9.5 22.5v192q0 13-9.5 22.5t-22.5 9.5h-352v352q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-352h-352q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h352v-352q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5v352zm-736 224q0 52 38 90t90 38h256v238q-68 50-171 50h-874q-121 0-194-69t-73-190q0-53 3.5-103.5t14-109 26.5-108.5 43-97.5 62-81 85.5-53.5 111.5-20q19 0 39 17 79 61 154.5 91.5t164.5 30.5 164.5-30.5 154.5-91.5q20-17 39-17 132 0 217 96h-223q-52 0-90 38t-38 90v192z"/></svg>
      </div>
      <autocomplete class="branch branch__manager autocomplete-input"
                    :class="{error : error_manager, success: success_manager}"
                    :suggestions="managers"
                    :text="manager"
                    fieldName="full_name"
                    placeholder="Branch Manager"
                    @changeSearch="checkEmail($event, 'manager')"
                    @suggestionClick="patchManager"></autocomplete>
      <preloader class="small-preloader" :show="showPreloadManager"></preloader>               
    </td>
    <td class="branch-td">
      <div class="branch__btn-add">
        <svg width="18"
             height="16"
             class="icon-user-plus"
             viewBox="0 0 2048 1792"
             xmlns="http://www.w3.org/2000/svg"><path d="M704 896q-159 0-271.5-112.5t-112.5-271.5 112.5-271.5 271.5-112.5 271.5 112.5 112.5 271.5-112.5 271.5-271.5 112.5zm960 128h352q13 0 22.5 9.5t9.5 22.5v192q0 13-9.5 22.5t-22.5 9.5h-352v352q0 13-9.5 22.5t-22.5 9.5h-192q-13 0-22.5-9.5t-9.5-22.5v-352h-352q-13 0-22.5-9.5t-9.5-22.5v-192q0-13 9.5-22.5t22.5-9.5h352v-352q0-13 9.5-22.5t22.5-9.5h192q13 0 22.5 9.5t9.5 22.5v352zm-736 224q0 52 38 90t90 38h256v238q-68 50-171 50h-874q-121 0-194-69t-73-190q0-53 3.5-103.5t14-109 26.5-108.5 43-97.5 62-81 85.5-53.5 111.5-20q19 0 39 17 79 61 154.5 91.5t164.5 30.5 164.5-30.5 154.5-91.5q20-17 39-17 132 0 217 96h-223q-52 0-90 38t-38 90v192z"/></svg>
      </div>
      <autocomplete class="branch branch__manager autocomplete-input"
                    :class="{error : error_supervisor, success: success_supervisor}"
                    :suggestions="supervisors"
                    :text="supervisor"
                    v-model="supervisor"
                    fieldName="full_name"
                    placeholder="Branch Supervisor"
                    @changeSearch="checkEmail($event, 'supervisor')"
                    @suggestionClick="patchSupervisor"></autocomplete>
      <preloader class="small-preloader" :show="showPreloadSupervisor"></preloader>               
    </td>
    <td class="branch-td branch-td__call">
      <button @click="showPhones = !showPhones"
              class="branch__button">
                    <svg class="branch__call" v-if="isPhonesSet"><use xlink:href="images/icons-sprite.svg#user-call-yes"></use></svg>
                    <svg class="branch__call" v-else ><use xlink:href="images/icons-sprite.svg#user-call-no"></use></svg>
                </button>
    </td>
    <td class="branch-td branch-td__del">
        <div class="delete-icon" @click="deleteBranch">
          <svg class="icon" width="20" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M704 1376v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm256 0v-704q0-14-9-23t-23-9h-64q-14 0-23 9t-9 23v704q0 14 9 23t23 9h64q14 0 23-9t9-23zm-544-992h448l-48-117q-7-9-17-11h-317q-10 2-17 11zm928 32v64q0 14-9 23t-23 9h-96v948q0 83-47 143.5t-113 60.5h-832q-66 0-113-58.5t-47-141.5v-952h-96q-14 0-23-9t-9-23v-64q0-14 9-23t23-9h309l70-167q15-37 54-63t79-26h320q40 0 79 26t54 63l70 167h309q14 0 23 9t9 23z"/></svg>
        </div>
    </td>
  </tr>
  <tr v-show="showPhones"
      key="phones-content">
    <td colspan="6" class="d-flex justify-content-between wrap__branch-phones">
      <div class="branch__title">
        Emergency Phones
      </div>
      <div class="branch-phones">
        <branch-phones @inputPhone="patchPhone"
                       :branch="branch"></branch-phones>
      </div>
    </td>
  </tr>
</transition-group>
</template>

<script>
import 'images/sprites/branch-manager/user-call-yes.svg'
import 'images/sprites/branch-manager/user-call-no.svg'
import {
  validateEmail
} from '../../Common/utils.js'
export default {
  props: {
    branch: {
      type: Object
    },
    managers: {
      type: Array,
      required: true
    },
    supervisors: {
      type: Array,
      required: true
    },
    googlePlacesLoad: Boolean
  },
  data: () => ({
    showPhones: false,
    address: '',
    region: '',
    city: '',
    manager: '',
    supervisor: '',
    success_manager: '',
    success_supervisor: '',
    error_manager: '',
    error_supervisor: '',
    modal: false,
    autocompleteAddress: null,
    autocompleteRegion: null,
    phoneSet: false,
    showPreloadAddress: false,
    showPreloadRegion: false,
    showPreloadManager: false,
    showPreloadSupervisor: false,
  }),
  computed: {
    isPhonesSet() {
      if (this.branch && (this.branch.police_phone ||
          this.branch.fire_phone ||
          this.branch.ambulance_phone ||
          this.phoneSet)) {
        return true;
      } else {
        return false;
      }
    }
  },
  methods: {

    patchAddress() {
      this.showPreloadAddress = true;
      this.$http.patch('api/v1/branches/' + this.branch.id, {
        geographical_area: {
          street_address: this.address,
          city: this.city
        }
      }).then(()=>{
        this.showPreloadAddress = false;
      })
    },

    patchRegion(region) {
      if (region) {
        this.showPreloadRegion = true;
        this.$http.patch('api/v1/branches/' + this.branch.id, {
          geographical_area: {
            region: this.region
          }
        }).then(()=>{
          this.showPreloadRegion = false;
        });
      }

    },

    patchManager(manager) {
      if (manager) {
        this.showPreloadManager = true;
        this.$http.patch(`api/v1/companies/${this.$auth.user().company_id}/users/branch_managers/${manager.id}`, {
          branch: this.branch.id
        }).then(()=>{
          this.showPreloadManager = false;
        });
      }
    },
    patchSupervisor(supervisor) {
      if (supervisor) {
        this.showPreloadSupervisor = true;
        this.$http.patch(`api/v1/branches/${this.branch.id}`, {
          supervisor: supervisor.id
        }).then(()=>{
          this.showPreloadSupervisor = false;
        });
      }
    },
    changeSelectRegion(component) {
      if (component.address_components) {
        component.address_components.forEach(address => {
          let addressType = address.types[0];
          if (addressType == 'administrative_area_level_1') {
            this.region = address['short_name'];
            this.patchRegion(this.region);
          }
        });
      }
    },
    changeSelectAddress(component) {
      if (component.address_components) {
        let route, street_number;
        component.address_components.forEach(address => {
          let addressType = address.types[0];
          if (addressType == 'route') {
            route = address['long_name'];
          }
          if (addressType == 'street_number') {
            street_number = address['short_name'];
          }
          if (addressType == 'administrative_area_level_1') {
            this.region = address['short_name'];
            this.patchRegion(this.region);
          }
          if(addressType=='locality'){
            this.city = address['long_name'];
          }
        });
        if (route && street_number) {
          this.address = route + ', ' + street_number;
        } else if (route) {
          this.address = route;
        } else {
          this.address = component.name;
        }
        if (this.address) {
          this.patchAddress(this.address);
        }
      }
    },
    patchPhone(phone, type) {
      let phoneObj = {}
      phoneObj[type + '_phone'] = phone;
      if (phone) {
        this.phoneSet = true;
      }
      this.$http.patch(`api/v1/branches/${this.branch.id}`, phoneObj);
    },
    initAutocomplete() {
      console.log('init google');
      this.autocompleteAddress = new google.maps.places.Autocomplete(this.$refs.address, {
        types: ['geocode'],
      });
      this.autocompleteAddress.addListener('place_changed', () => {
        let address = this.autocompleteAddress.getPlace();
        this.changeSelectAddress(address);
      });
      this.autocompleteRegion = new google.maps.places.Autocomplete(this.$refs.region, {
        types: ['(regions)'],
      });
      this.autocompleteRegion.addListener('place_changed', () => {
        let region = this.autocompleteRegion.getPlace();
        this.changeSelectRegion(region);
      });
    },
    checkEmail(selection, type) {
      //Проверка является ли введенное значение email
      console.log(selection, type);
      if (validateEmail(selection)) {
        let company_id = this.$auth.user()
          .company_id;
        let data = {
          email: selection,
          company: company_id
        };
        if (type == 'manager') {
          this.showPreloadManager = true;
          data.branch = this.branch.id;
          this.$http.post(`api/v1/companies/${company_id}/users/branch_managers/new`, data, {
              emulateJSON: true
            }, )
            .then((res) => {
              this.showPreloadManager = false;
              console.log(res.body);
              this.success_manager = 'E-mail with verification link for branch manager was sent.'
            })
            .catch(({
              body
            }) => {
              this.showPreloadManager = false;
              console.log(body.message);
              this.error_manager = body.message;
            });
        } else if (type == 'supervisor') {
          this.showPreloadSupervisor = true;
          this.$http.post(`api/v1/companies/${company_id}/users/supervisors/new`, data, {
              emulateJSON: true
            })
            .then((res) => {
              this.showPreloadSupervisor = false;
              console.log(res.body);
              this.$http.patch(`api/v1/branches/${this.branch.id}`, {
                supervisor: res.body.id
              });
              this.success_supervisor = 'E-mail with verification link for supervisor was sent.'
            })
            .catch(({
              body
            }) => {
              this.showPreloadSupervisor = false;
              console.log(body.message);
              this.error_supervisor = body.message;
            });
        }
      }
    },
    deleteBranch(){
      this.$emit('deleteBranch', this.branch.id);
    }
  },

  created() {
    this.$http.get('api/v1/branches/' + this.branch.id)
      .then(
        res => {
          console.log(res.body)
          if (res.body.branch_manager) {
            this.manager = res.body.branch_manager.first_name + ' ' + res.body.branch_manager.last_name
          }
          if (res.body.supervisor) {
            this.supervisor = res.body.supervisor.first_name + ' ' + res.body.supervisor.last_name
          }
          if (res.body.geographical_area) {
            this.region = res.body.geographical_area.region
            this.address = res.body.geographical_area.street_address
          }
        })
  },
  mounted() {
    let unwatch = this.$watch('googlePlacesLoad', function(value) {
      if (value) {
        this.initAutocomplete();
      }
    }, {
      immediate: true
    });
  },

  components: {
    addBtn: require('../../Common/ButtonPlus'),
    closeBtn: require('../../Common/CloseBtn'),
    branchPhones: require('../../BranchPhones'),
    autocomplete: require('../../Common/Autocomplete'),
    preloader: require('../../Common/Preloader')
  }
}
</script>
<style lang='scss' src='./style.scss' scoped></style> 
<style lang='scss' scoped> 
.autocomplete-wrap input[name=industry_category] {
    height: 62px;
    padding: 20px;
    border: none;
}
.autocomplete-dropdown {
    background-color: #fff;
    box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
    position: absolute;
    right: 0;
    left: 0;
    z-index: 10;
}
.autocomplete-dropdown .select-options-list {
    margin: 0;
    padding: 0;

    list-style-type: none;
}
.autocomplete__item {
    padding: 5px 0;
    color: #505d63;
}
.branch__btn-add a {
    outline: none;
}
.border {
    border: 1px solid grey;
    border-radius: 2px;
    padding: 12px;
}
.modal-content {
    margin-top: 150px;
}
.table-row .fade-slide-enter-active {
    transition: all 0.4s ease;
}
.table-row .fade-slide-move {
    transition: transform 0.4s;
    position: relative;
}
.table-row .fade-slide-leave-active {
    animation: slide-out 0.6s ease-out forwards;
    transition: opacity 0.6s ease;
    opacity: 0;
    position: absolute;
    width: calc(100vw - 50px);
}
.delete-icon{
   width: 35px;
   height: 35px;
   border-radius: 50%;
   background-color: #e9eff2;
   display: flex;
   align-items: center;
   justify-content: center;
   cursor: pointer;
   .icon{
       fill: #8b9da6; 
   }
   &:hover{
      background-color: darken(#e9eff2, 10);
      .icon{
          fill: #fff;
      } 
   }  
}
.delete-icon .icon{
    width: 13px;
    height: 16px;
}
</style>
