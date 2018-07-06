<template>
  <div id="app">
    <div v-if="$auth.ready()" class="app-wrapper d-flex">
        <transition name="fade" mode="out-in"> 
          <router-view></router-view>
        </transition>
    </div>
    <div v-if="!$auth.ready()" class="app-wrapper app-wrapper_plug d-flex justify-content-center align-items-center">
            Loading...
    </div>
  </div>
</template>

<script>
export default {
  name: 'app',
  data: () => ({
  }),
  created() {
    this.$store.dispatch('fetchBranches')
  },
  components: {
 
  }
}
</script>

<style lang="scss">
.vdatetime-popup__header,
.vdatetime-popup__date-picker__item--selected > span > span,
.vdatetime-popup__date-picker__item--selected:hover > span > span {
  background: $blue!important
}

.vdatetime-popup__list-picker__item--selected,
.vdatetime-popup__actions__button {
  color: $blue!important
}

.datePicker_input {
  width: 100%;
  height: 100%;
  opacity: 0;
  position: relative;
  cursor: pointer;
  z-index: 3;
}
.datePicker_wrap {
  display: block;
  width: 50px;
  height: 50px;
  border: 2px solid #dfe4e6;
  border-radius: 50%;
  margin-top: 23px;
  position: relative;
  transition: all .2s ease;
  z-index: 1;

  &:after {
    content: url('data:image/svg+xml; utf8, <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 511.6 511.6"><path d="M482.5 83.9c-7.2-7.2-15.8-10.8-25.7-10.8h-36.5v-27.4c0-12.6-4.5-23.3-13.4-32.3C397.9 4.5 387.2 0 374.6 0h-18.3c-12.6 0-23.3 4.5-32.3 13.4 -8.9 8.9-13.4 19.7-13.4 32.3v27.4h-109.6v-27.4c0-12.6-4.5-23.3-13.4-32.3C178.6 4.5 167.9 0 155.3 0H137.1c-12.6 0-23.3 4.5-32.3 13.4 -8.9 8.9-13.4 19.7-13.4 32.3v27.4H54.8c-9.9 0-18.5 3.6-25.7 10.9 -7.2 7.2-10.8 15.8-10.8 25.7v365.5c0 9.9 3.6 18.5 10.9 25.7 7.2 7.2 15.8 10.8 25.7 10.8h402c9.9 0 18.5-3.6 25.7-10.8 7.2-7.2 10.9-15.8 10.9-25.7V109.6C493.4 99.7 489.7 91.2 482.5 83.9zM137 475.1H54.8v-82.2h82.2V475.1zM137 374.6H54.8v-91.4h82.2V374.6zM137 265H54.8v-82.2h82.2V265zM130.6 134.3c-1.8-1.8-2.7-3.9-2.7-6.4V45.7c0-2.5 0.9-4.6 2.7-6.4 1.8-1.8 3.9-2.7 6.4-2.7h18.3c2.5 0 4.6 0.9 6.4 2.7 1.8 1.8 2.7 3.9 2.7 6.4v82.2c0 2.5-0.9 4.6-2.7 6.4 -1.8 1.8-3.9 2.7-6.4 2.7H137.1C134.6 137 132.4 136.1 130.6 134.3zM246.7 475.1h-91.4v-82.2h91.4V475.1zM246.7 374.6h-91.4v-91.4h91.4V374.6zM246.7 265h-91.4v-82.2h91.4V265zM356.3 475.1h-91.4v-82.2h91.4V475.1zM356.3 374.6h-91.4v-91.4h91.4V374.6zM356.3 265h-91.4v-82.2h91.4V265zM349.9 134.3c-1.8-1.8-2.7-3.9-2.7-6.4V45.7c0-2.5 0.9-4.6 2.7-6.4 1.8-1.8 3.9-2.7 6.4-2.7h18.3c2.5 0 4.6 0.9 6.4 2.7 1.8 1.8 2.7 3.9 2.7 6.4v82.2c0 2.5-0.9 4.6-2.7 6.4 -1.8 1.8-3.9 2.7-6.4 2.7h-18.3C353.8 137 351.7 136.1 349.9 134.3zM456.8 475.1h-82.2v-82.2h82.2V475.1zM456.8 374.6h-82.2v-91.4h82.2V374.6zM456.8 265h-82.2v-82.2h82.2V265z" fill="#8b9da6"/></svg>');
    position: absolute;
    top: 13px;
    left: 13px;
    z-index: 2;
  }

  &:hover, &.active {
    background: #dfe4e6;
  }
}
.fade-enter{
    opacity: 0;
}
.fade-enter-active{
    transition: opacity .4s;
}
.fade-leave-active{
    transition: opacity .4s;
    opacity: 0; 
}
.fade-move{
   transition: transform .4s; 
}
</style>
