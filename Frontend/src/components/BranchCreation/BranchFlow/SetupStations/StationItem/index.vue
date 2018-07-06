<template>
    <div class="col-md-4 d-flex">
        <div class="station d-flex" v-if="!station.name" @click="addStation">
            <div class="station-inner d-flex flex-column">
                <add-button class="add-station"></add-button> 
                <div class="label-button">
                    Add Station
                </div>
            </div>
        </div>
        <div class="station d-flex" v-else @click="editStation">
            <div class="station-data-inner d-flex flex-column">
                <div class="station-data-content">
                    <div class="station-name">
                        {{station.name}} 
                    </div>
                    <div class="station-roles-list d-flex">
                        <role-item class="station-roles__item"  
                        v-for="(role, ind) in station.roles"
                        :key="ind" :name="role.name" 
                        :color="role.color"></role-item>
                    </div>
                </div>
                <div class="station-devices d-flex" v-if="false">
                    <div class="device-item d-flex" v-for="(n, ind) in 3" :key="ind">
                        <svg width="20" height="100%" class="icon-device">
                            <use xlink:href="images/icons-sprite.svg#ipad-air-usage"></use>
                        </svg>   
                    </div>
                </div>
            </div>
            <close-btn class="close-station" @click="removeStation"></close-btn>
        </div>
    </div>
</template>
<script>
import 'images/sprites/ipad-air.svg'
export default {
    props:{
        station: Object,
    },
    data: function(){
       return{
            
       } 
    },
    methods:{
        addStation(){
           this.$emit('addStation', this.station.id); 
        },
        editStation(){
            this.$emit('editStation', this.station.id);
        },
        removeStation(){
            this.$emit('removeStation', this.station.id);
        }
    },
    components:{
        addButton: require('../../../../Common/ButtonPlus'),
        roleItem: require('../RolesList/RolesItem'),
        closeBtn: require('../../../../Common/CloseBtn')
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>