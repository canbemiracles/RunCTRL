<template>
  <div class="counter_wrap">
    <h6 :style="{ color: hex }">{{ label }}</h6>
    <p>{{ count }}<span v-if="sub">{{ `(${sub})` }}</span></p>
  </div>
</template>

<script>
import palette from '../../../stylesheets/index'
export default {
  props: {
    label: {
      type: String,
      required: true
    },
    labelColor: {
      type: String,
      default: 'gray'
    },
    count: {
      required: true
    },
    sub: {  // Дополнительная серая надпись снизу
      type: [String, Number],
      default: ''
    }
  },
  
  computed: {
    hex() {
      return palette[this.labelColor]
    }
  }
}
</script>

<style lang="scss" scoped>
.counter_wrap {
  display: inline-block;
  h6 {
    margin: 0;
    font-size: 16px;
  }
  p {
    margin: 0;
    color: #2e3a40;
    font-size: 40px;
    font-family: 'Roboto-Light', sans-serif;

    span {
      color: #7e8a90;
      font-size: 20px;
    }
  }
}

</style>
