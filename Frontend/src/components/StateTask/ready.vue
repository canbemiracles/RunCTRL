<template>
    <div class="done" :class="{disabled: disabled}">
        <svg width="9" height="9" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
            <path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z" fill="#fff" />
        </svg>
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
.done {
    width: 16px;
    height: 16px;
    background-color: $readygreen;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    margin-right: 6px;
    flex-shrink: 0;
}
.disabled{
  background-color: #c2ced3; 
}
</style>