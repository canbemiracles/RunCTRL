import Vue from 'vue';
import Vuex from 'vuex';
import VueResourse from 'vue-resource';


Vue.use(Vuex);
Vue.use(VueResourse);
Vue.use(require('vue-moment'));


Vue.http.options.root = 'http://localhost:8000';
Vue.http.options.credentials = true;

import roles from './modules/roles';
import login from './modules/login';
import registration from './modules/registration';
import branches from './modules/branches';
import shifts from './modules/shifts';
import scroll from './modules/scroll';
import employees from './modules/employees';
import branchStations from './modules/branchStations';
import company from './modules/company';
import googleData from './modules/googleData';
import calendar from './modules/calendar'; 

export const store = new Vuex.Store({
    getters: {
        $http: ()=>(VueResourse),
        $auth: ()=>(Vue.auth),
        $router: ()=>(Vue.router)
    },
    modules: {
       roles,
       login,
       registration,
       branches,
       shifts,
       scroll,
       employees,
       branchStations,
       company,
       googleData,
       calendar
    },
})
