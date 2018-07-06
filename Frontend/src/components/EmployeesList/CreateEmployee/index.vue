<template>
<b-modal class="employee-popup" size="lg"
         v-model="showModal"
         hide-header
         hide-footer>

  <div class="header">
    <h2>{{editEmployee ? 'Edit Employee': 'Create New Employee'}}</h2>
    <close-button class="close"
                  @click="showModal = false" />
  </div>
  <div class="input-wrapper">
    <input type="text"
           placeholder="First and Last Name"
           class="name-input"
           v-model="fullName"
           :class="{'field-error': validation.hasError('fullName')}" />
    <div class="error-message">{{validation.firstError('fullName')}}</div>
  </div>

  <div class="section">
    <div class="branches">
      <div class="head">
        <h3>Assigned to Branches</h3>
        <plus-button class="plus"
                     @click="showAddBranch = true" />
      </div>
      <badge v-for="(branch, index) in assignedBranches"
             :key="index"
             v-if="branch.geographical_address && branch.geographical_address.street_address"
             :text="branch.geographical_address.street_address"
             @close="removeBranch(branch, index)" />
      <autocomplete v-if="showAddBranch"
                    placeholder="Select branch"
                    class="autocomplete"
                    :options="branchesToSelect"
                    field-name="text"
                    @select="addBranch" />
    </div>

    <div class="roles">
      <div class="head">
        <h3>Roles</h3>
        <app-multi-select class="add-role" 
          dropDownClass="roles-dropdown" 
          valueFieldName="id"
          textFieldName="role"
          :options="roles"
          :selectedOptions="assignedRoles.map(({id}) => id)"
          @changeActiveSelect="addRole"
          :roleIcon="true"
          >
            <div slot="no-data">{{ assignedBranches.length ? 'No roles in selected branches' : 'Please select branches first' }}</div> 
          </app-multi-select>
      </div>
      <transition-group class="d-flex flex-wrap" name="slide">
        <roles-item v-for="(role, i) in assignedRoles"
                    :key="i"
                    :remove="true"
                    :color="role.color ? role.color : false"
                    :name="role.role"
                    @remove="removeRole(role)" />
      </transition-group>  

    </div>
  </div>

  <div class="section top">
    <div class="input-wrapper">
      <input type="text"
             placeholder="Hourly Rate"
             v-model="employee.hourly_rate"
             class="hourly-rate"
             :class="{'field-error': validation.hasError('employee.hourly_rate')}">
      <div class="error-message">{{validation.firstError('employee.hourly_rate')}}</div>
    </div>

    <div class="input-wrapper">
      <input type="text"
             placeholder="ID / Social Security Number"
             v-model="employee.social_security_number"
             :class="{'field-error': validation.hasError('employee.social_security_number')}">
      <div class="error-message">{{validation.firstError('employee.social_security_number')}}</div>
    </div>

  </div>

  <div class="section">
    <div class="avatar-wrapper">
      <div v-if="image != '' || editEmployee && employee.avatar"
           class="avatar">
        <img v-if="image != ''"
             :src="image"
             alt="">
        <img v-else
             :src="$http.options.root + '/' + employee.avatar.path"
             alt="">
      </div>
      <div v-else
           class="avatar">
        <img src="images/user_default.jpg"
             alt="">
      </div>

      <div v-if="image === '' || editEmployee && !editEmployee.avatar"
           class="upload-wrapper">
        <label for="avatar"
               class="upload">Upload Photo</label>
        <input id="avatar"
               type="file"
               @change="onFileChange">
      </div>
      <div v-else
           class="upload-wrapper">
        <button class="upload"
                @click="removeImage">Remove Photo</button>
      </div>
    </div>

    <div class="right-section">
      <div class="input-wrapper">
        <input type="text"
               placeholder="Address"
               id="route"
               ref="address"
               v-model="employee.geographical_area.street_address"
               :class="{'field-error': validation.hasError('employee.geographical_area.street_address')}">
        <div class="error-message">{{validation.firstError('employee.geographical_area.street_address')}}</div>
      </div>
      <div class="city-wrap">

        <div class="input-wrapper">
          <input ref="country" id="country" required 
          :class="{'field-error': validation.hasError('countryName')}" 
          class="form_input country" type="text" name="state_region"
          v-model="countryName">
        </div>

        <div class="input-wrapper">
          <input type="text"
                ref="region"
                id="administrative_area_level_1"
                 placeholder="Region"
                 v-model="employee.geographical_area.region"
                 :class="{'field-error': validation.hasError('employee.geographical_area.region')}">
          <div class="error-message">{{validation.firstError('employee.geographical_area.region')}}</div>
        </div>

      </div>
      

      <div class="city-wrap">
        <div class="input-wrapper">
          <input type="text"
                 placeholder="City"
                 id="locality"
                 ref="city"
                 v-model="employee.geographical_area.city"
                 class="city"
                 :class="{'field-error': validation.hasError('employee.geographical_area.city')}">
          <div class="error-message">{{validation.firstError('employee.geographical_area.city')}}</div>
        </div>

        <div class="input-wrapper">
          <input type="text"
                 placeholder="ZIP"
                 id="postal_code"
                 v-model="employee.geographical_area.zip"
                 :class="{'field-error': validation.hasError('employee.geographical_area.zip')}">
          <div class="error-message">{{validation.firstError('employee.geographical_area.zip')}}</div>
        </div>
      </div>
      <div class="input-wrapper">
        <div class="paired_row flex-nowrap form-row__phone">
          <app-select :options="selFlagsOptions"
              @changeSelection="changeSelectFlag"
              dropDownClass="flags_drop_down"
              selectClass="flags-select"
              :hideFirstTxt="true"
              :img="true"
              ></app-select>
          <div :class="{'field-error': validation.hasError('phone_number_p2')}" class="d-flex form_input  form_input-phone-wrap flex-nowrap">
              <span class="tel-code">{{ selectedFlagVal.text}}</span>
              <input required  class="form-input__phone" type="tel" id="form_tel_number" name="tel_number" v-model="phone_number_p2" >
          </div>
        </div>
        <div class="d-flex">
            <div class="error-message">{{validation.firstError('phone_number_p2')}}</div>
        </div>
      </div>

      <b-dropdown id="ddown1"
                  :text="employee.family_situation.family_status"
                  class="dropdown">
        <b-dropdown-item v-for="status in family_statuses"
                         @click="employee.family_situation = status"
                         :key="status.id">{{status.family_status}}</b-dropdown-item>
      </b-dropdown>
    </div>
  </div>

  <button class="create-button"
          @click="createEmployee">{{editEmployee ? 'Edit Employee': 'Create Employee'}}</button>

  <preloader :show="showPreloader"></preloader>
</b-modal>
</template>
<script>
import { Validator } from "simple-vue-validator"
import { mapGetters, mapMutations, mapState, mapActions } from 'vuex';
import {$eventBus} from '../../../main'
import flagsOption from '../../Registration/Step2/flags'
import {loadJs} from '../../Common/utils.js'
export default {
  mixins: [require("simple-vue-validator")
    .mixin
  ],

  validators: {
    "fullName" (value) {
      return Validator.value(value)
        .required();
    },
    "employee.hourly_rate" (value) {
      return Validator.value(value)
        .required().float();
    },
    "employee.social_security_number" (value) {
      return Validator.value(value)
        .required();
    },
    "employee.geographical_area.region" (value) {
      return Validator.value(value)
        .required();
    },
    'countryName'(value) {
      return Validator.value(value).required();
    },
    "employee.geographical_area.street_address" (value) {
      return Validator.value(value)
        .required();
    },
    "employee.geographical_area.city" (value) {
      return Validator.value(value)
        .required();
    },
    "employee.geographical_area.zip" (value) {
      return Validator.value(value)
        .required().integer();
    },
    "phone_number_p2" (value) {
      return Validator.value(value)
        .required().integer();
    },
  },

  props: {
    show: {
      type: Boolean
    },
    editEmployee: {
      type: Object
    }
  },

  data: () => ({
    employee: {
      first_name: '',
      last_name: '',
      phone_number: '',
      social_security_number: '',
      family_situation: {
        family_status: 'Family Status'
      },
      hourly_rate: '',
      branches: [],
      geographical_area: {
        region: '',
        city: '',
        street_address: '',
        zip: '',
        country: {
          id: ''
        },
      }
    },
    phone_number_p2: null,
    selectedFlagVal: {
      value: 1,
      text: '+7 840',
      data_attr: 'Abkhazia'
    },
    selFlagsOptions: flagsOption,
    fullName: '',
    family_statuses: [],
    assignedBranches: [],
    assignedRoles: [],
    countries: [],
    roles: [],
    showAddBranch: false,
    showAddRole: false,
    image: '',
    files: null,
    countryName: '',
    countryCode: null,
    countryId: null,
    initCountry: null,
    autocompleteRegion: null,
    autocompleteCity: null,
    autocompleteAddress: null,
    branches: [],
    branchesToSelect: [],
    detachBranchId: null,
    copyEmployeeObj: {},
    editEmployeeUnwatch: null
  }),

  computed: {
    ...mapGetters(['apiKey', 'language']),
    ...mapState({
			showPreloader: state => state.calendar.showPreloader,
		}),
    showModal: {
      get() {
        return this.show
      },
      set(value) {
        this.$emit('update:show', value)
      }
    },
    
    geoData(){
      return JSON.parse(localStorage.getItem('geoData'));
    }
  },
  created() {
    this.$http.get('api/v1/family_statuses/')
      .then(res => {
        console.log(res.body)
        this.family_statuses = res.body
      })
    this.$http.get('api/v1/countries/')
      .then(res => this.countries = res.body)
    this.$http.get('api/v1/branches/')
      .then(res => {
        this.branches = res.body;
        this.setBranchesToSelect();
        if(this.editEmployee){
          this.initEditEmployeeData();
        }
        if (this.branches.length === 1) {
          this.addBranch(this.branches[0])
        }
      })

      this.getRoles();
  },
  mounted(){
    this.setPreloaderState(false);
    if(!window.google){
      loadJs(`https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&libraries=places&language=${this.language}`, ()=>{
          this.initAutocomplete();
      });
    }else{
      this.initAutocomplete();
    }
  },
  methods: {
    ...mapMutations(['setPreloaderState']),
    getRoles() {
      this.roles=[];
      this.assignedBranches.forEach(branch=>{
          branch.stations.forEach(station=>{
            this.roles = [...this.roles, ...station.origin_roles]
          });
      });
    },
    removeBranchRoles(branchId){
      this.roles = this.roles.filter(({branch_id})=> branch_id != branchId);
    },
    rolesQuery() {
      let query = ''
      this.assignedBranches.forEach(branch => query += `branches[]=${branch.id}&`)
      return query
    },
    createEmployee() {
      this.$validate()
      .then( (success) => {
        if (success) {
          this.sendData()
        }
      });
    },
    sendData() {
        this.setPreloaderState(true);
        console.log('send employee');
        let names = this.fullName.split(' ');
        this.employee.first_name = names[0];
        this.employee.last_name = this.fullName.substr(names[0].length).trim();
        let postImg = new FormData();
        if (this.files) {
          postImg.append('avatar[path]', this.files[0], this.files[0].name);
        }

        let method = this.editEmployee ? 'patch' : 'post';
        let path = this.editEmployee ? this.employee.id : 'new';
        let obj;
        this.employee.branches = this.assignedBranches;
        this.employee.roles_info = this.assignedRoles;
        this.employee.phone_number = {
          country: {
            id: this.selectedFlagVal.value,
            name: this.selectedFlagVal.data_attr,
            phone_code: this.selectedFlagVal.text
          },
          phone_number: this.phone_number_p2,
          prefix_number: this.editEmployee ? this.editEmployee.phone_number.prefix_number : '', // temp field => will delete
        }
        
        if (method === 'patch') {
          obj = {
            first_name: this.employee.first_name,
            last_name: this.employee.last_name,
            phone_number:{
              country: this.selectedFlagVal.value,
              phone_number: +this.phone_number_p2
            },
            family_situation: this.employee.family_situation.id,
            hourly_rate: this.employee.hourly_rate,
            social_security_number: this.employee.social_security_number,
            branches: this.assignedBranches.map(({
              id
            }) => id),
            roles: this.assignedRoles.map(({
              id
            }) => id),
            geographical_area: {
              region: this.employee.geographical_area.region,
              city: this.employee.geographical_area.city,
              street_address: this.employee.geographical_area.street_address,
              country: this.employee.geographical_area.country.id,
              zip: this.employee.geographical_area.zip,
            }
          }
          console.log('Edit employee isEqual employee: ', _.isEqual(this.editEmployee, this.employee));
          if(_.isEqual(this.editEmployee, this.employee)){
            console.log('no changes on employee data...');
            console.log(!!this.files);
            if(this.files){
              this.sendAvatar(postImg);
            }else{
              this.setPreloaderState(false);
              this.showModal = false;
            }
            return 
          }
        } else {
          obj = _.cloneDeep(this.employee);
          delete obj.roles_info;
          // obj.prefix_number = this.selectedFlagVal;
          obj.phone_number =  {
            country: this.selectedFlagVal.value,
            phone_number: +this.phone_number_p2
          };
          obj.branches = this.assignedBranches.map(({id}) => id)
          obj.roles = this.assignedRoles.map(({id}) => id);
          obj.family_situation = this.employee.family_situation.id;
          obj.geographical_area.country = this.employee.geographical_area.country.id;
        }
        this.$http[method](`api/v1/companies/${this.$auth.user().company_id}/employees/${path}`, obj)
          .then(res => {
            if(method =='post'){
              this.employee.id = res.body.id;
            }
            if (this.files) {
              this.sendAvatar(postImg);
            }else{
              this.updateList();
            }
            if (res.status === 200 && method === 'post') {
              // alert('employee successfully created!\n(I need design for this message)');
            }
          }).catch(err=>{
            this.setPreloaderState(false);
            this.showModal = false;
            alert(err.body.type || err.body.message);
          });
        
    },
    sendAvatar(postImg){
      this.$http.post(`api/v1/companies/${this.$auth.user().company_id}/employees/${this.employee.id}/avatars`, postImg).then((res)=>{
        this.employee.avatar = res.body.avatar;
        this.updateList();
      });
    },
    updateList(){
      this.setPreloaderState(false);
      this.showModal = false;
      if(!this.editEmployee){
        this.$emit('updateList');
        this.clearFieldsData();
      }
      this.$emit('updateData', this.employee); // передать копию исходному объекту
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
      // this.employee.branches.push(item.id)
      this.showAddBranch = false;
    },
    setBranchesToSelect() {
      this.branchesToSelect = this.branches
        .filter(branch => branch.geographical_area && branch.geographical_area.street_address)
        .map(branch => ({
          text: branch.geographical_area.street_address,
          id: branch.id,
          stations: branch.stations
        }))
    },
    onFileChange(e) {
      let files = e.target.files || e.dataTransfer.files;
      if (!files.length)
        return;
      this.createImage(files[0]);
      this.files = files;
    },
    createImage(file) {
      let image = new Image();
      let reader = new FileReader();
      let vm = this;

      reader.onload = (e) => {
        vm.image = e.target.result;
      };
      reader.readAsDataURL(file);
    },
    removeImage(e) {
      this.employee.avatar = null
      this.image = '';
      this.files = null;
    },

    addRole(role) {
      if (role[0]) {
        this.assignedRoles.push(role[1]);
      } else {
        this.removeRole(role[1]);
      }
    },

    removeRole(role) {
      this.assignedRoles.splice(this.assignedRoles.indexOf(this.assignedRoles.find(item => item.id === role.id)), 1)
    },
    removeBranch(branch, index){
      this.assignedBranches.splice(index, 1);
      this.branchesToSelect.push({
        id: branch.id,
        stations: branch.stations,
        text: branch.geographical_address.street_address
      });
      this.assignedRoles = this.assignedRoles.filter((item) => item.branch_id != branch.id);
      if(this.assignedBranches.length==0){
        this.roles=[];
        this.assignedRoles = [];
        this.detachBranchId = branch.id;
      }else{
        this.removeBranchRoles(branch.id);
      }
    },
    blur(e) {
      console.log(e)
    },
    fetchFlagsList(){
      for(let i=0; i<this.countries.length; i++){
          for(let c=0; c<flagsOption.length; c++){
              if(this.countries[i].name == flagsOption[c].data_attr){
                  flagsOption[c].value= this.countries[i].id;
                  flagsOption[c].text= this.countries[i].phone_code;
                  break;
              }
          }
      }
    },
    changeSelectFlag(option){
      this.selectedFlagVal = option;
    },
    changeSelectCountries(component, employeeInit=false){
      console.log('select country');
      component.address_components.forEach(address=>{
          let addressType = address.types[0];
          if(addressType=='country'){
              if(_.isEmpty(employeeInit)){
                this.countryName = address['long_name'];
              }else{
                if(employeeInit.geographical_area && employeeInit.geographical_area.country){
                  this.countryName = employeeInit.geographical_area.country.name;
                }else{
                  this.countryName ='';
                }
              }
              this.countryCode = address['short_name'];
          }
      });
      this.countries.forEach(country=>{
          if(country.country_code == this.countryCode){
              this.employee.geographical_area.country=country;
              this.countryId = country.id;
              this.selectedFlagVal=country;
              $eventBus.$emit('changeSelectCountries', country);
          }
      });
      this.initAutocompleteRestrictions();
    },
    initAutocompleteRestrictions(){
      this.autocompleteRegion.setComponentRestrictions({country: this.countryCode});
      this.autocompleteCity.setComponentRestrictions({country: this.countryCode});
      this.autocompleteAddress.setComponentRestrictions({country: this.countryCode});
    },
    initAutocomplete() {
        // Create the autocomplete object, restricting the search to geographical
        // location types.
        this.autocompleteAddress = new google.maps.places.Autocomplete(this.$refs.address,
        {
            types: ['geocode'],
        });
        this.autocompleteAddress.addListener('place_changed', ()=>{
            let address = this.autocompleteAddress.getPlace();
            this.changeSelectAddress(address);
        });
        this.autocompleteCity = new google.maps.places.Autocomplete(this.$refs.city,
        {
            types: ['(cities)'],
        });
        this.autocompleteCity.addListener('place_changed', ()=>{
            let city = this.autocompleteCity .getPlace();
            this.changeSelectCity(city);
        });
        this.autocompleteRegion = new google.maps.places.Autocomplete(this.$refs.region,
            {
                types: ['(regions)'],
            });
        this.autocompleteRegion.addListener('place_changed', ()=>{
            let region = this.autocompleteRegion.getPlace();
            this.changeSelectRegion(region);
        });

        let autocompleteCountry = new google.maps.places.Autocomplete(this.$refs.country,
            {
                types: ['geocode'],
            });

        autocompleteCountry.addListener('place_changed', ()=>{
            let country = autocompleteCountry.getPlace();
            this.changeSelectCountries(country);
        });
        let unwatch = this.$watch('countries', (value)=>{
            if(value.length){
                this.fetchFlagsList();
                  this.$watch('geoData', (value)=>{
                          if(!$.isEmptyObject(value)){
                              this.initGeoData();
                          }
                      },
                      {immediate: true}
                  );
                  if(this.$route.name == 'employeeProfile' && this.$route.params.id){
                    this.editEmployeeUnwatch = this.$watch('editEmployee', (value)=>{
                        if(!$.isEmptyObject(value)){
                          if(value.geographical_area && value.geographical_area.country){
                            this.countryName = value.geographical_area.country.name;
                            $eventBus.$emit('changeSelectCountries', value.geographical_area.country);
                          }
                          if(this.branches.length){
                            this.initEditEmployeeData();
                            this.validation.reset();
                          }
                        }
                    }, {immediate: true, deep: true})
                  }
                  
            }
        }, {immediate: true});
    },
    initGeoData(){
      let place = JSON.parse(localStorage.getItem('geoData'));
      let componentForm = {
          locality: 'long_name',
          administrative_area_level_1: 'short_name',
          country: 'long_name',
          postal_code: 'short_name',
      };

      for (let component in componentForm) {
          document.getElementById(component).value = '';
          document.getElementById(component).disabled = false;
      }
      for (let i = 0; i < place.address_components.length; i++) {
          let addressType = place.address_components[i].types[0];
          if (componentForm[addressType]) {
              let val = place.address_components[i][componentForm[addressType]];
              switch (addressType){
                  case 'locality':
                      !this.editEmployee && (this.employee.geographical_area.city = val);
                      break;
                  case 'administrative_area_level_1':
                      !this.editEmployee && (this.employee.geographical_area.region = val);
                      break;
                  case 'country':
                      !this.editEmployee && (this.countryName = val);
                      break;
                  case 'postal_code':
                      !this.editEmployee && (this.employee.geographical_area.zip = val);
                      break;
              }
          }
          if(addressType=='country'){
              this.countryCode = place.address_components[i]['short_name'];
          }
      }
      this.changeSelectCountries(place, this.editEmployee);
    },
    changeSelectRegion(component){
          component.address_components.forEach(address=>{
            let addressType = address.types[0];
            if(addressType=='administrative_area_level_1'){
                this.employee.geographical_area.region = address['short_name'];
            }
        });
    },
    changeSelectAddress(component){
        if(component.address_components){
            let route, street_number;
            console.log(component);
            component.address_components.forEach(address=>{
                let addressType = address.types[0];
                if(addressType =='route'){
                    route = address['long_name'];
                }
                if(addressType =='street_number'){
                    street_number = address['short_name'];
                }
            });
            if(route && street_number){
                this.employee.geographical_area.street_address = route + ', ' + street_number;
            }else if(route){
                this.employee.geographical_area.street_address = route;
            }else{
                this.employee.geographical_area.street_address = component.name;
            }
        }
    },
    changeSelectCity(component){
          component.address_components.forEach(address=>{
            let addressType = address.types[0];
            if(addressType=='locality'){
                this.employee.geographical_area.city = address['long_name'];
            }
        });
    },
    clearFieldsData(){
      this.employee = {
        first_name: '',
        last_name: '',
        phone_number: '',
        social_security_number: '',
        family_situation: {
          family_status: 'Family Status'
        },
        hourly_rate: '',
        branches: [],
        geographical_area: {
          region: '',
          city: '',
          street_address: '',
          zip: '',
          country: {
            id: ''
          },
        }
      };
      this.assignedBranches = [];
      this.assignedRoles= [];
      this.phone_number_p2 = null;
      this.image = '';
      this.files = null;
      this.fullName = '';
      this.family_statuses = [];
      this.countries = [];
      this.roles = [];
      this.showAddBranch = false;
      this.showAddRole = false;
      this.countryName = '';
      this.countryCode = null;
      this.initCountry = null;
      this.autocompleteRegion = null;
      this.autocompleteCity = null;
      this.autocompleteAddress = null;
      this.branches = [];
      this.branchesToSelect = [];
      this.setBranchesToSelect();
      this.validation.reset();
    },
    initEditEmployeeData(){
      if (!_.isEmpty(this.editEmployee)) {
        this.editEmployeeUnwatch();
        console.log('init data');
        let copyemployeeObj = _.cloneDeep(this.editEmployee);
        this.copyEmployeeObj = copyemployeeObj;
        if (!copyemployeeObj.geographical_area) {
            copyemployeeObj.geographical_area = {
              region: '',
              city: '',
              street_address: '',
              zip: '',
              country: {
                id: ''
              },
            }
        }
        let unwatchContryId = this.$watch('countryId', (id)=>{
          if(id){
            if(!copyemployeeObj.geographical_area.country.id){
              copyemployeeObj.geographical_area.country.id = id;
            }else{
              this.countryCode = copyemployeeObj.geographical_area.country.country_code;
            }
            this.initAutocompleteRestrictions();
            if(copyemployeeObj.phone_number && !copyemployeeObj.phone_number.country && ~copyemployeeObj.phone_number.phone_number.indexOf(this.selectedFlagVal.phone_code)){
              this.phone_number_p2 = copyemployeeObj.phone_number.phone_number.substr(this.selectedFlagVal.phone_code.length);
            }else if(copyemployeeObj.phone_number && copyemployeeObj.phone_number.country){
              this.selectedFlagVal = copyemployeeObj.phone_number.country;
              $eventBus.$emit('changeSelectCountries', copyemployeeObj.phone_number.country);
              this.phone_number_p2 =  copyemployeeObj.phone_number.phone_number;
            }
          }
        }, {immediate: true});
        let vm = this;
        function unwatchContryIdWait(){
          setTimeout(()=>{
            if(vm.countryId && unwatchContryId){
              unwatchContryId();
              unwatchContryId = null;
            }else if(unwatchContryId){
              unwatchContryIdWait();
            }
          }, 3000);
        }
        if(unwatchContryId){
          console.log('unwatchContryIdWait run');
          unwatchContryIdWait();
        }
        
        this.employee = copyemployeeObj;
        this.assignedBranches = [];
        copyemployeeObj.branches.forEach(el =>{
          let branch = this.branches.find(item=>{
            return item.id == el.id
          });
          this.assignedBranches.push({
            geographical_address: el.geographical_address,
            id: el.id,
            stations: branch.stations
          })
        });
        
        if(this.assignedBranches.length){
          this.getRoles();
        }
        this.assignedRoles = copyemployeeObj.roles_info
        this.fullName = this.employee.first_name + ' ' + this.employee.last_name
        if (!this.employee.family_situation || this.employee.family_situation && !this.employee.family_situation.family_status) {
          this.employee.family_situation = {
            family_status: 'Family Status'
          }
        }
        
      }
    },
    getCountryByTelCode(){
      return this.countries.find(item =>{
        return item.phone_code == this.selectedFlagVal
      })
    }
  },
  watch: {
    show(value){
      if(value){
        this.initEditEmployeeData();
        this.validation.reset();
      }else{
        if(this.$route.name == 'employeeProfile' && this.$route.params.id){
          console.log('saved (no changes) : ', _.isEqual(this.editEmployee, this.employee));
          if(!_.isEqual(this.editEmployee, this.employee)){
            this.employee = _.cloneDeep(this.editEmployee);
            this.initEditEmployeeData();
            //если выход без сохранения вернуть значения страны и флага
            if(this.editEmployee.geographical_area && this.editEmployee.geographical_area.country){
              this.countryName = this.editEmployee.geographical_area.country.name;
              $eventBus.$emit('changeSelectCountries', this.editEmployee.geographical_area.country);
            }
          }
        }
      }
    }
  },

  components: {
    closeButton: require('../../Common/CloseBtn'),
    plusButton: require('../../Common/ButtonPlus'),
    rolesItem: require('../../BranchCreation/BranchFlow/SetupStations/RolesList/RolesItem'),
    badge: require('../../Common/Badge'),
    autocomplete: require('../../Common/Autocomplete2'),
    appSelect: require('../../Common/Select'),
    preloader: require('../../Common/Preloader'),
    appMultiSelect: require('../../Common/MultiSelect2')
  }

}
</script>
<style lang='scss' src='./style.scss' scoped></style> 
<style lang='scss' scoped> 


.fade {
    background-color: rgba(43, 69, 83, 0.2);
}

.employee-popup {
  /deep/ .modal-body {
    padding: 10px;
  }
  /deep/ .modal-content {
    box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
    border-radius: 2px;
    padding: 0;
    border: 0;
  }
}
.employee-popup {
  /deep/ .right-section {
      .btn-group {
          .btn-secondary {
              background: white;
              width: 100%;
              color: #2e3a40;
              border: none;
              box-shadow: none;
              text-align: left;
              padding-left: 30px;
              &:active,
              &:focus,
              &:hover {
                  color: #2e3a40;
                  outline: none;
                  box-shadow: none;
                  background-color: white;

              }
          }
      }
      .dropdown-menu{
        border: 0;
        box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
        .dropdown-item, .dropdown-item:focus, .dropdown-item:hover{
          border: 0;
          box-shadow: none;
          outline: 0;
        }
        .dropdown-item:hover{
          background-color: #e9f1f5;
        }
      }
  }
}

#country {
    height: 55px;
    input.with_border {
        border: none;
        height: 100%;
        border: none;
        border-right: 1px solid #e6ecee;
        border-top: 1px solid #e6ecee;
        padding-left: 30px;
        font-size: 15px;
        font-weight: 400;
        line-height: 18px;
        color: #2e3a40;
    }
}
.flags_drop_down{
    width: 108px;
    height: 162px;
    display: block;
    position: absolute;
    top: 100%;
    box-shadow: 0px 0px 10px #bac6ca;
    background-color: #ffffff;
    z-index: 1;
    .select-option-item{
      white-space: nowrap;
    }
}
</style>
