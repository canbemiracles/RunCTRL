<template>
    <section class="slide-section-container no-margin d-flex flex-column">
        <div class="slide-content d-flex flex-column">
            <div class="section-header d-flex">
                <div class="title">Branch Data</div>
            </div>
            <div class="branch-data-container">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-6 branch-item d-flex">
                            <div class="branch-input" :class="{'field-error': validation.hasError('address')}">
                                <input v-model="address" ref="address" class="branch-form-input"
                                    type="text"
                                    placeholder="Enter Branch Address…">
                            </div>
                            <div class="error-message">{{validation.firstError('address')}}</div>
                        </div>
                        <div class="col-6 branch-item d-flex">
                            <div class="branch-input">
                                <input v-model="region" ref="region" class="branch-form-input"
                                    type="text"
                                    placeholder="Region">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <employees-search
                        title="Branch Manager"
                        :suggestions="managers"
                        :branchId="branchId ? branchId : branchData ?  branchData.id : branchData"
                        @selected="selectedManager"
                        ></employees-search>
                        <employees-search
                        title="Supervisor"
                        :suggestions="supervisers"
                        :branchId="branchId ? branchId : branchData ?  branchData.id : branchData"
                         @selected="selectedSupervisor"
                        ></employees-search>
                    </div>
                    <div class="col-12 branch-item-bottom" >
                        <div class="data-title">
                            Emergency Phones
                        </div>
                        <div class="row phones-row">
                            <branch-phones 
                            @inputPhone="inputPhone" 
                            @inputValid="inputValid"
                            :branch="branchData"></branch-phones>
                        </div>
                    </div>          
                </div>
                <div class="create-branch-button-wrap">
                    <button class="btn-create" @click="saveBranch">{{ buttonTitle }}</button>
                </div>
            </div>
        </div>
        <app-footer></app-footer>   
    </section>
</template>
<script>
import { mapActions, mapGetters } from 'vuex';
import { Validator } from "simple-vue-validator";
import { loadJs } from '../../../Common/utils.js'
export default {
    mixins: [require("simple-vue-validator").mixin],
    props:{
        branchData: Object,
        branchId: Number
    },
    data: function() {
        return {
            address: "",
            region: "",
            managers: [],
            supervisers:[],
            searchManager: {},
            searchSupervisor: {},
            phones: {
                police: {type: 'police', phone: ''},
                fire: {type: 'fire', phone: ''},
                ambulance: {type: 'ambulance', phone: ''},
            },
            autocompleteAddress: null,
            autocompleteRegion: null,
            phoneValid: true
        };
    },
    validators: {
        "address"(value) {
            return Validator.value(value).required();
        },
    },
    computed:{
        ...mapGetters(['apiKey', 'language']),
        buttonTitle(){
            return this.branchData ? 'edit branch' : 'create branch';
        }
    },
    mounted() {
        
        if(!(window.google && google.maps.places)){
            loadJs(`https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&libraries=places&language=${this.language}`, ()=>{
                 this.initAutocomplete();
            });
        }else{
             this.initAutocomplete();
        }
        if(this.$router.currentRoute.name == 'branchFlowEdit'){
            this.$watch('branchData', function(branch){
                if(!_.isEmpty(branch)){
                    this.getSupervisors().then(res=>{
                        this.supervisers = res.filter((supervisor) => {
                            return supervisor.confirmed && supervisor.id != branch.supervisor.id;
                        });
                       
                        if(!_.isEmpty(branch.supervisor)){
                            this.supervisers = [branch.supervisor, ...this.supervisers]
                            this.selectedSupervisor(branch.supervisor.id);
                        }  
                    });
                    this.getManagers().then(res=>{
                        this.managers = res.filter(empl =>{
                            return (empl.branch_id == null)
                        });
                        if(!_.isEmpty(branch.branch_manager)){
                            this.managers = [branch.branch_manager, ...this.managers];
                            this.selectedManager(branch.branch_manager.id);
                        }
                    });
                    if(branch.geographical_area){
                        this.address = branch.geographical_area.street_address;
                        this.region = branch.geographical_area.region;
                    }
                }
            }, { immediate: true} );
        }else{
            this.getSupervisors().then(res=>{
                this.supervisers = res.filter((supervisor) => {
                    return supervisor.confirmed;
                });
            });
            this.getManagers().then(res=>{
                this.managers = res.filter(empl =>{
                    return (empl.branch_id == null)
                });
            });
        }
    },
    methods:{
        ...mapActions(['getSupervisors', 'getManagers', 'detachEmployeeBranch']),
        selectedManager(id){
            let that = this;
            this.managers.forEach(element => {
                if(element.id==id){
                    that.$set(element, 'selected', true);
                    this.searchManager=element;
                }else{
                that.$set(element, 'selected', false);  
                }
            });
            
        },
        selectedSupervisor(id){
            let that = this;
            this.supervisers.forEach(element => {
                if(element.id==id){
                    that.$set(element, 'selected', true);
                    this.searchSupervisor=element;
                }else{
                that.$set(element, 'selected', false);  
                }
            });
        },
        inputPhone(number, type){
            this.phones[type].phone = number;
        },
        inputValid(val){
            this.phoneValid = val;
        },
        saveBranch(){
            this.$validate().then(success => {
                if(!success) return;
                if(!this.phoneValid) return;
                this.$emit('createBranch', {
                    address: this.address,
                    region: this.region,
                    manager: this.searchManager,
                    supervisor: this.searchSupervisor,
                    phones: this.phones,
                });
            });
        },
        changeSelectRegion(component){
            if(component.address_components){
                component.address_components.forEach(address=>{
                    let addressType = address.types[0];
                    if(addressType=='administrative_area_level_1'){
                        this.region = address['short_name'];
                    }
                }); 
            }
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
                    if(addressType=='administrative_area_level_1'){
                        this.region = address['short_name'];
                    }
                });
                if(route && street_number){
                    this.address = route + ', ' + street_number; 
                }else if(route){
                    this.address = route;
                }else{
                    this.address=component.name;
                }
            }
        },
        initAutocomplete() {
            this.autocompleteAddress = new google.maps.places.Autocomplete(this.$refs.address,
            {
                types: ['geocode'],
            });
            this.autocompleteAddress.addListener('place_changed', ()=>{
                let address = this.autocompleteAddress.getPlace(); 
                this.changeSelectAddress(address); 
            });       
            this.autocompleteRegion = new google.maps.places.Autocomplete(this.$refs.region,
            {
                types: ['(regions)'],
            });
            this.autocompleteRegion.addListener('place_changed', ()=>{
                let region = this.autocompleteRegion.getPlace(); 
                this.changeSelectRegion(region); 
            });     
        },

    },
    components: {
        branchPhones: require("../../../BranchPhones"),
        employeesSearch: require('./EmployeesSearch'),
        appFooter: require('../../../Footer'),
    }
};
</script>
<style lang='scss' src='./style.scss' scoped></style>