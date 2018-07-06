<template>
<div class="login-bg">
  <div class="login-container login-page">
    <div class="logo-main">
      <img src="../../assets/images/logo-main.svg" alt="" class="logo-img">
    </div>
    <transition name="fade" mode="out-in">
      <router-view></router-view>
    </transition>
  </div>
</div>
</template>

<script>
export default {
  name: 'Login',
  data: () => ({

  }),
  created(){
    
  }
}
</script>

<style lang="scss" src="./style.scss" scoped></style>
