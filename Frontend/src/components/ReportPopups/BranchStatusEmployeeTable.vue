<template>
    <table class="m-table">
        <thead>
            <th colspan="2">EMPLOYEE</th>
            <th>STATION</th>
            <th>POSITION</th>
            <th>SHIFT TIME</th>
            <th>TOTAL</th>
            <th>SALARY</th>
            <th>&nbsp;</th>
        </thead>
        <tbody>
            <tr v-for="employee in employees" :key="employee.id">
                <td>
                    <div class="employee-picture" :style="{backgroundImage: 'url(' + (employee.employee.avatar ? $http.options.root +'/'+ employee.employee.avatar.path : require('images/user_default.jpg')) +')'}"></div>
                </td>
                <td>
                    <div class="employee-name">
                        {{ employee.employee.first_name }} {{ employee.employee.last_name }}
                    </div>
                </td>
                <td>{{ employee.role.branch_station }}</td>
                <td v-color="employee.role.color">{{ employee.role.role }}</td>
                <td>
                    <div class="time-row d-flex info-content">
                        <div class="time time-start" :class="{ 'time-late' : isLate(employee)}">
                            {{ $moment(employee.date_start).utc().format(twelvehour ? 'h:mm a' : 'HH:mm') }}
                        </div>
                        <span>&nbsp;-&nbsp;</span>
                        <div class="time time-end" :class="{'working': !employee.date_end}">
                           {{ employee.date_end && employee.date_start ? $moment(employee.date_end).utc().format(twelvehour ? 'h:mm a' : 'HH:mm') : 'WORK' }}
                        </div>
                    </div>
                </td>
                <td>{{ employee.total_hours ? formatTotalTime(employee) : 0 }}</td>
                <td>{{ employee.total_cost | currency }} <span class="hourly-rate"> / {{ employee.employee.hourly_rate | currency }}/h</span></td>
                <td>
                  <div class="indicators">
                    <indicator :value="1" color="readyGreen"/>
                    <indicator :value="2" color="pendingYellow" />
                    <indicator :value="3" color="cancelRed" />
                  </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    props:{
        employees: Array,
        twelvehour: Boolean
    },
    data(){
        return {

        }
    },
    methods:{
        isLate(employee){
            let dateStart = moment(employee.date_start);
            let startShift = moment(employee.branch_shift.time_open);
            if(dateStart.isAfter(startShift)){
                return true;
            }
            return false;
        },
        formatTotalTime(employee){
            if(employee.date_end){
                let start = moment(employee.date_start).toObject();
                let end= moment(employee.date_end).toObject();
                let diff = moment({ hours: end.hours, minutes: end.minutes}).diff(moment({ hours: start.hours, minutes: start.minutes}));
                let hours = moment.duration(diff).hours();
                let minutes = moment.duration(diff).minutes();
                return `${hours}:${minutes}`;
            }else{
                let hoursInt = +employee.total_hours;
                let hours = ~~hoursInt;
                let minutes = Math.round((hoursInt - hours) * 60);
                return `${hours}:${minutes}`;
            }
        },
    },
    components: {
      indicator: require('../Common/Indicator')
    }
}
</script>
<style lang='scss' src="../SettingsPage/table.scss" scoped></style>
<style lang='scss' scoped>
.m-table {
    margin-top: 30px;
        tr {
            min-height: 60px;
            td {
                white-space: nowrap;
                text-overflow: ellipsis;
                color: #505d63;
                font-size: 15px;
                font-weight: 400;
                line-height: 19px;
                padding-left: 0.4em;
                padding-right: 0.4em;
            }
        }
        th{
            padding-left: 0.4em;
            padding-right: 0.4em; 
        }
    .employee-name {
        color: #505d63;
        font-size: 15px;
        font-weight: 600;
        line-height: 19px;
    }
    .time {
        height: 22px;
        width: 69px;
        line-height: 22px;
        background-color: #d3f7c4;
        text-align: center;
        color: #2e3a40;
        font-size: 14px;
        font-weight: 400;
    }

    .time-late {
        background-color: #fccfcf;
    }
    .time-work {
        background-color: #dcfaff;
        color: #627680;
        text-transform: uppercase;
    }
    .time-end{
        &.working{
            background-color: #dcfaff;
        }
    }
}
.employee-picture{
    height: 35px;
    width: 35px;
    border-radius: 50%;
    display: inline-flex;
    background-position: center center;
    background-size: cover; 

}
.indicators{
    /deep/ .indicator{
        margin: 0;
        margin-right: 5px;
        &:last-child{
            margin: 0;
        }
    }
}
.hourly-rate{
   color: #7e8a90;
   font-size: 15px; 
}
</style>
