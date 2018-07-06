<template>
    <div class="top-info">
        <div class="container">
            <div class="row">
                <div class="col col-12 info-row d-flex flex-row p-25-30">
                    <div class="adress" v-if="geo">
                        {{geo.street_address}}
                    </div>
                    <div class="buttons-group">
                        <!-- <button class="delete-btn" @click="showModal">Delete Branch</button> -->
                        <router-link tag="button" class="edit-btn" :to="{ name: 'branchFlowEdit', params: { id: branchId } }" >Edit Branch</router-link>
                    </div>
                </div>
            </div>

            <div class="row info-row" v-if="manager || supervisor">
                <div class="col col-6 p-25-30 info-col">
                    <div class="user d-flex" v-if="manager">
                        <div class="user__picture" :style="{backgroundImage: 'url(' + (manager.avatar ? $http.options.root +'/'+ manager.avatar.path : require('images/user_default.jpg')) +')' }"></div>
                        <div class="user__info d-flex flex-column p-15-lr">
                            <template v-if="manager.confirmed">
                                <div class="name">{{manager.first_name}} {{manager.last_name}}</div>
                                <div class="post">Branch Manager</div>
                                <div class="phone">{{manager.phone_number ? manager.phone_number.phone_number : ''}}</div>
                            </template>
                            <template v-else>
                                <div class="info-message">
                                    Branch manager's email is not confirmed.
                                </div>
                                <a href="#" @click.prevent="resendInvitation(manager.email)" class="info-message">Resend invitation to {{ manager.email }}</a>
                            </template>
                        </div>
                    </div>
                </div>
                <div class="col col-6 p-25-30 info-col">
                    <div class="user d-flex" v-if="supervisor">
                        <div class="user__picture" :style="{backgroundImage: 'url(' + (supervisor.avatar ? $http.options.root +'/'+ supervisor.avatar.path : require('images/user_default.jpg')) +')' }"></div>
                        <div class="user__info d-flex flex-column p-15-lr">
                            <template v-if="supervisor.confirmed">
                                <div class="name">{{supervisor.first_name}} {{supervisor.last_name}}</div>
                                <div class="post">Supervisor</div>
                                <div class="user-phone">{{supervisor.phone_number ? supervisor.phone_number.phone_number : ''}}</div>
                            </template>
                            <template v-else>
                                <div class="info-message">
                                    Supervisor's email is not confirmed.
                                </div>
                                <a href="#" @click.prevent="resendInvitation(supervisor.email)" class="info-message">Resend invitation to {{ supervisor.email }}</a>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row block-phones" v-if="policePhone || firePhone || ambulancePhone">
                <div class="col col-1 p-0">
                    <div class="phone-circle d-flex">
                      <svg class="phone-icon" width="20" height="20">
                        <use xlink:href="images/icons-sprite.svg#star-i"></use>
                      </svg>
                    </div>
                </div>
                <div class="col-3">
                    <div class="tel-name">Police</div>
                    <div class="tel-number">{{policePhone}}</div>
                </div>
                <div class="col col-1 p-0">
                    <div class="phone-circle d-flex">
                      <svg class="phone-icon" width="20" height="20">
                        <use xlink:href="images/icons-sprite.svg#fire"></use>
                      </svg>
                    </div>
                </div>
                <div class="col-3">
                    <div class="tel-name">Firefighter</div>
                    <div class="tel-number">{{firePhone}}</div>
                </div>
                <div class="col col-1 p-0">
                    <div class="phone-circle d-flex">
                      <svg class="phone-icon" width="20" height="20">
                        <use xlink:href="images/icons-sprite.svg#plus"></use>
                      </svg>
                    </div>
                </div>
                <div class="col-3">
                    <div class="tel-name">Ambulance</div>
                    <div class="tel-number">{{ambulancePhone}}</div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import 'images/sprites/warning-icon.svg';

import 'images/sprites/phone-icons/star-i.svg'
import 'images/sprites/phone-icons/fire.svg'
import 'images/sprites/phone-icons/plus.svg'
import {mapActions} from 'vuex';
export default {
    props:{
        geo: Object,
        manager: Object,
        supervisor: Object,
        ambulancePhone: String,
        policePhone: String,
        firePhone: String,
        branchId: Number
    },
    methods:{
        ...mapActions({
            deleteBranchRequest : 'deleteBranch'
        }),
        deleteBranch(){
            this.deleteBranchRequest(this.branchId);
            this.$router.push({name: 'dashboard'});
        },
        showModal(){
            this.$root.$emit('bv::show::modal','confirmDelBranch');
        },
        hideModal(){
             this.$root.$emit('bv::hide::modal','confirmDelBranch');
        },
        resendInvitation(email){
            let data={email};
            this.$http({
                    url:"api/v1/users/resend_invite_confirmation",
                    method: "POST",
                    body: data,
                    emulateJSON: true
            }).then(response=>{
                console.log(response.body.message);
            }, response=>{
                console.log(response.data);
            });
        }
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
