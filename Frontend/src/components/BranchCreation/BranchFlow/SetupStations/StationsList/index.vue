<template>
    <div class="conatiner-fluid p-0 d-flex flex-column station-container">
        <draggable class="drag-wrap row flex-column" v-model="stationList" :move="()=>(false)" :options="dragOptions" >
            <transition-group tag="div" class="dragArea d-flex flex-wrap col-12" name="list-complete">
                <station v-for="station in stationList" :key="station.id"
                :station="station"
                @addStation="addStation"
                @editStation="editStation"
                @removeStation="deleteStation"
                ></station>
            </transition-group>
        </draggable>
        <transition name="fade">
            <create-station v-show="modalShow"
            :show="modalShow" 
            @closeModal="closeModal" 
            @addStation="saveStation"
            @editStation="editStationRequest"
            :data="renderDataStation" 
            @createStation="createStation"
            :ind="currentStationId"
            :branchId="branchId"
            :plan="tarifPlan"
            ></create-station>
        </transition>
    </div>
</template>
<script>
import {mapMutations, mapGetters, mapActions} from 'vuex';
import draggable from 'vuedraggable';
import {$eventBus} from '../../../../../main';
export default {
    props: ['branchId', 'stations'],
    data: function(){
        return{
            modalShow: false,
            renderDataStation: null,
            stationName: '',
            currentStationId: null,
            emptyStations: true,
            dragOptions: {
                group: { 
                    name: 'stations',
                }, 
                sort: false,
                draggable: "",
                animation: 250,
            },
            tarifPlan: null
        }
    },
    created(){
        if(this.$router.currentRoute.name == 'branchFlow'){
            this.clearStationsList();
        }
        this.isEmptySations();
        this.getCompanyData().then((company)=>{
            this.tarifPlan = company.plan;
        }); 
    },
    computed:{
       ...mapGetters(['getStation']),
       stationList:{
            get(){
               return this.$store.getters.stationList
            },
            set(value) {
                this.$store.commit('updateStationList', value);
                console.log('updateStationList');
            }
       } 
    },
    methods:{
        ...mapMutations(['setStation','setTempDataStation', 'setEmpty', 'removeStation', 'updateStationList']),
        ...mapActions(['deleteStationRequest', 'getCompanyData', 'clearStationsList']),
        createStation(roles, name, id){
            let data={ 
                    id, 
                    roles,
                    name, 
            }
            this.setStation(data);
        },
        chooseRecStation(item){
            this.stationList.push(item);
            console.log('chooseRECstation');
            this.endDragStation(item);
        },
        endDragStation(item){
            this.$emit('addStation', item);
            if(this.emptyStations.length>1){
                this.removeStation(this.emptyStations[0].id);
                this.emptyStations = this.emptyStations.slice(1);
            }
        },
        saveStation(station){
            this.$emit('addStation', station);
        },
        addStation(id){
            this.currentStationId = id;
            this.modalShow=true;
        },
        deleteStation(id){
            this.removeStation(id);
            this.deleteStationRequest({branch_id: this.branchId, station_id: id});
            if(this.stationList.length<3 && this.emptyStations.length==1 ||
            this.stationList.length==this.emptyStations.length){
                this.setEmpty();
                this.isEmptySations();
            }
        },
        editStation(id){
            this.currentStationId = id;
            let station = this.getStation(id);
            this.renderDataStation = {
                roles: station.roles,
                name: station.name,
                color: station.color,
                roleName: station.roleName,
                tempRoles: station.tempRoles,
                tempStationName: station.tempStationName,
                pin: station.pin,
                pin_expire: station.pin_expire,
                qr: station.qr
            };
            this.modalShow=true;
        },
        closeModal(tempdata){
            this.modalShow=false;
            this.renderDataStation = null;
            if(tempdata){
              this.setTempDataStation(tempdata);  
            }
            this.isEmptySations(); 
        },
        isEmptySations(){
            let empty=[];
            this.stationList.forEach(item=>{
                if(!item.name){
                    empty.push(item);
                }
            });
            if(empty.length){
                this.emptyStations =  empty;
            }else{
                this.emptyStations = false;
            }
        },
        initStationsList(stations){
            let initArr = [];
            stations.forEach((station) =>{
                
                let item = {
                    id: station.id,
                    name: station.name,
                    pin: station.pin,
                    pin_expire: station.pin_expire,
                    roles: []
                }
                station.origin_roles.forEach((role, index)=>{
                    item.roles.push({
                        color: role.color,
                        ind: index,
                        name: role.role,
                        role_id: role.id
                    });
                });
                initArr.push(item);
                // let initArrcopy = _.cloneDeep(initArr);
                this.updateStationList(initArr);
            });
        },
        editStationRequest(data){
            console.log('editStation');
            this.$emit('editStation', data);
        },
    },
    watch:{
        emptyStations(value){
            if(!value.length){
                this.setEmpty();
            }
            
        },
        stationList(stations){
            console.log('stationList watch');
            if(stations.length<3 && this.emptyStations.length==1 ){
                    this.setEmpty();
            }
            this.isEmptySations();
        },
        stations(value){
            console.log('station watch');
            if(value.length){
                this.initStationsList(value);
            } 
        }
        
    },
    components:{
        station: require('../StationItem'),
        createStation: require('../CreateStation'),
        draggable
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>