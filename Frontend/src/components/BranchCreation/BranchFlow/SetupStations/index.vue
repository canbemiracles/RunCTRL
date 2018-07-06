<template>
    <section class="slide-section-container d-flex">
        <div class="slide-content d-flex flex-column stations" >
            <div class="section-header d-flex">
                <div class="text-left">Create branch</div>
                <div class="title">Set Up Stations</div>
            </div>
            <stations-list ref="stationsList" 
            @addStation="addStation"
            @editStation="editStation"
            :stations="stations" 
            :branchId="branchId"

            ></stations-list> 
        </div>    
        <recommend>
            <div slot="recommend">
                <div class="text">
                    Analytics only available on paid plans. Please upgrade your account to see.
                </div>
                <div class="recomm-list">
                    <draggable v-model="templatesList" 
                    @end="endDragStation" 
                    :clone="cloneTemplate" 
                    :options="dragOptions">
                        <recom-template 
                            v-for="(item, ind) in templatesList" :key="ind"
                            @chooseRecStation="chooseRecStation"
                            :recommend="item">
                        </recom-template>
                    </draggable> 
                </div>   
            </div>
        </recommend>
    </section>
</template>
<script>
import draggable from 'vuedraggable';
import {$eventBus} from '../../../../main';
import {mapState, mapMutations, mapGetters, mapActions} from 'vuex';
export default {
    props: ['branchId', 'stations'],
    data: ()=>({
        templatesList: [],
        dragOptions:{
            group:{ 
                name: 'stations',  
                pull: 'clone',
            },
            chosenClass: 'recom-drag' 
        }
    }),
    computed: {
        ...mapState({
            currentid: (state) => (state.branchStations.currentid),
        }),
        ...mapGetters(['company']),
    },
    mounted(){
        this.getRecommendStations();
    },
    methods:{
        ...mapMutations(['incrementIdStation']),
        ...mapActions(['getCompanyData']),
        endDragStation(evt){
            let data=this.templatesList[evt.oldIndex];
            data.id=this.currentid;
            this.$refs.stationsList.endDragStation(data);
        },
        cloneTemplate(original){
            this.incrementIdStation();
            let copy = _.cloneDeep(original);
            copy.id= this.currentid;
            return copy;
        },
        getRecommendStations(){
            
           let userData = this.$auth.user();
           let  category_id,
                subcategory_id; 
            this.getCompanyData().then(response => {
                console.log('getRecommend');
               category_id = response.subcategory.category.id;
               subcategory_id = response.subcategory.id;
            }).then(()=>{
                this.$http.get(`api/v1/industry_categories/${category_id}/subcategories/${subcategory_id}/recommendations_stations/`).then(response=>{
                    let templatesList = response.body;
                    templatesList.forEach(element => {
                        this.templatesList.push({
                            id: element.id,
                            name: element.name,
                            roles: element.recommendations_roles,
                            color: element.color
                        });
                    });
                });
            });
        },
        chooseRecStation(station){
            this.$refs.stationsList.chooseRecStation(station);
        },
        addStation(station){
            this.$emit('saveStation', station);
        },
        editStation(data){
            this.$emit('editStation', data);
        }
    },
    components:{
        stationsList: require('./StationsList'),
        recommend: require('../../../SidebarRecommend'),
        recomTemplate: require('../../../SidebarRecommend/RecommendTemplate'),
        draggable
    }
}
</script>
<style lang='scss' scoped>
.recomm-list{
    margin-top: 12px;
}
.stations{
    position: relative;
}
.recom-drag{
    height: 205px;
    width: 200px;
    display: block;
}
</style>