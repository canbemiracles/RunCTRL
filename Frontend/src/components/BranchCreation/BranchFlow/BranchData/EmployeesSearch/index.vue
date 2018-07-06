<template>
    <div class="col-6 branch-item">
        <div class="header-row d-flex align-items-center justify-content-between">
            <div class="data-title">
                {{title}}
            </div>
            <div class="buttons-group d-flex">
                <search-input v-model="selection" class="btn-item"   
                @typeSearch="setSelection"
                @changeSearch="checkEmail"></search-input>
            </div>
        </div>
        <div class="employees-list-wrap scroll-pane" ref="employeesList" v-if="matches.length">
            <div class="employees-list-inner">
                <employees-list @initEmployees="initScroll" :employees="matches" @selected="selected"></employees-list>
            </div>
        </div>
        <div v-if="!matches.length || error" class="info-message" :class="[{'error-message': error}, {'success-message': success}]">
            <template v-if="!success">{{error ? error :'No employees in this company'}}</template>
            <template v-else>{{success ? success : ''}}</template>
        </div>   
    </div>
</template>
<script>
import {validateEmail} from '../../../../Common/utils.js'
export default {
    props:{
        title: String,
        suggestions: Array,
        branchId: Number
    },
    data: ()=>{
        return {
            selection: '',
            error: null,
            success: null,
            apiScroll: null
        }    
    },
    watch:{
        selection(value){
            if(!value){
                this.error = null;
            }
        },
        matches:{
            handler(val){
                if(val.length){
                    if(this.apiScroll){
                       this.apiScroll.reinitialise(); 
                    }
                }
            },
            deep: true,
        }
    },
    computed: {
        //Filtering the suggestion based on the input
        matches() {
          let that = this;
          return this.suggestions.filter(function(elem) {
            let searchStr = elem.first_name + " " + elem.last_name;
            return (
              searchStr.toLowerCase().indexOf(that.selection.toLowerCase()) >= 0
            );
          });
        },
    },
    mounted(){
    },
    methods:{
        setSelection(selection){
            this.success=null;
            this.error=null;
            this.selection = selection;
        },
        checkEmail(selection){
            //Проверка является ли введенное значение email
            if(validateEmail(selection)){
                let company_id = this.$auth.user().company_id;
                let data={email: selection, company: company_id};
                if(this.title=='Branch Manager'){
                    data.branch = this.branchId;
                    this.$http.post(`api/v1/companies/${company_id}/users/branch_managers/new`, data, {emulateJSON: true}).then((res)=>{
                        console.log(res.body);
                        this.success = 'E-mail with verification link for branch manager was sent.'
                    }).catch(({ body })=>{
                        console.log(body.message);
                        this.error = body.message;
                    });
                }else if(this.title=='Supervisor'){
                    this.$http.post(`api/v1/companies/${company_id}/users/supervisors/new`, data, {emulateJSON: true})
                    .then((res)=>{
                        console.log(res.body);
                        this.success = 'E-mail with verification link for supervisor was sent.'
                    }).catch(({ body })=>{
                        console.log(body.message);
                        this.error = body.message;
                    });
                }
            }
        },
        selected(employee){
            this.$emit('selected', employee.id);
        },
        initScroll(){
             let scrollelem = $(this.$refs.employeesList).jScrollPane({
                autoReinitialise: true
            });
            this.apiScroll = scrollelem.data("jsp");
        }
    },
    components:{
        searchInput: require("../../../../Common/InputBtnSearch"),
        employeesList: require("../EmployessList")
    }
}
</script>
<style lang="scss" scoped>
.info-message{
    padding: 15px;
    color: rgba($lightgray, .5);
}
.error-message{
    color: $cancelRed;
}
.success-message{
    color: $readygreen;
}
</style>

