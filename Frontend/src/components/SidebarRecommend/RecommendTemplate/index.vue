<template>
    <div class="template d-flex" @click="chooseStation">
        <svg width="13" height="19" class="icon">
            <use xlink:href="images/icons-sprite.svg#ipad-mini-usage"></use>
        </svg>
        <div class="text">{{recommend.name}}</div>  
    </div>
</template>
<script>
import 'images/sprites/ipad-mini.svg'
import {$eventBus} from '../../../main';
import {mapMutations, mapState} from 'vuex';
export default {
    props:{
        recommend: Object
    },
    data:()=>{
        return {
            disabled: false
        }
    },
    computed: mapState({
        currentid: (state) => (state.branchStations.currentid),
    }),
    methods:{
         ...mapMutations(['incrementIdStation']),
        chooseStation: _.debounce(function(e){
                let copy = _.cloneDeep(this.recommend);
                this.incrementIdStation();
                copy.id= this.currentid;
                console.log('chooseStation');
                this.$emit('chooseRecStation', copy); 
        }, 250),
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>