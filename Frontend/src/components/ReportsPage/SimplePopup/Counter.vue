<template lang="html">
  <div class="wrapper">
    <div class="icon">
      <slot />
    </div>
    <div class="">
      <p>{{title}}</p>
      <h6 v-if="value">{{value | currency(maxDig=0)}}</h6>
      <h6 v-else>0</h6>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    title: String,
    value: String/Number
  }
}
</script>

<style lang="scss" scoped>
.wrapper {
    display: inline-flex;
}
p {
    color: #7e8a90;
    font-size: 15px;
    font-weight: 400;
    line-height: 19px;
    margin-bottom: 6px;
}
h6 {
    color: #2e3a40;
    font-size: 30px;
    font-weight: 300;
}

.icon {
    margin-right: 13px;
    display: inline-flex;
}

svg {
    display: inline-flex;
}
</style>
