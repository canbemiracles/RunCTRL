<template>
    <div class="d-flex container-fluid p-0 flex-column">
        <form-step-three
        class="align-left" 
        :isStepsHeader="false"
        ></form-step-three>
    </div>
</template>
<script>
export default {
    components: {
        formStepThree: require('../../Registration/Step3')
    }
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
