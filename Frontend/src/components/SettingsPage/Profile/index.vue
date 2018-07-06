<template lang="html">
    <div class="wrapper">        
        <div class="col col-md-6">
            <h2>Profile</h2>
            <div class="form_row flex-nowrap">
                <div class="paired_row paired_row_step2">
                    <div class="form_row_text">First Name</div>
                    <input class="form_input" required type="text" name="first_name" v-model="user.first_name" @change="patch('first_name')" >
                </div>
                <div class="paired_row paired_row_step2">
                    <div class="form_row_text">Last Name</div>
                    <input required class="form_input" type="text" name="last_name" v-model="user.last_name" @change="patch('last_name')">
                </div>
            </div>
            <div class="form_row">
                <div class="form_row_text">Email</div>
                <input class="form_input" type="text" name="email" v-model="user.email" @change="patch('email')">
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
                    <div  class="d-flex form_input  form_input-phone-wrap flex-nowrap">
                        <span class="tel-code">{{ selectedFlagVal.text }}</span>
                        <input required  class="form-input__phone" type="tel" id="form_tel_number" name="tel_number" 
                        v-model="user.phone_number.phone_number"  @change="patch('phone_number')">
                    </div>
                </div>
                <div class="d-flex">
                    <div class="error-message"></div>
                </div>
            </div>
            <div class="form_row">
                <div class="form_row_text">New Password</div>
                <input class="form_input" type="password" name="newpassword">
            </div>
        </div>
        <preloader :show="showPreloader"/>
    </div>    
</template>

<script>
import flagsOption from '../../Registration/Step2/flags';
import { mapGetters, mapActions } from 'vuex';
import {$eventBus} from '../../../main'
export default {
    data: () => ({
        selFlagsOptions: flagsOption,
        selectedFlagVal: '',
        user: {
            phone_number:{}
        },
        countries: [],
        initLoad: false,
        showPreloader: false
    }),
    computed: {
        ...mapGetters(['company'])
    },
    methods: {
        ...mapActions(['getCompanyData']),
        changeSelectFlag(option){
            this.selectedFlagVal = option;
            this.user.phone_number.country = option;
            this.patch('phone_number');
        },
        patch(field) {
            if(this.initLoad){
                delete this.user.phone_number.id;
                let data = _.cloneDeep(this.user);
                data.phone_number.country = data.phone_number.country.id;
                this.$http.patch(`api/v1/users/admins/${this.user.id}`, {[field]: data[field]});
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
    },
    created() {
        this.showPreloader = true;
        this.$http.get(`api/v1/users/current`).then((response)=>{
            console.log('userData');
            this.user = response.body;
            this.$http.get(`api/v1/countries/`).then(res => {
                this.countries = res.body;
                this.fetchFlagsList();
                this.getCompanyData().then((data)=>{
                    console.log('getCompany');
                    if(this.user.phone_number && !this.user.phone_number.country){
                        if(data.geographical_area && data.geographical_area.country){
                            $eventBus.$emit('changeSelectCountries', data.geographical_area.country); 
                            if(~this.user.phone_number.phone_number.indexOf(this.selectedFlagVal.phone_code)){
                                this.user.phone_number.phone_number = this.user.phone_number.phone_number.substr(this.selectedFlagVal.phone_code.length);
                            }
                        }
                    }
                    if(this.user.phone_number && this.user.phone_number.country){
                        this.selectedFlagVal = this.user.phone_number.country;
                        console.log(this.user.phone_number.country);
                        $eventBus.$emit('changeSelectCountries', this.user.phone_number.country);
                        this.user.phone_number.phone_number =  this.user.phone_number.phone_number;
                    }
                    if(!this.user.phone_number){
                        this.user.phone_number = {
                            country: '',
                            phone_number: ''
                        }
                    }
                    this.initLoad = true;
                    this.showPreloader = false;
                });
            });
        });
    },
    beforeDestroy(){
        console.log('beforeDestroy profile');
        console.log(this.user.phone_number.country);
    },
    components: {
        appSelect: require('../../Common/Select'),
        preloader: require('../../Common/Preloader')
    }
}
</script>

<style lang="scss" src="../../Registration/style.scss" scoped></style>
<style lang="scss" src="../../Registration/Step2/drop-down.scss"></style>
<style lang="scss" src="./style.scss" scoped></style>
