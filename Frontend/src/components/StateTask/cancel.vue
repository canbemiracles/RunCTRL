<template>
    <div class="cancel" :class="{disabled: disabled}">
        <svg class="cancel-icon"  width="10" height="10" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1490 1322q0 40-28 68l-136 136q-28 28-68 28t-68-28l-294-294-294 294q-28 28-68 28t-68-28l-136-136q-28-28-28-68t28-68l294-294-294-294q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 294 294-294q28-28 68-28t68 28l136 136q28 28 28 68t-28 68l-294 294 294 294q28 28 28 68z"/></svg>
    </div>
</template>
<script>
export default {
    props:{
        disabled: Boolean
    }
}
</script>
<style lang='scss' scoped>
.cancel {
    width: 16px;
    height: 16px;
    background-color: $cancelRed;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 6px;
    flex-shrink: 0;
}
.cancel-icon{
    fill: #fff;
}
.disabled{
  background-color: #c2ced3; 
}
</style>