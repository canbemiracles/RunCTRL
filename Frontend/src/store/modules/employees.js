export default {

  state: {
    data: null,
    liveEmployeesData: null
  },

  getters: {
    employees: ({ data }) => data,
    employeesLiveData: ({ liveEmployeesData }) => liveEmployeesData,
  },

  mutations: {
    setEmployees(state, data) {
      state.data = data
    },
    setEmployeesLiveData(state, data) {
      state.liveEmployeesData = data
    }
  },

  actions: {
    async request({ rootGetters }, { url = '', method, data }) {
      const { company_id } = rootGetters.$auth.user()
      const params = [`api/v1/companies/${company_id}${url}`]
      data && params.push(data)
      return await rootGetters.$http[method](...params)
    },

    fetchEmployees({ commit, dispatch }) {
      dispatch('request', { url: '/employees', method: 'get' })
        .then(({ body }) => commit('setEmployees', body))
        .catch(err => console.log('Error in fetchEmployees(): ', err))
    },

    addEmployee({ commit, dispatch, state }, data) {
      dispatch('request', { url: '/employees/new', method: 'post', data })
        .then(({ body }) => commit('setEmployees', [...state.data, body]))
        .catch(err => console.log('Error in addEmployee(): ', err))
    },

    deleteEmployee({ commit, dispatch, state }, id) {
      dispatch('request', { url: '/employees/' + id, method: 'delete' })
        .then(() => commit('setEmployees', state.data.filter(b => b.id !== id)))
        .catch(err => console.log('Error in deleteEmployee(): ', err))
    },

    updateEmployee({ commit, dispatch, state }, { id, data }) {
      dispatch('request', { url: '/employees/' + id, method: 'patch', data })
        .then(({ body }) => commit('setBranches', state.data.map(e => e.id === id ? body : e)))
        .catch(err => console.log('Error in updateEmployee(): ', err))
    },
    attachEmployeeToBranch({state, rootGetters}, { branch_id, employee_id }){
      return rootGetters.$http.post(`api/v1/branches/${branch_id}/attach_employee`, {employee_id}, {emulateJSON: true})
        .catch(err => console.log('Error in attachEmployeeToBranch(): ', err))
    },
    detachEmployeeBranch({state, rootGetters}, { branch_id, employee_id }){
      return rootGetters.$http.delete(`api/v1/branches/${branch_id}/detach_employee?employee_id=${employee_id}`)
        .catch(err => console.log('Error in detachEmployeeToBranch(): ', err))
    },
    getLiveDataEmployeesShift({state, commit, rootGetters}, branch_id){
      return rootGetters.$http.get(`api/v1/branches/${branch_id}/get_data_live_employees_shift`)
        .then(({ body }) => commit('setEmployeesLiveData', body))
        .catch(err => console.log('Error in getLiveDataEmployeesShift(): ', err))
    },
    getCompanyEmployeesCurrentShift({state, commit, rootGetters}){
      let company_id = rootGetters.$auth.user().company_id;
      return rootGetters.$http.get(`api/v1/companies/${company_id}/employees_by_current_shift`)
    },
    getFamilyStatusList({state, commit, rootGetters}, access){
      let url;
      if(access){
        url = `api/v1/family_statuses/?token=${access}`;
      }else{
        url = `api/v1/family_statuses/`;
      }
      return rootGetters.$http.get(url);
    }
  }
}
