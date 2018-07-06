<template lang="html">
  <div class="">
    <h2>Newsletter Subscribtion</h2>
    <div class="top">
      <svg class="icon"><use :xlink:href="'images/icons-sprite.svg#' + (subscribed ? 'subscribed' : 'unsubscribed')"></use></svg>
      <div class="text-block">
        <h3>{{subscribed ? 'You are subscribed for Run Control newsletter.' : 'You aren\'t  subscribed for Run Control newsletter.'}}</h3>
        <p>So when its time to finally get your children that very first telescope, you want to make sure it’s just the right thing.</p>
      </div>
    </div>
    <div class="divider"></div>
    <div class="wrapper">
      <label class="checkbox-wrapper">
        <input v-model="subscribed" class="checkbox" type="checkbox">
        <span class="checkmark"></span>
      </label>
      <div class="text-block">
        <h4>News and Announcements</h4>
        <p>So when its time to finally get your children that very first telescope, you want to make sure it’s just the right thing.</p>
      </div>
    </div>
    <div class="wrapper">
      <label class="checkbox-wrapper">
        <input v-model="subscribed" class="checkbox" type="checkbox">
        <span class="checkmark"></span>
      </label>
      <div class="text-block">
        <h4>Offers</h4>
        <p>So when its time to finally get your children that very first telescope, you want to make sure it’s just the right thing.</p>
      </div>
    </div>
    <div class="wrapper">
      <label class="checkbox-wrapper">
        <input v-model="subscribed" class="checkbox" type="checkbox">
        <span class="checkmark"></span>
      </label>
      <div class="text-block">
        <h4>Notifications</h4>
        <p>So when its time to finally get your children that very first telescope, you want to make sure it’s just the right thing.</p>
      </div>
    </div>
    <button class="submit-btn" @click="subscribed = !subscribed">{{subscribed ? 'Unsubscribe' : 'Subscribe'}}</button>
  </div>
</template>

<script>
import 'images/sprites/subscriptions/subscribed.svg'
import 'images/sprites/subscriptions/unsubscribed.svg'
export default {
  data: () => ({
    subscribed: true,
  }),
}
</script>

<style lang="scss" src="./style.scss" scoped>
</style>
