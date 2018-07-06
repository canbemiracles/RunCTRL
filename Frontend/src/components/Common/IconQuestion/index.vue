<template>
    <span class="icon-question-wrap"><span class="icon-question-txt"> ?</span> </span>
</template>
<script>
export default {
}
</script>
<style lang='scss' scoped>
.icon-question-wrap {
  width: 17px;
  height: 17px;
  flex-shrink: 0;
  border-radius: 50%;
  background-color: #e1e9ed;
  display: flex;
  position: relative;
  top: -1px;
  justify-content: center;
  align-items: center;
  margin-left: 5px;
}
.icon-question-txt{
  font-size: 11px;
  position: relative;
  top: 1px;
  color: #627680;
  cursor: default;
}
</style>