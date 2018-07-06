<template>
  <button
    :class="['filter_tab', { arrow, active }]"
    v-text="text"
    @click.prevent="$emit('click')"
  ></button>
</template>

<script>
export default {
  props: {
    text: {
      type: [String, Number],
      required: true
    },
    arrow: Boolean, // Добавлять ли стрелку снизу
    active: Boolean // Активна ли кнопка
  }
}
</script>

<style lang="scss" scoped>

.filter_tab {
  background: #fff;
  padding: 5px 10px;
  border: 1px solid #d1d9de;
  border-radius: 2px;
  outline: none;
  color: #627680;
  font: 15px 'Roboto-Light';
  transition: all .2 ease;

  &:hover {
    border-color: $blue;
  }

  &.active {
    background: $blue;
    border-color: $blue;
    color: #fff;
    position: relative;
  }
}

.arrow.active:before {
  content: '';
  display: block;
  border: 5px solid transparent;
  border-top-color: $blue;
  position: absolute;
  bottom: -10px;
  left: 0;
  right: 0;
  width: 0;
  margin: auto;
}

</style>
