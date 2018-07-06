<template>
    <transition-group tag="DIV" name="slide" class="shifts-list">
        <shift-item 
        v-for="shift in shift_groups"
        :shiftId="shift.shift_id"
        :key="shift.shift_id"
        ></shift-item> 
    </transition-group>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
export default {
    props:{
        shifts: {
            type: Array
        }
    },
    data: function(){
        return{
           
        }
    },
    created(){
        if(this.$router.currentRoute.name == 'branchFlow'){
            this.initEmptyShiftGroup();
        }
    },
    mounted(){
        this.$watch('shifts', function(value){
            if(value && value.length){
                this.fetchShifts(value);  
            }
        }, 
        { immediate: true });
    },
    computed:{
        ...mapGetters(['shift_groups'])
    },
    methods:{
        ...mapActions(['fetchShifts', 'initEmptyShiftGroup']),
    },
    components:{
        shiftItem: require('../ShiftItem')
    }
}
</script>
<style lang="scss" scoped>
.slide-enter{
    opacity: 0;
}
.slide-enter-active{
    animation: slide-in .4s ease-out forwards;
    transition: opacity .4s;
}
.slide-leave-active{
    animation: slide-out .4s ease-out forwards;
    transition: opacity .4s;
    opacity: 0;
    position: absolute; 
}
.slide-move{
   transition: transform .4s; 
}
@keyframes slide-in{
    from{
        transform: translateY(20px);
    }
    to{
        transform: translateY(0);
    }
}
@keyframes slide-out{
    from{
        transform: translateY(0);
    }
    to{
        transform: translateY(20px);
    }
}
</style>
