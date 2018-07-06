<template>
    <div class="form login-page">
        <figure>
            <form @submit.prevent="signUp">
                <div class="form-title-sign-up">
                    <div class="form-title-info">Start a 3-months free trial</div>
                    <div class="form-txt">No credit card required.</div>
                </div>
                <input @input="error=null" :class="{'field-error': validation.hasError('data.body.username')}" type="email" required v-model="data.body.username" class="field-form" placeholder="E-mail Address">
                <div class="error-message">{{validation.firstError('data.body.username')}}</div>
                <input :class="{'field-error': validation.hasError('data.body.password')}" type="password" required v-model="data.body.password" class="field-form" placeholder="Create a Password">
                <div class="error-message">{{validation.firstError('data.body.password')}}</div>
                <div class="error-message" v-if="error" v-html="error"></div>
                <button type="submit" :disabled="disableSignUp" class="submit-btn">Sign Up</button>
             </form>
        </figure>
        <div class="form-info">
            <router-link :to="{name: 'forgotPass'}" class="form-link">Forgot Password?</router-link>
        </div>
        <div class="form-info-bottom">
            <span class="form-info-txt">Already have an account?</span>
            <router-link :to="{name: 'login', params: { body: data.body }}" class="form-link">Sign In</router-link>
        </div>
    </div>

</template>
<script>
import { mapGetters, mapMutations, mapActions} from 'vuex';
import { Validator } from 'simple-vue-validator';
export default {
    mixins: [require('simple-vue-validator').mixin],
    validators: {
        'data.body.username'(value) {
            return Validator.value(value).required();
        },
        'data.body.password'(value) {
            return Validator.value(value).required().minLength(6);
        },
    },
    data: function(){
        return{
            data: {
                    body: {
                        username: "",
                        password: '',
                    },
                    rememberMe: false,
            },
            disableSignUp: false,
            error: null
        }
    },
    created() {
      if (this.$route.params.body) {
        this.data.body = this.$route.params.body
      }
    },
    methods: {
        ...mapMutations(['setUserData', 'setStep']),
        ...mapActions(['getRouteTransitionRegist',  'geolocate']),
        signUp(){
            this.disableSignUp = true;
            this.$validate().then(success => {
                if(!success) return;
                var data={
                        'email': this.data.body.username,
                        'plainPassword[first]' : this.data.body.password,
                        'plainPassword[second]': this.data.body.password
                };
                var redirect = this.$auth.redirect();
                this.$auth.register({
                    url:"api/v1/users/admins/new",
                    method: "POST",
                    body: data,
                    emulateJSON: true,
                    rememberMe: this.data.rememberMe,
                    redirect:  {name: 'Welcome'},
                    success(res) {
                        this.geolocate();
                        this.setUserData(data);
                        this.error = null;
                        this.setStep(1);
                    },
                    error(res) {
                        this.disableSignUp = false
                        if(res.status==400){
                            this.error = res.body.message;
                        };
                        this.error = res.body.message;
                    }
                });
                this.validation.reset();
            });

            },
    }
}
</script>
