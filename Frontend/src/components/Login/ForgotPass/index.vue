<template>
    <div class="form">
         <figure v-if="!this.success_message">
            <form @submit.prevent="sendData">
                <div class="fogot-pass-info">
                    <div class="form-title">Password Reset</div>
                    <div class="form-info-txt text-center mb-30">To reset your password, please enter your registered email address.</div>
                </div>
                <input  type="email" :class="{'field-error': validation.hasError('data.body.username')}" v-model="data.body.username" class="field-form" placeholder="yourname@example.com">
                <div class="error-message">{{validation.firstError('data.body.username')}}</div>
                <button type="submit" class="submit-btn">Get Reset Link</button>
            </form>
        </figure>
        <div v-else class="success">
            {{success_message}}
        </div>
        <a class="historyBack" href="#" @click.prevent="$router.back()">
            <svg width="20" height="20" class="arrow-back" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                <path d="M1664 896v128q0 53-32.5 90.5t-84.5 37.5h-704l293 294q38 36 38 90t-38 90l-75 76q-37 37-90 37-52 0-91-37l-651-652q-37-37-37-90 0-52 37-91l651-650q38-38 91-38 52 0 90 38l75 74q38 38 38 91t-38 91l-293 293h704q52 0 84.5 37.5t32.5 90.5z" />
            </svg>
        </a>
    </div>
</template>
<script>
import { Validator } from 'simple-vue-validator';
import { mapMutations} from 'vuex';
export default {
    mixins: [require('simple-vue-validator').mixin],
    validators: {
        'data.body.username'(value) {
            return Validator.value(value).required();
        },
    },
    data: function(){
        return{
            data: {
                    body: {
                        username: "",
                    },
            },
            success_message: null
        }
    },
    methods:{
        ...mapMutations(['setUserData']),
        sendData(){
             this.$validate().then(success => {
                if(!success) return;
                this.validation.reset();
                this.$http({
                    url: 'resetting/send-email',
                    method: 'POST',
                    body: {username: this.data.body.username},
                    emulateJSON: true
                }).then(response=>{
                    this.setUserData({username: this.data.body.username});
                    this.success_message = "Password reset link sent. Please clicking on the link in the e-mail we sent you.";
                }); 
             });
        }
    }
};
</script>
<style scoped>
.submit-btn{
    margin-top: 5px;
}
</style>

