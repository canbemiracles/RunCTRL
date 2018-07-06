<template>
    <section class="slide-section-container d-flex">
        <div class="slide-content d-flex flex-column" >
            <div class="slide-content-inner">
                <div class="section-header d-flex">
                    <div class="text-left">Create branch</div>
                    <div class="title">Branch Shifts</div>
                    <button class="delete-btn" @click="deleteBranch" v-if="$route.params.id">Delete Branch</button>
                </div>
                <shift-list :shifts="shifts"></shift-list>
            </div>
        </div>
        <recommend>
            <div slot="recommend">
                Analytics only available on paid plans. Please upgrade your account to see.
            </div>
        </recommend>
    </section>
</template>
<script>
import {mapActions} from 'vuex';
export default {
    props: {
        shifts: Array
    },
    methods: {
        deleteBranch(){
            this.$emit('deleteBranch');
        }
    },
    components:{
        shiftList: require('./ShiftList'),
        recommend: require('../../../SidebarRecommend'),
    }
}
</script>
<style lang="scss" scoped>
.delete-btn{
    border-radius: 20px;
    border: none;
    display: flex;
    padding: 0 14px;
    text-align: center;
    align-items: center;
    height: 30px;
    text-transform: uppercase;
    color: #627680;
    font-size: 13px;
    font-family: "Roboto-Bold", sans-serif;
    transition: all ease 300ms;
    &:hover, &:focus{
        text-decoration: none;
        border: none;
        outline: none;
    }
    background-color: #f97474;
    color: #fff;
    &:hover{
        background-color: lighten($cancelRed, 5);
    }
}

</style>
