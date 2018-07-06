const roles = {
    state: {
        data: []
    },
    getters: {
        roles: ({ data }) => (data)
    },
    mutations: {
        setRolesData(state, data){
            state.data = data; 
        }
    },
    actions: {
        fetchRoles({state, commit, rootGetters}, branchId) {
            const { $http } = rootGetters;
            $http.get(`api/v1/branches/${branchId}/live_roles_data`).then(response =>{
                commit('setRolesData', response.body);
            }), error =>{
                console.error(error);
            }
        }
    }
}

export default roles;