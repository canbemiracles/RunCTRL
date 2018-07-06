const registration = {
    state: {
        step: -1,
        countries: [],
        indCategory: [],
        time_zones: [],
        date_formats: [],
        time_formats: [],
        currencies: [],
    },
    getters: {
        countries: ({ countries }) => (countries),
        industryCategory: ({ indCategory }) => (indCategory),
        time_zones: ({ time_zones }) => (time_zones),
        date_formats: ({ date_formats }) => (date_formats),
        time_formats: ({ time_formats }) => (time_formats),
        currencies: ({ currencies }) => (currencies),
        registr_step: ({ step }) => (step),
    },
    mutations: {
        setStep(state, data) {
            state.step = data
        },
        setCountries(state, data) {
            state.countries = data
        },
        setIndustryCat(state, data) {
            state.indCategory = data
        },
        setTimeZones(state, data) {
            state.time_zones = data
        },
        setDateFormats(state, data) {
            state.date_formats = data
        },
        setTimeFormats(state, data) {
            state.time_formats = data
        },
        setCurrencies(state, data) {
            state.currencies = data
        },
    },
    actions: {
        checkRegistrStep({ commit, state, rootGetters }) {
            console.log('checkRegister');
            const { $http, $auth } = rootGetters;
            return new Promise(function (resolve) {
                let company_id = $auth.user().company_id;
                if (company_id) {
                    $http.get(`api/v1/companies/${company_id}`).then(response => {
                        if (!response.body.time_zone) {
                            commit('setStep', 3);
                            resolve(3)
                        }else if(!response.body.plan) {
                            commit('setStep', null);
                            resolve(null); //step4
                        }else{
                            commit('setStep', null);
                            resolve(null)
                        }
                    });
                }
                else {
                    commit('setStep', 2);
                    resolve(2)
                }
            });

        },
        step2({ commit, state, rootGetters }) {
            const { $http } = rootGetters;
            let promiseCountry = $http.get('api/v1/countries/').then(response => {
                commit('setCountries', response.body);
            });
            let promiseIndCategories = $http.get('api/v1/industry_categories/').then(response => {
                commit('setIndustryCat', response.body);
            }); 
            return Promise.all([promiseCountry, promiseIndCategories]);
        },
        step3({ commit, state, rootGetters }) {
            const { $http } = rootGetters;
            let promiseTimeZone = $http.get('api/v1/time_zones').then(response => {
                commit('setTimeZones', response.body);
            });
            let promiseDateFormats = $http.get('api/v1/date_formats').then(response => {
                commit('setDateFormats', response.body);
            });
            let promiseTimeFormats = $http.get('api/v1/time_formats/').then(response => {
                commit('setTimeFormats', response.body);
            });
            let promiseCurrencies = $http.get('api/v1/currencies').then(response => {
                commit('setCurrencies', response.body);
            });
            return Promise.all([promiseTimeZone, promiseDateFormats, promiseTimeFormats, promiseCurrencies]);
        },
        fetchCountriesWithAccess({ commit, state, rootGetters }, access){
            const { $http } = rootGetters;
            return $http.get(`api/v1/countries/?token=${access}`).then(response => {
                commit('setCountries', response.body);
            });
        },
        getRouteFromStep({state}){
            if(state.step==1){
                return {name: 'Welcome'}
            }else if(state.step==2){
                return {name: 'Registration', params: {step: 'step-2'}}
            }else if(state.step==3){
                return {name: 'Registration', params: {step: 'step-3'}}
            }else if(!state.step){
                return {name: 'dashboard'}
            }else{
                return {name: 'login'}
            }
        },
        getRouteTransitionRegist({ dispatch, state, rootGetters }){
            return dispatch('checkRegistrStep').then(stepData=>{
                return dispatch('getRouteFromStep', stepData);
            });
        }
    }
}

export default registration;