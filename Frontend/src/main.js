import 'images/favicon.png';
import Vue from 'vue';
import VueRouter from 'vue-router';
import App from './App.vue';
import 'jquery';
import "jscrollpane/script/jquery.mousewheel.js";
import "jscrollpane/script/jquery.jscrollpane.min.js";
import 'bootstrap/dist/js/bootstrap.min.js';
import './customModules/jquery-clockpicker-custom.js';
import router from './router';
import {store} from './store';
import 'babel-polyfill'
let _ = require('lodash');
import BootstrapVue from 'bootstrap-vue'
import {getCurrencyValue, guardRoute} from './components/Common/utils.js'
import "moment-duration-format";
import 'bootstrap-datepicker'; 
import Moment from 'moment';
import { extendMoment } from 'moment-range';
const moment = extendMoment(Moment);

Vue.config.productionTip = false;
Vue.config.performance = true;

var path = require('path');
Vue.use(BootstrapVue);

export const $eventBus = new Vue();

Vue.router = router;

//DIRECTIVES

Vue.directive('color', {
    bind(el, binding, vnode){
        if(binding.arg == 'bg'){
            el.style.backgroundColor = '#' + binding.value;
        }else{
            el.style.color = '#' + binding.value;
        }
    },
    update(el, binding, vnode){
        if(binding.arg == 'bg'){
            el.style.backgroundColor = '#' + binding.value;
        }else{
            el.style.color = '#' + binding.value;
        }
    }
});

//FILTERS

Vue.filter('currency', function (value, minDig=2, maxDig=2) {
    return getCurrencyValue(value, minDig, maxDig); 
});


Vue.http.interceptors.push(function(request, next) {
    next(function (res) {
        if (res.status === 401 ) {
            if(res.url == Vue.http.options.root + '/api/v1/users/current'){
                Vue.router.push({name: 'Welcome'});    
            }
        }

        if(res.status === 400 &&
            ['Invalid refresh token', 'No \"refresh_token\" parameter found'].indexOf(res.data.error_description) > -1){
                Vue.auth.logout({
                    redirect: {name: 'login'},
                });
        }
    });
});

// Vue Auth
function getRefreshToken(){
    let token=localStorage.getItem('default_auth_token');
    if(token){
        token = token.split(';');
        token = token[1];
    }else{
        token= "";
    } 
    return token;
}

Vue.use(require('@websanova/vue-auth'), {
    auth: require('./customModules/customAuth.js'),
    http: require('@websanova/vue-auth/drivers/http/vue-resource.1.x.js'),
    router: require('@websanova/vue-auth/drivers/router/vue-router.2.x.js'),
    rolesVar: 'role',
    tokenHeader: 'Authorization',
    authRedirect: {path: '/'},
    refreshData: {
        url: "oauth/v2/token", 
        enabled: true,
        before: function(req){
           req.params = {
                grant_type: "refresh_token",
                client_id: '1_1fwfg4mreq680s0404s8g8ggkgkkgoc08skow044o08cwckc4o',
                client_secret: '3kz917qhoo6ccw8ogg8og0k8k4kw80skokg8scsco88k8wk4wk',
                refresh_token: getRefreshToken()
            }
        }, 
        interval: 60, redirect: false},
    loginData:{url: "oauth/v2/token", method: "GET"},
    fetchData: {url: 'api/v1/users/current', method: 'GET', enabled: true},
    parseUserData: (data)=>(data),
    notFoundRedirect: false
});

router.beforeEach((to, from, next) => {
    // console.log(from);
    // console.log(to);
    if(store.getters.registr_step == -1){
        store.dispatch('checkRegistrStep').then(step=>{
            guardRoute(step, to, from, next)
        });
    }else{
        guardRoute(store.getters.registr_step, to, from, next); 
    }
        
})

new Vue({
  el: '#app',
  router,
  store,
  render: h => h(App),
});