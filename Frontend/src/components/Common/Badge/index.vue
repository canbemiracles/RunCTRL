<template>
  <div class="badge_wrap">

    <span class="badge" :style="{ width }">
      {{ text }}
      <a href="#" @click.prevent="$emit('close')">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="10" height="10"><path d="M28.2 24L47.1 5.1c1.2-1.2 1.2-3.1 0-4.2 -1.2-1.2-3.1-1.2-4.2 0L24 19.7 5.1 0.9c-1.2-1.2-3.1-1.2-4.2 0 -1.2 1.2-1.2 3.1 0 4.2l18.9 18.9L0.9 42.9c-1.2 1.2-1.2 3.1 0 4.2C1.5 47.7 2.2 48 3 48s1.5-0.3 2.1-0.9l18.9-18.9L42.9 47.1c0.6 0.6 1.4 0.9 2.1 0.9s1.5-0.3 2.1-0.9c1.2-1.2 1.2-3.1 0-4.2L28.2 24z" fill="#627680"/></svg>
      </a>
    </span>

    <slot></slot>
  </div>

</template>

<script>
export default {
  props: {
    text: {
      type: String,
      required: true
    },
    width: {
      type: String
    }
  }
}
</script>

<style lang="scss" scoped>

.badge_wrap {
  display: inline-block;
}

.badge {
  display: inline-block;
  padding: 7px 35px 7px 13px;
  background: #e9eff2;
  font-size: 15px;
  border-radius: 15px;
  color: #627680;
  position: relative;
  overflow: hidden;
  text-overflow: ellipsis;
  text-align: left;

  a {
    height: 14px;
    padding: 2px 10px 0 7px;
    border-left: 1px solid #627680;
    text-decoration: none;
    position: absolute;
    top: 8px;
    right: 0;

    svg {
      vertical-align: top;
    }
  }
}

</style>
