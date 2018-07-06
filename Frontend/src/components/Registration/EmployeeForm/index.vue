<template>
    <div class="d-flex container-fluid p-0 flex-column employee-form">
        <app-header :itemsMenu="this.menu" 
        :search="false" :accoutItems="false"
        >
        </app-header>
        <form-step-two 
        :isRegistrEmployee="true"
        :isStepsHeader="false"
        :employeeData = "employeeData"
        :accessToken="access_token"
        @employeeDataSend="employeeDataSend"
        ></form-step-two>
        <app-footer></app-footer>
         <!-- Modal Component -->
        <b-modal
            id="modalSendInfo"
            centered
            ref="modal" 
            :ok-only="true"
            :no-close-on-backdrop="!status"
            :no-close-on-esc="!status"
            header-class="error-header"
            >
            <div slot="modal-header" class="modal-title d-flex">
               <svg v-if="modalInfo.title == 'Error!'" class="warning-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="42" height="42" viewBox="0 0 42 42"><defs><path id="63g8a" d="M117 781a20 20 0 1 1 40 0 20 20 0 0 1-40 0z"/><path id="63g8b" d="M135 783v-12h4v12zm0 7v-4h4v4z"/></defs><g><g transform="translate(-116 -760)"><use fill="#fff" fill-opacity="0" stroke="#f53f3f" stroke-miterlimit="50" stroke-width="1.5" xlink:href="#63g8a"/></g><g transform="translate(-116 -760)"><use fill="#f53f3f" xlink:href="#63g8b"/></g></g></svg>
               <svg v-else class="warning-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="42" height="42" viewBox="0 0 42 42"><defs><path id="63g8a" d="M117 781a20 20 0 1 1 40 0 20 20 0 0 1-40 0z"/><path id="63g8b" d="M135 783v-12h4v12zm0 7v-4h4v4z"/></defs><g><g transform="translate(-116 -760)"><use fill="#fff" fill-opacity="0" stroke="#7acd63" stroke-miterlimit="50" stroke-width="1.5" xlink:href="#63g8a"/></g><g transform="translate(-116 -760)"><use fill="#7acd63" xlink:href="#63g8b"/></g></g></svg>
                <span class="warn-title" :class="{error: modalInfo.title == 'Error!'}"> {{ modalInfo.title }}</span>
                <button v-if="status" type="button" aria-label="Close" class="close" @click="hideModal">×</button>
            </div>
            <div class="modal-message" :class="{error: modalInfo.title == 'Error!'}">{{ modalInfo.message }}</div>
            <div v-if="!status" class="modal-message error small">Invitation link is not valid</div>
            <div  v-if="!status" slot="modal-footer"></div>
        </b-modal>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions} from 'vuex';
export default {
    data: function(){
        return {
            menu: [
                {name: "Welcome", link: "/welcome"},
                {name: "FAQ", link: "/faq"},
            ],
            status: false,
            access_token: '',
            company_id: null,
            employee_type: null,
            employeeData: {},
            modalInfo: {}
        }
    },
    created() {
        this.fetchConfirm();
        this.geolocate();
    },
    mounted(){
        if(!this.$route.query.confirm_link){
            // this.accessDenied();
        }
    },
    methods:{
        ...mapActions(['geolocate']),
        fetchConfirm() {
            let confirm_link = this.$route.query.confirm_link;
            this.employeeData.company_id = this.$route.query.company;
            this.employeeData.employee_type = this.$route.query.type_user;
            let router = this.$router;
            if (confirm_link) {
                this.$http.get(confirm_link).then(response => {
                    this.status = true;
                    this.access_token = response.body.access_token;
                }, response => {
                    if (response.status === 404) {
                        this.accessDenied();
                    }
                });
            }
        },
        accessDenied(){
            this.status = false;
            this.$set(this.modalInfo, 'message', 'ACCESS DENIED');
            this.$set(this.modalInfo, 'title', 'Error!');
            this.$root.$emit('bv::show::modal','modalSendInfo');
        },
        employeeDataSend(info){
            let success = info.employeeMessage.success && info.userMessage.success;
            if(success){
                this.$router.push({name: 'ThankYou', params: { registrEmployee: true }});
            }else{
                let error = info.employeeMessage.error.status && info.userMessage.error.status;
                this.$set(this.modalInfo, 'title', success ? 'Success' : 'Error!');
                let errorMessage = '';
                if(info.employeeMessage.error.body && info.userMessage.error.body){
                    if(info.userMessage.error.body.error == info.employeeMessage.error.body.error){
                        if(info.employeeMessage.error.body.error == 'access_denied'){
                            errorMessage = 'ACCESS DENIED';
                        }else{
                            errorMessage = info.userMessage.error.body.error || info.userMessage.error.body.message;
                        }
                    }else{
                        errorMessage = info.userMessage.error.body.error || info.userMessage.error.body.message + '\n' +  info.employeeMessage.error.body.error || info.employeeMessage.error.body.message;
                    } 
                }else if(info.employeeMessage.error.body){
                    errorMessage = info.employeeMessage.error.body.error || info.employeeMessage.error.body.message;
                }else if(info.userMessage.error.body){
                    errorMessage = info.userMessage.error.body.error || info.userMessage.error.body.message;
                }
                this.$set(this.modalInfo, 'message', errorMessage);
                this.$root.$emit('bv::show::modal','modalSendInfo');
            }   
            
            
        },
        hideModal(){
             this.$root.$emit('bv::hide::modal','modalSendInfo');
        },
    },
    components:{
        appHeader: require('../../Header'),
        appFooter: require('../../Footer'),
        formStepTwo: require('../Step2')
    }
}
</script>
<style lang="scss" src="../style.scss" scoped></style>
<style lang="scss" src="../Step2/drop-down.scss"></style>
<style lang="scss" scoped>
.employee-form {
    
   /deep/  .modal-content{
    box-shadow: 2px 6px 10px rgba(28, 60, 77, 0.1);
    border: none;
    color: $lightgray;
    text-align: center;
    }
    /deep/ .modal-backdrop{
        background-color: #d9dedead;
    }
    /deep/ .modal-title{
        color: $cancelRed;
        width: 100%;
        display: flex;
        align-items: center;  
    }
    /deep/ .warning-icon{
        width: 42px;
        height: 42px;
        margin-right: 15px; 
    }
    /deep/ .warn-title{
        font-family: 'Roboto-Medium', sans-serif;
        &.success{
            color: $readygreen;
        }
    }
    /deep/ .modal-footer{
        .btn.btn-primary{
            background-color: $blue;
            border: none;
            border-radius: 100px;
            padding: 0.3em 1.8em;
            transition: all ease 300ms;
            &:hover, &:focus, &:active{
                box-shadow: none;
                outline: none;
                border: none;
                background-color: lighten($blue, 20);
            }
        }
    }
    .modal-message.error{
        color: $cancelRed;
        font-size: 18px;
        &.small{
            font-size: 14px;
            font-family: 'Roboto-Light', sans-serif;
        }
    }   
}
</style>
