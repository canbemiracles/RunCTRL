<template>
    <div class="create-popup-wrap">
        <div class="create-popup d-flex">
            <div class="container">
                <div class="row">    
                    <div class="create-data col-9 d-flex flex-column">
                        <div class="data-title d-flex">
                            <div class="title">{{ data ? 'Edit Station' : 'Create New Station' }}</div>
                            <close-btn  class="btn-close" @click="closeModal"></close-btn>
                        </div>
                        <div class="trial-message d-flex" v-if="trial">
                            <div class="icon-warning-wrap">
                                <svg width="42" class="warning-icon">
                                    <use xlink:href="images/icons-sprite.svg#warning-icon-usage"></use>
                                </svg> 
                            </div>
                            <div class="warning-message">
                                <span class="bold-highlight">Note: </span> free plan allows you to use up to three stations. After trial period ends, you’ll need to upgrade your plan.
                            </div>   
                        </div>
                        <div class="data-content d-flex flex-row">
                            <div class="data-content-left d-flex flex-column col-8">
                                <input v-model.lazy="stationName" @change="activeDevice" class="form-input-name form-input-name__station"
                                    type="text"
                                    placeholder="Station Name"
                                    :class="{'field-error': validation.hasError('stationName')}">
                                
                                <div class="error-message">{{validation.firstError('stationName')}}</div>
                                <div class="set-roles d-flex flex-column">
                                    <div class="set-roles__title" v-show="setRoleLabel">
                                        Set roles
                                    </div>
                                    <div class="set-roles-list-wrap d-flex flex-column">
                                        <draggable class="set-roles-list d-flex flex-wrap" v-model="roles" :move="()=>(false)" :options="{group:'role', animation: 250 }">
                                            <role-item class="set-roles__item"  v-for="(role, ind) in roles"
                                            :key="ind" :name="role.name" :color="role.color" @click="editRole(role, ind)" 
                                            remove="true" @remove="removeRole(ind)"></role-item>
                                        </draggable>
                                    </div>
                                </div>
                                <div class="add-role">
                                    <div class="add-role__inner d-flex">
                                        <add-btn class="add-role__btn" v-if="!color" @click="addRole"></add-btn>
                                        <div class="role-color" v-color:bg="color" v-else @click="colorpick=!colorpick"></div>
                                        <b-form-input v-model="roleName" ref="setRoleInput" class="form-input-name form-input-name__role"
                                        type="text"
                                        placeholder="Add new role"
                                        @focus.native="addRole"
                                        @keydown.enter.native = "setRole">
                                        </b-form-input>
                                        <div class="add-role-btn" v-if="roleName" @click="setRole">
                                            <svg width="23" class="icon-check">
                                                <use xlink:href="images/icons-sprite.svg#check-usage"></use>
                                            </svg>
                                        </div>
                                        <transition name="fade">    
                                            <color-picker v-if="colorpick" class="color-picker" :colors="colors" @chooseColor="chooseColor"></color-picker>  
                                        </transition>
                                    </div>
                                </div>
                            </div>
                            <div class="data-content-right d-flex flex-column col-4">
                                <div class="attach-device d-flex flex-column">
                                    <div class="tip d-flex">
                                        <div class="tip-txt" v-if="!pin">Please enter a name first to attach a device.</div>
                                        <div class="tip-code" v-else>
                                            <div class="tip-txt" >Scan QR or enter number <br> to connect station device</div>
                                            <div class="code">{{pin}}</div>
                                        </div>
                                        <icon-question class="icon-tip"></icon-question>
                                    </div>
                                    <div v-if="!qr" class="device d-flex">
                                        <svg width="100" class="icon-device-qr">
                                            <use xlink:href="images/icons-sprite.svg#device-qr-code-usage"></use>
                                        </svg>
                                    </div>
                                    <div v-else class="qr-code d-flex">
                                        <img :src="qr.image" alt="" class="qr-image">
                                    </div> 
                                </div>
                                <div class="devices-icons">
                                    <div class="devices-icons__inner d-flex" v-if="device">
                                        <div class="device-item d-flex" v-for="(n, ind) in 3" :key="ind">
                                            <svg width="20" height="100%" class="icon-device">
                                                <use xlink:href="images/icons-sprite.svg#ipad-pro-usage"></use>
                                            </svg>
                                            <div class="close-device d-flex">
                                                <close-btn  class="btn-close" ></close-btn>
                                            </div>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="data-footer d-flex flex-column">
                            <button class="btn-create" :class="{disable: disableSend}" @click.prevent="createStaion">{{this.btnText}}</button>
                        </div>
                    </div>
                    <div class="roles col-3">
                        <div class="data-title d-flex">
                            <div class="title">Recommended Roles</div>
                        </div>
                        <div class="roles-wrap">
                            <roles-list :rolesList="recommendRoles" 
                            @chooseRoleTemplate="chooseRoleTemplate"
                            @dragRoleTempl="dragRoleTempl"
                            ></roles-list>
                        </div>
                    </div>
                </div>    
            </div>
        </div>
    </div>
</template>
<script>
import 'images/sprites/device-qr-code.svg'
import 'images/sprites/ipad-pro.svg'
import 'images/sprites/check.svg'
import 'images/sprites/warning-icon.svg'
import { Validator } from "simple-vue-validator";
import draggable from 'vuedraggable'
import {mapActions, mapGetters, mapMutations} from 'vuex'
import {$eventBus} from '../../../../../main'
export default {
    mixins: [require("simple-vue-validator").mixin],
    props:{
        data: Object,
        name: String,
        ind: Number/String,
        branchId: Number,
        plan: String,
        show: Boolean
    },
    data: function(){
        return {
            stationName: "",
            roleName: '',
            disableSend: false,
            colorpick: false,
            device: false,
            color: '',
            roles: [],
            edit_id: null,
            station_id: null,
            qr: null,
            pin: null,
            pin_expire: null,
            editMode: false,
            updateName: false,
            updatedRoles: [],
            deleteRoles: [],
            setRoleLabel: false,
            btnText: 'Create Station',
            recommendRoles: [
                { name: 'Front Manager', color: '9c27b0'}, 
                { name: 'Kitchen Manager', color: '673ab7'}, 
                { name:'Chef', color: '3f51b5'}, 
                { name: 'W W', color: '2196f3'},
                { name: 'Super manager', color: '00bcd4'}
                ],
            colors: [
                '9c27b0',
                '673ab7',
                '3f51b5',
                '2196f3',
                '00bcd4',
                '4caf50',
                '8bc34a',
                'cddc39',
                'fcbf07'
            ],
            expireIntervalFunc: null
        }
    },
    validators: {
        "stationName"(value) {
            return Validator.value(value).required()
        },
    },
    computed:{
        trial(){
            if(this.plan == 'free'){
               return  true;
            }else{
                return false;
            }
        }
    },
    mounted(){
        console.log('mounted');
        $eventBus.$on('stationSaved', (data)=>{
            if(this.show){
                this.station_id = data.id;
                let updatedata = {
                    id: data.id,
                    name: this.stationName,
                    roles: this.data ? this.data.roles : [], 
                }
                this.setStation(updatedata);
                this.qr = data.qr;
                this.pin = data.pin.pin;
                this.pin_expire = data.pin.pin_expire;     
            }
        });
    },
    watch:{
        stationName(newVal, oldValue){
             if(this.data){
                if(newVal !==this.data.name){
                    this.updateName=true;
                }
             }else{
                if(oldValue){
                   this.updateName=true; 
                }
             }
        },
        pin: {
            handler: function(value){
                if(value){
                    this.disableSend=false;
                }else{
                    this.disableSend=true;
                }
            },
            immediate: true
        },
        roles(value){
            if(value.length){
                this.setRoleLabel=true;
            }else{
                this.setRoleLabel=false;
            }
        },
        show(val){
            if(val){
                this.initData();
            }
        }
    },
    methods:{
        ...mapActions(['createQR', 'getPIN']),
        ...mapMutations(['setStation']),
        createStaion(){
            this.$validate().then(success => {
                if(!success) return;
                if(this.disableSend) return;
                let stat_id = this.station_id ? this.station_id : this.ind;
                let sendDataRoles=[];
                if(this.data){
                    let oldDataRolesArr = this.data.roles.map( ({role_id}) =>(role_id));
                    let newRolesArr = this.roles.filter(({ role_id })=>{ return oldDataRolesArr.indexOf(role_id) == -1 });
                    sendDataRoles = [...newRolesArr, ...this.updatedRoles]; 
                }else{
                   sendDataRoles = this.roles; 
                }
                
                if(this.updateName){
                    this.$emit('editStation', { name: this.stationName, station_id: stat_id});
                }
                if(sendDataRoles.length){
                   this.$emit('editStation', { roles: sendDataRoles, station_id: stat_id});
                }
                if(this.deleteRoles.length){
                    this.deleteRoleRequest(this.deleteRoles, stat_id);
                }
                this.$emit('createStation', this.roles, this.stationName, stat_id);
                this.clearData();
                this.$emit('closeModal');
            });     
        },
        chooseColor(color){
            this.color = color;
            this.colorpick = false;
            this.$refs.setRoleInput.focus();
        },
        chooseRoleTemplate(role){
            this.roles.push({
                color: role.color,
                name: role.name,
                ind: this.roles.length
            });
        },
        addRole(){
            if(!this.color){
               this.color=this.colors[~~(Math.random() * this.colors.length)]; 
            }
        },
        setRole(){
            if(this.roleName && this.edit_id == null){
                this.roles.push({
                    color: this.color,
                    name: this.roleName,
                    ind: this.roles.length
                })
                this.color='';
                this.roleName="";
            }
            if(this.roleName && this.edit_id != null){
                this.roles[this.edit_id].name = this.roleName;
                this.roles[this.edit_id].color = this.color;
                if(this.data && this.data.roles[this.edit_id]){
                    if( this.data.roles[this.edit_id].name != this.roleName 
                        || this.data.roles[this.edit_id].color != this.color){
                        this.updatedRoles.push(this.roles[this.edit_id]);
                    } 
                }
                this.edit_id = null;
                this.color='';
                this.roleName="";
            }
           
        },
        editRole(role, id){
            this.roleName = role.name;
            this.edit_id = id;
            this.color = role.color;
        },
        removeRole(ind){
            if(this.roles[ind].role_id){
               this.deleteRoles.push(this.roles[ind]); 
            }
            this.roles = this.roles.filter((item, index)=>index!=ind);
        },
        deleteRoleRequest(delArr, station_id){
            delArr.forEach(element => {
                this.$http.delete(`api/v1/branches/${this.branchId}/stations/${station_id}/origin_roles/${element.role_id}`);
            });
        },
        activeDevice(){
            if(!this.qr){
                console.log('activeDevice addStation');
                this.$emit('addStation', {name: this.stationName, id: this.ind});
            }
        },
        closeModal(){
            if(this.stationName && !this.pin) return;
            this.$emit('closeModal', { 
                id: this.station_id ? this.station_id : this.ind,
                roleColor: this.color, 
                roleName: this.roleName,
                tempRoles: !_.isEmpty(this.data) ?  _.differenceBy(this.roles, this.data.roles, 'ind') : this.roles,
                tempStationName: this.stationName
            });
            if(this.station_id){
                setTimeout(()=>{
                   this.clearData();
                   this.validation.reset(); 
                }, 2000);
            }
        },
        dragRoleTempl(move){
            if(move=='start'){
                this.setRoleLabel=true;
            }else if(move=='end'){
                if(!this.roles.length){
                    this.setRoleLabel=false;
                }
            }
            
        },
        checkAndReplaceExpiredPin(expire){
            let timeExpire = moment(moment.utc(expire).toISOString(true));
            if(moment().isSameOrAfter(timeExpire)){
                console.log('fetchPin');
                this.fetchPIN();
            }
        },
        fetchPIN(){
            this.getPIN(
                {
                    branch_id: this.branchId,
                    station_id: this.station_id 
                }
            ).then(response=>{
                    this.pin = response.pin;
                    this.pin_expire = response.pin_expire;
                    this.data.pin = response.pin.pin;
                    this.data.pin_expire = response.pin.pin_expire;
            });
        },
        clearData(){
            this.stationName = "";
            this.roleName = '';
            this.disableSend = false;
            this.colorpick = false;
            this.device = false;
            this.color = '';
            this.roles = [];
            this.edit_id = null;
            this.station_id = null;
            this.qr = null;
            this.pin = null;
            this.pin_expire = null;
            this.editMode = false;
            this.updateName = false;
            this.updatedRoles= [];
            this.deleteRoles = [];
            this.setRoleLabel = false;
            this.btnText = 'Create Station';
            this.expireIntervalFunc = null;
        },
        initData(){
            if(this.data){
                this.editMode=true;
                var cloneRoles = this.data.roles.map(a => ({...a}));
                if(this.data.tempRoles && this.data.tempRoles.length){
                    cloneRoles = [...cloneRoles, ...this.data.tempRoles];
                }
                this.roles = cloneRoles; 
                this.stationName = this.data.tempStationName ?  this.data.tempStationName : this.data.name;
                this.color = this.data.roleColor ?  this.data.roleColor : '';
                this.roleName = this.data.roleName ?  this.data.roleName : '';
                this.station_id = this.ind;
                if(this.data.qr){
                    this.qr = this.data.qr;
                }
                if(this.data.pin){
                    if(_.isObject(this.data.pin)){
                        this.pin = this.data.pin.pin;
                        this.pin_expire = this.data.pin.pin_expire;
                    }else{
                        this.pin = this.data.pin;
                        this.pin_expire = this.data.pin_expire;
                    }
                }
                this.btnText = "Edit Station"
            }
            if(this.stationName){
                console.log(this.pin_expire);
                if(this.pin_expire){
                    this.expireIntervalFunc = setInterval(()=>{
                        this.checkAndReplaceExpiredPin(this.pin_expire);
                    }, 1000); 
                }else{
                    this.fetchPIN();
                }
                if(!this.qr){
                    this.createQR({
                        branch_id: this.branchId,
                        station_id: this.station_id 
                    }).then(response=>{
                        this.qr = response;
                    });
                }
            }
        }
        
    },
    beforeDestroy(){
		clearInterval(this.expireIntervalFunc);
	},
    components:{
        closeBtn: require('../../../../Common/CloseBtn'),
        rolesList: require('../RolesList'),
        iconQuestion: require('../../../../Common/IconQuestion'),
        addBtn: require('../../../../Common/ButtonPlus'),
        colorPicker: require('../ColorPicker'),
        roleItem: require('../RolesList/RolesItem'),
        draggable
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
