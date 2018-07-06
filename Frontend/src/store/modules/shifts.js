import Vue from 'vue';
const shifts = {
    state: {
        data: [],
        shift_group: [],
        day_start: null,
        updateMode: false,
        forDeleteArr: []
    },
    getters:{
        shifts(state){
            return state.data;
        },
        shift_groups(state){
            return state.shift_group;
        },
        forDeleteArr(state){
            return state.forDeleteArr;
        }
    },
    mutations:{
        addforDeleteArr(state, id){
            state.forDeleteArr.push(id);
        },
        removefromDeleteArr(state, id){
            state.forDeleteArr = state.forDeleteArr.filter(item=>{
                return id != item;
            })
        },
        setShiftsUpdateMode(state, val){
            state.updateMode = val;
        },
        addNewShiftTimeRow(state, shift_id){
            state.shift_group.forEach(item=>{
                if(item.shift_id == shift_id){
                    if(item.listBusyDays.length!=7){
                        item.time_rows.push({
                            shift_id: shift_id,
                            week_day_start: state.day_start,
                            timeRow_id: item.timeRowCount,
                            time_open: "08:00:00",
                            time_close: "17:00:00" 
                        });
                    }else{
                        return false;
                    }
                    item.timeRowCount++;
                }
            });
        },
        addNewShift(state, data){
            state.shift_group.forEach(item=>{
                if(item.shift_id==data.shift_id){
                    item.listBusyDays.push({
                       day:  data.shift_day,
                       shiftId: data.shift_id,
                       rowId: data.timeRow_id
                    });
                }
            });
            state.data.push(data);
        },
        setShiftTimeRow(state, data){
            state.shift_group.forEach(item=>{
                if(item.shift_id==data.shift_id){
                    item.time_rows.forEach(elem =>{
                        if(elem.timeRow_id == data.timeRow_id){
                            elem.time_close = data.time_close;
                            elem.time_open = data.time_open;
                        }
                    });
                }
            });
        },
        removeShift(state, data){
            state.data = state.data.filter(shift=>{
                return !(shift.shift_id == data.shift_id && shift.shift_day == data.shift_day)
            });
            state.shift_group.forEach(item=>{
                if(item.shift_id==data.shift_id){
                    item.listBusyDays = item.listBusyDays.filter(day=> day.day != data.shift_day);
                }
            });
        },
        addNewShiftGroup(state, data){
            state.shift_group.push(data);
        },
        updateShiftGroup(state, data){
            state.shift_group.forEach(item=>{
                if(item.shift_id == data.id){
                    Vue.set(item, 'name', data.value);
                }
            });
        },
        replaceShifts(state, data){
            state.data = data;
        },
        replaceShiftGroup(state, data){
            state.shift_group = data;
        },
        clearShiftData(state){
            state.data = [];
            state.shift_group = [];
        },
        removeShiftGroup(state, id){
            state.shift_group = state.shift_group.filter(shift=>shift.shift_id != id);
            if(state.data.length!=0){
                let data = _.partition(state.data, shift => shift.shift_id != id);
                state.data = data[0];
                data[1].forEach(el=>{
                    if(el.id){
                       state.forDeleteArr.push(el.id); 
                    }
                });    
            }
        },
        setDayStart(state, start){
            state.day_start = start;
        },
        setBranchId(state, branch_id){
            state.branch_id = branch_id;
        },
        setShiftDayId(state, shiftdata){
            state.data[shiftdata.index].id = shiftdata.id;
        },
        setErrorStatusShift(state, shiftdata){
            state.data.forEach(item=>{
                if(item.shift_id==shiftdata.shift_id && item.shift_day==shiftdata.shift_day){
                    item.error = shiftdata.error;
                }
            })
        } 
    },
    actions: {
        fetchShifts({state, commit, dispatch, rootGetters}, shifts) {
            const { $http } = rootGetters;
            dispatch('fetchWeekStart').then(res=>{
                if(shifts){
                    let shift_groups=[];
                    //Разделяем на shifts
                    let shifts_copy = shifts.map(a=>({...a}));
                    let index=0;
                    let shifts_row=[];
                    let time_rows=[];
                    while(shifts_copy.length){
                        let element = shifts_copy[0];
                        let matches = [];
                        let unmatches=[];
                        shifts_copy.forEach(shift=>{
                            if(shift.name == element.name){
                                matches.push(shift);
                            }else{
                                unmatches.push(shift); 
                            }
                        });
                        shifts_copy = unmatches;
                        shifts_row.push(matches);
                    }
                    let shiftsData = [];
                    shifts_row.forEach((row) =>{
                        shift_groups.push({
                            shift_id: index,
                            listBusyDays: [],
                            time_rows: [],
                            timeRowCount: 0
                        });
                        let indexRow=0;
                        row.forEach((element, ind)=>{
                            let time_close = moment.utc(element.time_close_custom).format('HH:mm:ss');
                            let time_open =  moment.utc(element.time_open_custom).format('HH:mm:ss');
                            element.time_open = time_open;
                            element.time_close = time_close;   
                        });
                        let row_copy = row.slice();
                        while(row_copy.length){
                            let element = row_copy[0];
                            row_copy=row_copy.filter(shift=> {
                                if(!(shift.time_open == element.time_open && shift.time_close == element.time_close)){
                                    return true;
                                }else{
                                    shift.timeRow_id = indexRow;
                                    shift.shift_id = index;
                                    shift.shift_day = shift.shift_day.day;
                                    shift.week_day_start = state.day_start;
                                    return false;
                                }
                            });
                            shift_groups[index].time_rows.push(element);
                            shift_groups[index].timeRowCount++;
                            shift_groups[index].name = element.name;
                            indexRow++;
                        }
                        
                        shiftsData = [...shiftsData, ...row];
                        shift_groups[index].time_rows.forEach((tr, ind)=>{
                            row.forEach(el=>{
                                if(el.time_open==tr.time_open && el.time_close==tr.time_close){
                                    shift_groups[index].listBusyDays.push({
                                        day: el.shift_day,
                                        rowId: ind,
                                        shiftId: index,
                                        savedId: el.id,
                                    })
                                }
                            });   
                        });
                        index++;
                        
                    });
                    commit('replaceShifts', shiftsData);
                    commit('replaceShiftGroup', shift_groups); 
                }else{
                    if(state.shift_group.length==0){
                        dispatch('initEmptyShiftGroup');
                    }
                }
            });
        },
        fetchWeekStart({state, commit, dispatch, rootGetters, rootState}){
            return new Promise((resolve)=>{
                if(state.day_start){
                    resolve();
                }else{
                    const { $http, $auth } = rootGetters;
                    dispatch({
                        type: 'getCompanyData', 
                        options: {root: true}
                    }).then((res)=>{
                        commit('setDayStart', res.week_start_on);
                        resolve();
                    });
                }
            });
            
        },
        initEmptyShiftGroup({state, commit, dispatch}){
            commit('clearShiftData');
            commit('addNewShiftGroup', {
                shift_id: 0,
                listBusyDays: [],
                time_rows: [],
                timeRowCount: 0,
            });
            if(state.day_start){
                commit('addNewShiftTimeRow', 0);
            }else{
                dispatch('fetchWeekStart').then(res=>{
                    commit('addNewShiftTimeRow', 0);
                });
            }
        },
        getBranchShifts({state, commit, rootGetters}, branch_id){
            const { $http } = rootGetters;
            return new Promise((resolve)=>{
                $http.get(`api/v1/branches/${branch_id}/shifts/`).then((res)=>{
                    commit('replaceShifts', res.body);
                    resolve();
                });
            });
        }
    }
}

export default shifts;