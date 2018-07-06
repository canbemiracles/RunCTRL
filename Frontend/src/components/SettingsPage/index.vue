<template lang="html">
  <div class="container-fluid p-0 d-flex flex-column">
      <app-header :reports="16" class="stable-sticky"></app-header>
      <div class="page-container">
        <div class="row">
          <div class="col col-lg-3 col-xl-2 side-wrap">
            <aside class="side-menu">

              <div class="photo">
                <img v-if="employee.avatar"
                     :src="$http.options.root + employee.avatar.path">
                <img v-else
                     src="../EmployeesList/CreateEmployee/avatar_placeholder.png">
              </div>

              <div class="actions">
                <button>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M381.6 65.5h-53.9V54C327.7 24.2 303.5 0 273.7 0h-35.3c-29.8 0-54 24.2-54 54v11.5h-53.9c-34.8 0-63 28.3-63 63v16H444.7v-16C444.7 93.8 416.4 65.5 381.6 65.5zM297.3 65.5H214.7V54c0-13 10.6-23.6 23.6-23.6h35.3c13 0 23.6 10.6 23.6 23.6V65.5z" class="a"/><path d="M88.5 175v306.1c0 17.1 13.9 30.9 30.9 30.9h273.2c17.1 0 30.9-13.9 30.9-30.9V175H88.5zM197.5 466.2h-30.4V220.8h30.4V466.2zM271.2 466.2h-30.4V220.8h30.4V466.2zM344.9 466.2h-30.4V220.8h30.4V466.2z"/></svg>
                </button>
                <button >
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 350 350"><path d="M175 171.2c38.9 0 70.5-38.3 70.5-85.6C245.5 38.3 235.1 0 175 0s-70.5 38.3-70.5 85.6C104.5 132.9 136.1 171.2 175 171.2z"/><path d="M41.9 301.9C41.9 299 41.9 301 41.9 301.9L41.9 301.9z"/><path d="M308.1 304.1C308.1 303.3 308.1 298.6 308.1 304.1L308.1 304.1z"/><path d="M307.9 298.4c-1.3-82.3-12.1-105.8-94.4-120.7 0 0-11.6 14.8-38.6 14.8s-38.6-14.8-38.6-14.8c-81.4 14.7-92.8 37.8-94.3 118 -0.1 6.5-0.2 6.9-0.2 6.1 0 1.4 0 4.1 0 8.7 0 0 19.6 39.5 133.1 39.5 113.5 0 133.1-39.5 133.1-39.5 0-3 0-5 0-6.4C308.1 304.6 308 303.7 307.9 298.4z"/></svg>
                </button>
              </div>

              <ul>
                <li>
                  <router-link :to="{ name: 'profile'}">Profile</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'company_information'}">Company Information</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'company_settings'}">Company Settings</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'billing'}">Billing</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'security'}">Security</router-link>
                </li>
                <div class="separator" />
                <li>
                  <router-link :to="{ name: 'email_subscription'}">Email Subscription</router-link>
                </li>
                <li>
                  <router-link :to="{ name: 'notification'}">Notifications</router-link>
                </li>
              </ul>
            </aside>
          </div>
          <div class="col col-lg-9 col-xl-10">
            <router-view :userData="employee"></router-view>
          </div>

        </div>
      </div>
      <app-footer />
  </div>
</template>

<script>
export default {
  data: () => ({
    employee: {}
  }),
  created() {
    this.$http.get(`api/v1/users/current`)
      .then(
        res => this.employee = res.body
      )
  },
  components: {
    appHeader: require('../Header'),
    appFooter: require('../Footer'),
  }
}
</script>

<style lang="scss" src="./style.scss" scoped></style>
