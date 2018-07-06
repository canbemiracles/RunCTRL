<template>
    <ul class="employees d-flex">
        <employee-item 
        v-for="employee in employees" 
        :key="employee.id"
        :employee="employee"
        @selected="selected"></employee-item>
    </ul>
</template>
<script>
export default {
    props:{
        employees: Array
    },
    data: ()=>{
        return{
            
        }
    },
    methods:{
        selected(id){
            this.employees.forEach(element => {
                if(element.id==id){
                    this.$emit('selected', element);
                }
            });
        }
    },
    mounted(){
        this.$nextTick(function(){ 
            this.$emit('initEmployees');
        });    
    },
    components:{
        employeeItem: require('./EmployessItem')
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>