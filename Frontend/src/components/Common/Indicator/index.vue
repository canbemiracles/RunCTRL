<template>
  <div class="indicator" :style="'background-color: ' + colorHex">
    <span class="center">{{ value }}</span>
  </div>
</template>

<script>
import palette from '../../../stylesheets/index'

export default {
  props: {
    value: {
      type: Number,
      default: 0
    },
    color: {
      type: String,
      default: 'green'
    },
  },

  computed: {
    colorHex() {
      return palette[this.value ? this.color : 'lightgray']
    }
  }
}
</script>

<style lang="scss" scoped>

.indicator {
  display: inline-flex;
  justify-content: center;
  align-items: center;
  padding: 2px 12px;
  margin: 0 11px;
  color: #fff;
  
  border-radius: 20px;
  position: relative;
  .center {
    font: 15px 'Roboto-Light';
  }
}

</style>
