<template>
  <div
    v-if="active"
    class="confirm_popup"
    @click.self="$emit('close')">

    <div class="wrap">

      <header>
        <p>{{ title }}</p>
        <close-btn @click="$emit('close')" />
      </header>

      <div class="body">
        <slot></slot>
      </div>

      <footer>
        <small-btn
          :text="cancelText"
          @click="$emit('close')"
        />
        <small-btn
          :text="confirmText"
          @click="$emit('confirm')"
        />
      </footer>

    </div>

  </div>
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true
    },
    cancelText: {
      type: String,
      default: 'Cancel'
    },
    confirmText: {
      type: String,
      default: 'Ok'
    },
    active: Boolean
  },

  watch: {
    active(isActive) {
      // Выключаем скролл
      document.body.style.overflowY = isActive ? 'hidden' : 'auto'
    }
  },

  components: {
    closeBtn: require('../CloseBtn'),
    smallBtn: require('../SmallBtn')
  }
}
</script>

<style lang="scss" scoped>

.confirm_popup {
  display: flex;
  justify-content: center;
  align-items: center;
  background: rgba(0, 0, 0, .3);
  width: 100vw;
  height: 100vh;
  position: fixed;
  top: 0;
  left: 0;
  z-index: 100;

  .wrap {
    background: #fff;

    header, footer {
      padding: 0 20px;
      height: 60px;
      line-height: 60px;
    }

    header {
      display: flex;
      justify-content: space-between;
      background: #e9eff2;
    }

    .body {
      font-family: 'Roboto-Light';
      padding: 20px;
    }

    footer {
      text-align: right;
      border-top: 1px solid #e9eff2;

      button + button {
        margin-left: 5px;
      }
    }
  }
}

p {
  margin: 0;
}

</style>
