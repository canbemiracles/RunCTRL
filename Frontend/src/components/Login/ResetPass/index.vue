<template>
    <div class="form">
         <figure v-if="!this.success_message">
            <form @submit.prevent="sendData">
                <div class="fogot-pass-info">
                    <div class="form-title">Change Password </div>
                    <div class="form-info-txt text-center mb-30">Please enter your new password.</div>
                </div>
                <input :class="{'field-error': validation.hasError('data.body.password')}" type="password" v-model="data.body.password" class="field-form" placeholder="Create a Password">
                <div class="error-message">{{validation.firstError('data.body.password')}}</div>
                <input :class="{'field-error': validation.hasError('data.body.password_repeat')}" type="password" v-model="data.body.password_repeat" class="field-form" placeholder="Repeat a Password">
                <div class="error-message">{{validation.firstError('data.body.password_repeat')}}</div>
                <button type="submit" class="submit-btn">Change Password</button>
            </form>
        </figure>
        <div v-else class="success">
            {{success_message}}
        </div>
    </div>
</template>
<script>
import { mapGetters, mapMutations, mapActions} from 'vuex';
import { Validator } from 'simple-vue-validator';
export default {
    mixins: [require('simple-vue-validator').mixin],
    validators: {
        'data.body.password'(value) {
            return Validator.value(value).required().minLength(6);
        },
        'data.body.password_repeat'(value) {
            let that=this;
            return Validator.custom(function () {
                if (!Validator.isEmpty(value)) {
                    if (value !== that.data.body.password) {
                        return 'Passwords do not match.'
                    }
                }else{
                    return 'Required.'
                }
            });
        },
    },
    computed: {
        ...mapGetters(['getUserData', 'getAccessData'])
    },
    mounted() {
        this.data.body.username = this.getUserData.username;
    },
    data: function(){
        return{
            data: {
                body: {
                    username: "",
                    password: "",
                    password_repeat: ""
                },
            },
            success_message: ""
        }
    },
    methods:{
        ...mapActions(['getRouteTransitionRegist']),
        sendData(){
             this.$validate().then(success => {
                if(!success) return;
                let reset_link = this.$route.query.reset_link;
                let router = this.$router;
                if (reset_link) {
                    this.$http({
                        url: reset_link,
                        method: 'POST',
                        body: {
                            'fos_user_resetting_form[plainPassword][first]': this.data.body.password,
                            'fos_user_resetting_form[plainPassword][second]': this.data.body.password
                            },
                        emulateJSON: true
                        }).then(response => {
                            this.validation.reset();
                            this.success_message=response.body.message;
                            var redirect = this.$auth.redirect();
                            var logData = Object.assign({}, { username: this.data.body.username, password: this.data.body.password}, this.getAccessData);
                            this.$auth.login({
                                params: logData,
                                rememberMe: true,
                                fetchUser: true,
                                redirect:  false,
                                success(data) {
                                    this.getRouteTransitionRegist().then(pathRoute=>{
                                        this.$router.push(pathRoute);
                                    });
                                },
                            });
                        });
                } 
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

