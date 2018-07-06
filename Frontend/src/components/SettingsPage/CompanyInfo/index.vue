<template>
     <div class="d-flex container-fluid p-0 flex-column">
        <form-step-two
        class="align-left" 
        :isStepsHeader="false"
        @initLoad="showPreloader = false"
        ></form-step-two>
        <preloader :show="showPreloader"></preloader>
    </div>
</template>
<script>
export default {
    props:{
         userData: Object
    },
    data: function(){
        return {
            showPreloader: true
        }
    },
    methods:{
        
    },
    components:{
        formStepTwo: require('../../Registration/Step2'),
        preloader: require('../../Common/Preloader')
    },
}
</script>
<style lang="scss" scoped>
.align-left.padd_top_steps{
    padding-top: 0;
    /deep/ .reg_form{
       margin-left: 0; 
    }
    /deep/ .step2_title{
        text-align: left;
        align-self: flex-start;
        margin-bottom: 0;
        margin-top: 0;
    }
}
</style>

