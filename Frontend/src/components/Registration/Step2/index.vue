<template>
    <div class="steps-container padd_top_steps">
        <div class="steps">
            <div class="step" v-if="isStepsHeader">
                <div class="round_57 step1_2_bor_col">
                    <div class="round_41">
                        <svg width="19" height="14" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg" fill="#0ecdee">
                            <path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" />
                        </svg>
                    </div>
                    <div class="step_span step_text_unchecked">Sign Up</div>
                </div>

                <div class="spacer"></div>

                <div class="round_70 step1_2_bor_col" >
                    <div class="round_50">2</div>
                    <div class="step_span step_text_checked step_marg_left_5 r70_bot">Company Info</div>
                </div>

                <div class="spacer"></div>

                <div class="round_57 step3_bor_col">
                    <div class="round_41">3</div>
                    <div class="step_span step_text_unchecked">Company Settings</div>
                </div>

            </div>
            <div class="step2_title marg_bot_40">{{ isRegistrEmployee ? 'Employee Information' : 'Company Information' }}</div>
            <div class="avatar-wrapper" v-if="isRegistrEmployee">
                <label for="avatar" class="avatar" :class="{'no-image': !image}" :style="{backgroundImage: `url(${avatarPath})`}"></label>
                <div class="upload-wrapper">
                    <label for="avatar" v-if="!image" class="upload">Upload Photo</label>
                    <input id="avatar" type="file" @change="onFileChange">
                </div>
                <div v-if="image" class="upload-wrapper">
                    <button class="upload" @click="removeImage">Remove Photo</button>
                </div>
            </div>
            <div class="reg_form">
                <figure>
                    <form action="" method="post" @submit.prevent="sendData">
                        <div class="form_row flex-nowrap">
                            <div class="paired_row paired_row_step2">
                                <div class="form_row_text">First Name</div>
                                <input :class="{'field-error': validation.hasError('userData.firstName')}" class="form_input" required type="text" name="first_name" v-model="userData.firstName">
                            </div>
                            <div class="paired_row paired_row_step2">
                                <div class="form_row_text">Last Name</div>
                                <input :class="{'field-error': validation.hasError('userData.lastName')}" required class="form_input" type="text" name="last_name" v-model="userData.lastName">
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="paired_row_step2 error-message">{{validation.firstError('userData.firstName')}}</div>
                            <div class="paired_row_step2 error-message">{{validation.firstError('userData.lastName')}}</div>
                        </div>
                        <template v-if="!isRegistrEmployee">
                            <div class="form_row">
                                <div class="form_row_text">Organization / Company Name</div>
                                <input required :class="{'field-error': validation.hasError('form.name')}" class="form_input" type="text" name="organization" v-model="form.name">
                            </div>
                            <div class="d-flex">
                                <div class="error-message">{{validation.firstError('form.name')}}</div>
                            </div>

                            <div class="form_row">
                                <div class="form_row_text">Industry Category</div>
                                <autocomplete :isError="validation.hasError('indCategorySelect')" :suggestions="industryCategory"
                                fieldName="category"
                                id="category"
                                @suggestionClick="fetchSubcategory"
                                @noSelection="noSelection"
                                :init="initCategory"
                                ></autocomplete>
                            </div>
                            <div class="d-flex">
                                <div class="error-message">{{validation.firstError('indCategorySelect')}}</div>
                            </div>

                            <div class="form_row">
                                <div class="form_row_text">Subcategory</div>
                                <app-select
                                :options="subCategoryList"
                                :isError="validation.hasError('form.subcategory')"
                                dropDownClass="sub-category-list"
                                :disabled="!indCategorySelect"
                                :class="{frm_row_select_inactive: !indCategorySelect}"
                                @changeSelection="changeSelectSubcategory"
                                :initValue="form.subcategory"
                                ></app-select>
                            </div>
                            <div class="d-flex">
                                <div class="error-message">{{validation.firstError('form.subcategory')}}</div>
                            </div>
                        </template>
                        <template v-else>
                            <div class="form_row">
                                <div class="form_row_text">Password</div>
                                <div class="form-input-wrap autocomplete-input w-100">
                                    <input  required :class="{'field-error': validation.hasError('userData.password')}"
                                    class="form_input" type="password"
                                    v-model="userData.password">
                                </div>
                                <div class="d-flex">
                                    <div class="error-message">{{validation.firstError('userData.password')}}</div>
                                </div>
                            </div>
                            <div class="form_row">
                                <div class="form_row_text">ID / Social Security Number</div>
                                <input required :class="{'field-error': validation.hasError('userData.social_number')}" class="form_input" type="text" name="social_number"
                                id="social_code" v-model="userData.social_number">
                            </div>
                            <div class="d-flex">
                                <div class="error-message">{{validation.firstError('userData.social_number')}}</div>
                            </div>
                            <div class="form_row">
                                <div class="form_row_text">Family Status</div>
                                <app-select :options="family_statuses"
                                    :disabled="!!!family_statuses.length"
                                    @changeSelection="changeFamilyStatuses"
                                    dropDownClass="select-dropdown"
                                    :initValue="userData.family_status"
                                ></app-select>
                            </div>
                        </template>
                        <div class="form_row">
                            <div class="form_row_text">Country</div>
                            <div class="form-input-wrap autocomplete-input w-100">
                                <input ref="country" id="country" required :class="{'field-error': validation.hasError('countryName')}" class="form_input" type="text" name="state_region"
                                v-model="countryName">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" width="16px" height="17px" viewBox="0 0 612.08 612.08" style="enable-background:new 0 0 612.08 612.08;" xml:space="preserve" class="search_icon">
                                    <path d="M237.927,0C106.555,0,0.035,106.52,0.035,237.893c0,131.373,106.52,237.893,237.893,237.893   c50.518,0,97.368-15.757,135.879-42.597l0.028-0.028l176.432,176.433c3.274,3.274,8.48,3.358,11.839,0l47.551-47.551   c3.274-3.274,3.106-8.703-0.028-11.838L433.223,373.8c26.84-38.539,42.597-85.39,42.597-135.907C475.82,106.52,369.3,0,237.927,0z    M237.927,419.811c-100.475,0-181.918-81.443-181.918-181.918S137.453,55.975,237.927,55.975s181.918,81.443,181.918,181.918   S338.402,419.811,237.927,419.811z" fill="#93aab5" />
                                </svg>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="error-message">{{validation.firstError('form.country')}}</div>
                        </div>
                        <div class="form_row">
                            <div class="form_row_text">State / Region</div>
                            <div class="form-input-wrap autocomplete-input w-100">
                                <input id="administrative_area_level_1" ref="region" required :class="{'field-error': validation.hasError('form.region')}" class="form_input" type="text" name="state_region"
                                v-model="form.region">
                                 <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" width="16px" height="17px" viewBox="0 0 612.08 612.08" style="enable-background:new 0 0 612.08 612.08;" xml:space="preserve" class="search_icon">
                                    <path d="M237.927,0C106.555,0,0.035,106.52,0.035,237.893c0,131.373,106.52,237.893,237.893,237.893   c50.518,0,97.368-15.757,135.879-42.597l0.028-0.028l176.432,176.433c3.274,3.274,8.48,3.358,11.839,0l47.551-47.551   c3.274-3.274,3.106-8.703-0.028-11.838L433.223,373.8c26.84-38.539,42.597-85.39,42.597-135.907C475.82,106.52,369.3,0,237.927,0z    M237.927,419.811c-100.475,0-181.918-81.443-181.918-181.918S137.453,55.975,237.927,55.975s181.918,81.443,181.918,181.918   S338.402,419.811,237.927,419.811z" fill="#93aab5" />
                                </svg>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="error-message">{{validation.firstError('form.region')}}</div>
                        </div>

                        <div class="form_row">
                            <div class="form_row_text">City</div>
                            <div class="form-input-wrap autocomplete-input w-100">
                                <input ref="city" id="locality" required :class="{'field-error': validation.hasError('form.city')}"
                                    class="form_input" type="text" name="city"
                                    v-model="form.city">
                                 <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" width="16px" height="17px" viewBox="0 0 612.08 612.08" style="enable-background:new 0 0 612.08 612.08;" xml:space="preserve" class="search_icon">
                                    <path d="M237.927,0C106.555,0,0.035,106.52,0.035,237.893c0,131.373,106.52,237.893,237.893,237.893   c50.518,0,97.368-15.757,135.879-42.597l0.028-0.028l176.432,176.433c3.274,3.274,8.48,3.358,11.839,0l47.551-47.551   c3.274-3.274,3.106-8.703-0.028-11.838L433.223,373.8c26.84-38.539,42.597-85.39,42.597-135.907C475.82,106.52,369.3,0,237.927,0z    M237.927,419.811c-100.475,0-181.918-81.443-181.918-181.918S137.453,55.975,237.927,55.975s181.918,81.443,181.918,181.918   S338.402,419.811,237.927,419.811z" fill="#93aab5" />
                                </svg>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="error-message">{{validation.firstError('form.city')}}</div>
                        </div>

                        <div class="form_row">
                            <div class="form_row_text">Street address</div>
                            <div class="form-input-wrap autocomplete-input w-100">
                                <input required :class="{'field-error': validation.hasError('form.street_address')}"
                                class="form_input" type="text" name="street"
                                id="route"
                                ref="address"
                                v-model="form.street_address">
                                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" id="Capa_1" x="0px" y="0px" width="16px" height="17px" viewBox="0 0 612.08 612.08" style="enable-background:new 0 0 612.08 612.08;" xml:space="preserve" class="search_icon">
                                    <path d="M237.927,0C106.555,0,0.035,106.52,0.035,237.893c0,131.373,106.52,237.893,237.893,237.893   c50.518,0,97.368-15.757,135.879-42.597l0.028-0.028l176.432,176.433c3.274,3.274,8.48,3.358,11.839,0l47.551-47.551   c3.274-3.274,3.106-8.703-0.028-11.838L433.223,373.8c26.84-38.539,42.597-85.39,42.597-135.907C475.82,106.52,369.3,0,237.927,0z    M237.927,419.811c-100.475,0-181.918-81.443-181.918-181.918S137.453,55.975,237.927,55.975s181.918,81.443,181.918,181.918   S338.402,419.811,237.927,419.811z" fill="#93aab5" />
                                </svg>
                            </div>
                        </div>
                        <div class="d-flex">
                            <div class="error-message">{{validation.firstError('form.street_address')}}</div>
                        </div>

                        <div class="form_row">
                            <div class="form_row_text">Postal code / Zip</div>
                            <input required :class="{'field-error': validation.hasError('form.zip')}" class="form_input" type="text" name="post_code"
                            id="postal_code" v-model="form.zip">
                        </div>
                        <div class="d-flex">
                            <div class="error-message">{{validation.firstError('form.zip')}}</div>
                        </div>

                        <div class="form_row frm_row_marg_bot">
                            <div class="form_row_text">Phone number</div>
                            <div class="paired_row flex-nowrap form-row__phone">
                                <app-select :options="selFlagsOptions"
                                    @changeSelection="changeSelectFlag"
                                    dropDownClass="flags_drop_down"
                                    selectClass="flags-select"
                                    :hideFirstTxt="true"
                                    :img="true"
                                    ></app-select>
                                <div :class="{'field-error': validation.hasError('phone_number_p2')}" class="d-flex form_input  form_input-phone-wrap flex-nowrap">
                                    <span class="tel-code">{{selectedFlagVal.text}}</span>
                                    <input required  class="form-input__phone" type="tel" id="form_tel_number" name="tel_number" v-model="phone_number_p2" >
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="error-message">{{validation.firstError('phone_number_p2')}}</div>
                            </div>
                        </div>

                    </form>
                </figure>
                <button type="submit" class="submit-btn marg_bot_last" :class="{disabled: submit}" @click="sendData">
                    {{ $router.currentRoute.name == 'Registration' || $router.currentRoute.name == 'RegistEmployee' ? 'Continue' : 'Edit' }}
                </button>
            </div>
        </div>
    </div>
</template>
<script>

import { Validator } from 'simple-vue-validator';
import { mapGetters, mapMutations, mapActions} from 'vuex';
import Vue from 'vue'
import {$eventBus} from '../../../main'
import flagsOption from './flags'
import {loadJs} from '../../Common/utils.js'
export default {
    mixins: [require('simple-vue-validator').mixin],
    validators: {
        'userData.firstName'(value) {
            return Validator.value(value).required();
        },
        'userData.lastName'(value) {
            return Validator.value(value).required();
        },
        'userData.password'(value) {
            if(this.isRegistrEmployee){
               return Validator.value(value).required().minLength(6);
            }else{
                return
            }
        },
        'userData.social_number'(value) {
            if(this.isRegistrEmployee){
               return Validator.value(value).required();
            }else{
                return
            }
        },
        'form.name'(value) {
            if(!this.isRegistrEmployee){
               return Validator.value(value).required();
            }else{
                return
            }
        },
        'indCategorySelect'(value) {
            if(!this.isRegistrEmployee){
               return Validator.value(value).required();
            }else{
                return
            }
        },
        'form.subcategory'(value) {
            if(!this.isRegistrEmployee){
               return Validator.value(value).required();
            }else{
                return
            }
        },
        'form.country'(value) {
            return Validator.value(value).required();
        },
        'form.region'(value) {
            if(this.form.country && this.form.country.is_state){
              return Validator.value(value).required();
            }else{
                return Validator.value(value);
            }
        },
        'form.city'(value) {
            return Validator.value(value).required();
        },
        'form.street_address'(value) {
            return Validator.value(value).required();
        },
        'form.zip'(value) {
            return Validator.value(value).required().integer();
        },
        'phone_number_p2'(value) {
            return Validator.value(value).required().integer();
        },
        'countryName'(value) {
            return Validator.value(value).required();
        },
    },
    props:{
        isStepsHeader:{
            type: Boolean,
            default: true
        },
        isRegistrEmployee: {
            type: Boolean,
            default: false
        },
        employeeData: Object,
        accessToken: String
    },
    data(){
        return {
            indCategorySelect: "",
            subCategoryList: [{value: null, text: "Please Select Category"}],
            id_admin: null,
            selectedFlagVal: {
                value: 1,
                text: '+7 840',
                data_attr: 'Abkhazia'
            },
            selFlagsOptions: flagsOption,
            submit: false,
            userData:{
                firstName: "",
                lastName: "",
                password: '',
                social_number: null,
                family_status: null
            },
            initCategory: null,
            phone_number_p2:"",
            form: {
                name : "",
                subcategory: null,
                region: "",
                city: "",
                street_address: "",
                zip: "",
                country: null
            },
            countryName: '',
            countryCode: null,
            initCountry: null,
            autocompleteRegion: null,
            autocompleteCity: null,
            autocompleteAddress: null,
            editMode: null,
            family_statuses: [],
            image: '',
            files: null,
        }
    },
    mounted(){
        if(!window.google){
            loadJs(`https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&libraries=places&language=${this.language}`, ()=>{
                this.initAutocomplete();
            });
        }else{
            this.initAutocomplete();
        }

    },
    beforeMount() {
        ///получаем данные для списков и id_admin
        let that = this;
        if(this.isRegistrEmployee){
            this.editMode = false;
            this.$watch('accessToken', function(value){
                console.log(value);
                if(value){
                    console.log('fetchCountriesWithAccess');
                    this.fetchCountriesWithAccess(value);
                    this.getFamilyStatusList(value).then((res)=>{
                        let familyStatusList = res.body.map(item=>{
                            return  { value: item.id, text: item.family_status }
                        });
                        let defaultOption = [{value: null, text: 'Please select'}]
                        this.family_statuses = [...defaultOption, ...familyStatusList];
                    })
                }
            }, {immediate: true});
        }else{
            this.step2().then(response=>{
                if(that.registr_step != 2){
                    that.editMode=true;
                    that.fetchCompanyData().then(()=>{
                        if(!$.isEmptyObject(that.company)){
                            that.initCompanyData(that.company);
                        }
                    });
                }else{
                    that.editMode=false;
                }
            });
        }

        this.$auth.fetch({
            success: function(){
                this.id_admin = this.$auth.user().id;
            }
        });
    },
    computed:{
        ...mapGetters(['countries', 'industryCategory', 'registr_step', 'apiKey', 'language', 'company']),
        geoData(){
            return JSON.parse(localStorage.getItem('geoData'));
        },
        avatarPath(){
            if(this.isRegistrEmployee){
                if(this.image && !this.userData.avatar){
                    return this.image;
                }else if(this.userData.avatar){
                    return this.$http.options.root + '/' + this.userData.avatar.path;
                }else{
                    return require('images/user_default.jpg');
                }
            }
        }
    },
    components: {
        appHeader: require('../../Header'),
        appFooter: require('../../Footer'),
        autocomplete: require('../../Common/Autocomplete'),
        appSelect: require('../../Common/Select')
    },
    watch:{
        'form.country': {
            handler(value){
                if(this.validation.hasError('form.country')){
                   this.$validate();
                }
            },
        }
    },
    methods:{
        ...mapActions(['step2', 'fetchCompanyData', 'fetchCountriesWithAccess', 'getFamilyStatusList']),
        ...mapMutations(['setStep', 'setCompanyData']),
        sendAvatar(postImg){
            this.$http.post(`api/v1/companies/${this.employeeData.company_id}/employees/${this.userData.id}/invite_avatars?token=${this.accessToken}`, postImg).then((res)=>{
                this.userData.avatar = res.body.avatar;
            });
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
            this.userData.avatar = null;
            this.files = null;
            this.image = '';
        },
        noSelection(id){
            if(id=="category"){
                this.fetchSubcategory({});
                this.indCategorySelect = "";
            }
            if(id=="country"){
                this.form.country = null;
            }
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
        fetchSubcategory(categoryObj){
            this.indCategorySelect = categoryObj.id;
            let that=this;
            that.subCategoryList=[{value: null, text: "Please Select Category"}];
            if(categoryObj.subcategories){
                that.subCategoryList=[{value: null, text: "Please Select"}];
                $.each(categoryObj.subcategories, function(ind, item){
                that.subCategoryList.push({value: item.id, text: item.subcategory});
                })
            }

        },
        sendData: _.debounce(function(){
            this.$validate().then(success => {
                if(!success) return;
                this.submit=true;
                let formData={
                    "geographical_area[region]": this.form.region,
                    "geographical_area[city]": this.form.city,
                    "geographical_area[street_address]": this.form.street_address,
                    "geographical_area[zip]": this.form.zip,
                    "geographical_area[country]": this.form.country.id
                };
                if(!this.isRegistrEmployee){
                    formData.name = this.form.name;
                    formData.subcategory = this.form.subcategory;
                    let method ="POST";
                    let url = 'api/v1/companies/new';
                    if(this.editMode){
                        method ="PATCH";
                        url = `api/v1/companies/${this.$auth.user().company_id}`;
                    }
                    this.$http({
                        url,
                        body: formData,
                        method,
                        emulateJSON: true
                    }).then(response=>{
                        this.setCompanyData(response.body);
                        this.submit=false;
                        this.$http({
                            url: 'api/v1/users/admins/'+ this.id_admin,
                            body: {
                                first_name: this.userData.firstName,
                                last_name: this.userData.lastName,
                                phone_number: {
                                    country: this.selectedFlagVal.value,
                                    phone_number: +this.phone_number_p2
                                }
                            },
                            method: "PATCH",
                            emulateJSON: true
                        }).then(response=>{
                            if(this.registr_step == 2){
                                this.$auth.fetch();
                                this.setStep(3);
                            }
                        });
                        if(this.$router.currentRoute.name == 'Registration'){
                            this.$router.push({name: "Registration", params: { step : 'step-3'}});
                        }
                    });
                }else{
                    //patch employee request
                    let postImg = new FormData();
                    if (this.files) {
                        postImg.append('avatar[path]', this.files[0], this.files[0].name);
                    }
                    let employeeMessage = {
                        success: false,
                        error: {
                            body: {},
                            status: false
                        }
                    }
                    let userMessage = {
                        success: false,
                        error: {
                            body: {},
                            status: false
                        }
                    }
                    let url = `api/v1/companies/${this.employeeData.company_id}/users/${this.employeeData.employee_type}/invite_edit?token=${this.accessToken}`;
                    let userData = {
                        first_name: this.userData.firstName,
                        last_name: this.userData.lastName,
                        phone_number: {
                            country: this.selectedFlagVal.value,
                            phone_number: +this.phone_number_p2
                        },
                        'plainPassword[first]' : this.userData.password,
                        'plainPassword[second]': this.userData.password,
                    }
                    let userSend = this.$http({
                        url: url,
                        body: userData,
                        method: 'PATCH',
                        emulateJSON: true
                    }).then((res)=>{
                        userMessage.success = true;
                    }, response=>{
                        userMessage.error.body = response.body;
                        userMessage.error.status = true;
                    });
                    let emplData= Object.assign({}, formData);
                    emplData.social_security_number = this.userData.social_number;
                    emplData.family_situation = this.userData.family_status;
                    emplData.phone_number =  {
                        country: this.selectedFlagVal.value,
                        phone_number: +this.phone_number_p2
                    };
                    emplData.first_name = this.userData.firstName;
                    emplData.last_name = this.userData.lastName;
                    emplData.hourly_rate = 0; // временно - обязательное поле
                    let urlEmpl = `api/v1/companies/${this.employeeData.company_id}/employees/invite_new?token=${this.accessToken}`;
                    let employeeSend =  this.$http({
                        url: urlEmpl,
                        body: emplData,
                        method: 'POST',
                        emulateJSON: true
                    }).then((res)=>{
                        this.userData.id = res.body.id;
                        if (this.files) {
                            this.sendAvatar(postImg);
                        }
                        employeeMessage.success = true;
                    }, response=>{
                        employeeMessage.error.body = response.body;
                        employeeMessage.error.status = true;
                    });
                    Promise.all([userSend, employeeSend]).then(()=>{
                        this.$emit('employeeDataSend', {
                            employeeMessage,
                            userMessage
                        });
                    });
                }
                this.validation.reset();
            });
        }, 250),
        changeSelectFlag(option){
            this.selectedFlagVal = option;
        },
        changeSelectSubcategory(option){
            this.form.subcategory = option.value;
        },
        changeFamilyStatuses(option){
            this.userData.family_status = option.value;
        },
        changeSelectCountries(component){
            component.address_components.forEach(address=>{
                let addressType = address.types[0];
                if(addressType=='country'){
                    this.countryName = address['long_name'];
                    this.countryCode = address['short_name'];
                }
            });
            this.countries.forEach(country=>{
                if(country.country_code == this.countryCode){
                    this.form.country=country;
                    this.selectedFlagVal=country.phone_code;
                    $eventBus.$emit('changeSelectCountries', country);
                }
            });
            this.initAutocompleteRestictions();
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
                    let unwatchEdit = this.$watch('editMode', (editVal)=>{
                        if(editVal != null){
                            if(!this.editMode){
                                let unwatch = this.$watch('geoData', (value)=>{
                                        if(!$.isEmptyObject(value)){
                                            this.initGeoData();
                                        }
                                    },
                                    {immediate: true}
                                );
                            }
                        }
                    }, {immediate: true});
                }
            }, {immediate: true});
        },
        initCompanyData(data){
            console.log('init');
            this.$http.get(`api/v1/users/current`)
            .then((res)=>{
                let phone = res.body.phone_number;
                if(data.geographical_area){
                    this.form = {
                        region: data.geographical_area.region,
                        city: data.geographical_area.city,
                        street_address: data.geographical_area.street_address,
                        zip: data.geographical_area.zip,
                        country: data.geographical_area.country
                    };
                    this.countryName=data.geographical_area.country.name;
                    this.countryCode = data.geographical_area.country.country_code;
                    this.selectedFlagVal = data.geographical_area.country;
                    if(!phone){
                    $eventBus.$emit('changeSelectCountries', data.geographical_area.country); 
                    }    
                    if(phone && !phone.country && ~phone.phone_number.indexOf(this.selectedFlagVal.phone_code)){
                        this.phone_number_p2 = phone.phone_number.substr(this.selectedFlagVal.phone_code.length);
                        $eventBus.$emit('changeSelectCountries', data.geographical_area.country);
                    }else if(phone && phone.country){
                        this.selectedFlagVal = phone.country;
                        $eventBus.$emit('changeSelectCountries', phone.country);
                        this.phone_number_p2 =  phone.phone_number;
                    }
                }
                this.form.name = data.name;
                this.form.subcategory = data.subcategory.id;

                this.indCategorySelect = data.subcategory.category.id;
                this.initCategory = data.subcategory.category.id;
                this.userData = {
                    firstName: this.$auth.user().first_name,
                    lastName: this.$auth.user().last_name,
                };
                this.initAutocompleteRestictions();
                this.$emit('initLoad');
            });
        },
        initAutocompleteRestictions(){
            if(this.countryCode){
                this.autocompleteRegion.setComponentRestrictions({'country': this.countryCode});
                this.autocompleteCity.setComponentRestrictions({'country': this.countryCode});
                this.autocompleteAddress.setComponentRestrictions({'country': this.countryCode});
            }
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
                            this.form.city = val;
                            break;
                        case 'administrative_area_level_1':
                            this.form.region = val;
                            break;
                        case 'country':
                            this.countryName = val;
                            break;
                        case 'postal_code':
                            this.form.zip = val;
                            break;
                    }
                }
                if(addressType=='country'){
                    this.countryCode = place.address_components[i]['short_name'];
                }
            }
            this.changeSelectCountries(place);
        },
        changeSelectRegion(component){
             component.address_components.forEach(address=>{
                let addressType = address.types[0];
                if(addressType=='administrative_area_level_1'){
                    this.form.region = address['short_name'];
                }
            });
        },
        changeSelectAddress(component){
            if(component.address_components){
               let route, street_number;
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
                    this.form.street_address = route + ', ' + street_number;
                }else if(route){
                    this.form.street_address = route;
                }else{
                    this.form.street_address= component.name;
                }
            }
        },
        changeSelectCity(component){
             component.address_components.forEach(address=>{
                let addressType = address.types[0];
                if(addressType=='locality'){
                    this.form.city = address['long_name'];
                }
            });
        }
    }
}
</script>
<style lang="scss" src="../style.scss" scoped></style>
<style lang="scss" src="./drop-down.scss"></style>
