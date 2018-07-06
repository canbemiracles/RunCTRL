const company = {
    state: {
        data: {},
        gettingData: false,
        timeFormat: null,
        dateFormat: null
    },
    getters: {
        company: ({ data }) => (data),
        companyFunc: state => () => state.data,
        timeFormat: ({ timeFormat }) => (timeFormat),
        dateFormat: ({ dateFormat }) => (dateFormat),
    },
    mutations: {
        setCompanyData(state, data) {
            state.data = data
        },
        setTimeFormat(state, data) {
            state.timeFormat = data
        },
        setDateFormat(state, data) {
            state.dateFormat = data.toUpperCase();
        }
    },
    actions: {
        fetchCompanyData({state, commit, rootGetters}) {
            const { $auth, $http } = rootGetters;
            let company_id = $auth.user().company_id;
            if(company_id){
                state.gettingData = true;
                return $http.get(`api/v1/companies/${company_id}`)
                    .then(({ body }) => {
                        state.gettingData = false;
                        commit('setCompanyData', body);
                        commit('setTimeFormat', body.time_format.time_format);
                        commit('setDateFormat', body.date_format.date_format);      
                    })
                    .catch(err => {
                        state.gettingData = false;  
                        console.log('Error in fetchCompanyData(): ', err)  
                    }) 
            }
        },
        getCompanyData({state, getters, dispatch}){
            return new Promise( (resolve)=>{
                if(!$.isEmptyObject(state.data)){
                    resolve(state.data)
                }else{
                    if(state.gettingData) {
                        this.watch(getters.companyFunc, ()=>{
                            let data = getters.companyFunc();
                            if(!_.isEmpty(data)){
                                resolve(data);
                            }
                        }, {deep: true, immediate: true});
                    }else{
                        dispatch('fetchCompanyData').then(()=>{
                            console.log('fetchCompanyData');
                            resolve(state.data)
                        });  
                    }
                }
            });
        }
    }
}

export default company;