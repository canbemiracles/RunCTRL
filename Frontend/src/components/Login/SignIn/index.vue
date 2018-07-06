<template>
    <div class="form">
        <figure>
            <form @submit.prevent="login">
                <div class="form-title mb-30">Sign In</div>
                <input @input="error=null" :class="{'field-error': validation.hasError('data.body.username')}" type="email" required v-model="data.body.username" class="field-form" placeholder="E-mail Address">
                <div class="error-message">{{validation.firstError('data.body.username')}}</div>
                <input :class="{'field-error': validation.hasError('data.body.password')}" type="password" required v-model="data.body.password" class="field-form" placeholder="Password">
                <div class="error-message">{{validation.firstError('data.body.password')}}</div>
                <div class="error-message" v-if="error" v-html="error"></div>
                <button type="submit" class="submit-btn">Sign In</button>
            </form>
        </figure>
        <div class="form-info">
            <router-link :to="{name: 'forgotPass'}" class="form-link">Forgot Password?</router-link>
        </div>
        <div class="form-info-bottom">
            <span class="form-info-txt">Don’t have an account?</span>
            <router-link :to="{name: 'signUp', params: { body: data.body }} " class="form-link">Sign Up</router-link>
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
            return Validator.value(value).required();
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
            error: null
        }
    },
    computed:{
            ...mapGetters(['getAccessData'])
    },
    created() {
      if (this.$route.params.body) {
        this.data.body = this.$route.params.body
      }
    },
    methods:{
        ...mapMutations(['setLoginData']),
        ...mapActions(['getRouteTransitionRegist', 'geolocate']),
        login() {
            this.$validate().then(success => {
                if(!success) return;
            var redirect = this.$auth.redirect();
            var logData = Object.assign({}, this.data.body, this.getAccessData);
            this.setLoginData(logData);
            this.$auth.login({
                params: logData,
                rememberMe: this.data.rememberMe,
                fetchUser: true,
                redirect:  false,
                success(data) {
                    this.geolocate();
                    this.getRouteTransitionRegist().then(pathRoute=>{
                        this.$router.push(pathRoute);
                        this.$store.dispatch('fetchCompanyData');
                        this.$store.dispatch('fetchBranches');
                    });
                },
                error(res) {
                    this.error = "Invalid combination of email and password <br> Please try again";
                }
            });
            this.validation.reset();
            });
        },
    }
}
</script>
