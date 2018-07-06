const rootUrl = 'api/v1/branches/'

const branchCreation = {
    state: {
        data: [],
        branchesLoad: false,
        errorMessage: ''
    },

    getters: {
        branches: ({ data }) => data,
        loadStatus: ({ branchesLoad }) => branchesLoad,
        errorMessage: ({ errorMessage }) => errorMessage,
    },

    mutations: {
        setBranches(state, data) {
            state.data = data
        },
        setLoadStatus(state, data){
            state.branchesLoad = data;
        },
        setErrorMessage(state, message){
            state.errorMessage = message;
        }
    },

    actions: {
        fetchBranches({ rootGetters, commit }) {
        return rootGetters.$http.get(rootUrl)
            .then(({ body }) => commit('setBranches', body))
            .catch(err => console.log('Error in fetchBranches(): ', err))
        },
        fetchBranchById({rootGetters}, id){
            const {$http} = rootGetters;
            return $http.get(rootUrl + id).then(res => (res.body));
        },
        addBranch({ rootGetters, commit, state }, data) {
        rootGetters.$http.post(rootUrl + 'new', data, {emulateJSON: true})
            .then(({ body }) => commit('setBranches', [...state.data, body]))
            .catch(err => console.log('Error in addBranch(): ', err))
        },

        deleteBranch({ rootGetters, commit, state }, id) {
        rootGetters.$http.delete(rootUrl + id)
            .then(() => commit('setBranches', state.data.filter(b => b.id !== id)))
            .catch(err => console.log('Error in deleteBranch(): ', err))
        },

        updateBranch({ rootGetters, commit, state }, { id, data }) {
        rootGetters.$http.patch(rootUrl + id, data)
            .then(({ body }) => commit('setBranches', state.data.map(b => b.id === id ? body : b)))
            .catch(err => console.log('Error in updateBranch(): ', err))
        },    
        createBranch({state, dispatch,rootGetters}, data) {
            console.log('create branch');
            const {$http, $auth} = rootGetters;
            dispatch('patchAddressRegionPhone', data);
            dispatch('patchBranchManager', data);
            dispatch('patchBranchSupervisor', data);
        },
        getSupervisors({rootGetters}){
            const {$http, $auth} = rootGetters;
            return $http.get(`api/v1/companies/${$auth.user().company_id}/users/supervisors/`).then(res => (res.body));
        },
        getManagers({rootGetters}){
            const {$http, $auth} = rootGetters;
            return $http.get(`api/v1/companies/${$auth.user().company_id}/users/branch_managers/`).then(res => (res.body));
        },
        patchAddressRegionPhone({rootGetters}, data) {
            const {$http} = rootGetters;
            return $http.patch('api/v1/branches/' + data.branchId, {
                geographical_area: {
                    street_address: data.address,
                    region: data.region
                },
                police_phone: data.phones.police.phone,
                fire_phone: data.phones.fire.phone,
                ambulance_phone:  data.phones.ambulance.phone
            }
        )
        },
        patchBranchManager({rootGetters}, data) {
            if(data.manager && data.manager.id){
                const {$http, $auth} = rootGetters;
                return $http.patch(`api/v1/companies/${$auth.user().company_id}/users/branch_managers/${data.manager.id}`, {branch: data.branchId})
            }
        },
        patchBranchSupervisor({rootGetters}, data) {
            if(data.supervisor && data.supervisor.id){
               const {$http} = rootGetters;
                return $http.patch(rootUrl + data.branchId, {supervisor: data.supervisor.id}); 
            }
        },
    }
}


export default branchCreation;
