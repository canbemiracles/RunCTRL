import Vue from 'vue';
const branchStations = {
    state: {
        data: [],
        currentid: 0,
        updateMode: false,
    },
    getters:{
        stationList(state){
            return state.data;
        },
        getStation: (state) => (id)=>{
            let station;
            state.data.forEach(elem=>{
                if(elem.id == id){
                    station=elem;
                }
            });
            return station;
        }
    },
    mutations:{
        incrementIdStation(state){
            state.currentid++;
        },
        setStation(state, data){
            state.data.forEach(elem=>{
                if(elem.id == data.id){
                    Vue.set(elem, 'name', data.name);
                    Vue.set(elem, 'roles', data.roles);
                }
            });
        },
        setTempDataStation(state, data){
            state.data.forEach(elem=>{
                if(elem.id == data.id){
                    Vue.set(elem, 'color', data.roleColor);
                    Vue.set(elem, 'roleName', data.roleName);
                    Vue.set(elem, 'tempRoles', data.tempRoles);
                    Vue.set(elem, 'tempStationName', data.tempStationName);
                }
            });
        },
        removeStation(state, id){
            state.data = state.data.filter(item=>{
                return id != item.id
            });
        },
        updateStationList(state, data){
            state.data = data;
        },
        setEmpty(state, data){
            state.data.push({
                id: ++state.currentid + 'empty'
            });
        },
        initStations(state){
            if(!state.data.length){
                for(var i=0; i<3; i++){
                    state.data.push({
                        id: i + 'empty'
                    })
                }
                state.currentid=i;
            }
        },
        clearStationsData(state){
            state.data = [];
        }
    },
    actions: {
        createQR({state, rootGetters}, data) {
            const {$http} = rootGetters;
            return $http.get(`api/v1/branches/${data.branch_id}/stations/${data.station_id}/qr`).then(res => (res.body));
        },
        getPIN({state, rootGetters}, data) {
            const {$http} = rootGetters;
            return $http.get(`api/v1/branches/${data.branch_id}/stations/${data.station_id}/pins`).then(res => (res.body));
        },
        clearStationsList({state, commit}){
            commit('clearStationsData');
            commit('initStations');
        },
        deleteStationRequest({state, rootGetters}, data){
            const {$http} = rootGetters;
            return $http.delete(`api/v1/branches/${data.branch_id}/stations/${data.station_id}`)
            .catch(err => console.error('Error in deleteStation(): ', err))
        }
    }
}

export default branchStations;