<template>
<div class="navigation-panel">
  <b-navbar toggleable="md">
    <b-nav-toggle target="nav_collapse"></b-nav-toggle>
    <b-navbar-brand href="#"
                    @click="clickLogo">
      <div class="logo">
        <svg class="logo-img"
             xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             width="71"
             height="72"
             viewBox="0 0 71 72"><defs><path id="a" d="M37.1 45.21c.2.05.31.13.31.37v1.09c0 .14-.06.21-.2.21H34.4c-1.89 0-2.22.82-2.22 3.49 0 2.67.33 3.51 2.22 3.51h2.81c.14 0 .2.07.2.21v1.11c0 .22-.11.3-.31.35-.67.14-1.49.21-2.7.21-3.5 0-4.4-1.23-4.4-5.39 0-4.15.9-5.37 4.4-5.37 1.21 0 2.03.08 2.7.21"/><path id="b" d="M39.18 50.4c0-2.69.37-3.52 2.26-3.52 1.89 0 2.26.83 2.26 3.52 0 2.66-.37 3.48-2.26 3.48-1.89 0-2.26-.82-2.26-3.48zm6.7 0c0-4.17-.94-5.4-4.44-5.4-3.49 0-4.44 1.23-4.44 5.4 0 4.14.95 5.36 4.44 5.36 3.5 0 4.44-1.22 4.44-5.36z"/><path id="c" d="M55.61 45c.13 0 .2.07.2.22v10.17c0 .15-.07.22-.2.22h-1.28c-.33 0-.49-.08-.68-.35l-4.5-6.59v6.72c0 .15-.07.22-.2.22h-1.74c-.14 0-.21-.07-.21-.22V45.22c0-.15.07-.22.21-.22h1.35c.55 0 .62.12.85.45l4.25 6.21v-6.44c0-.15.07-.22.21-.22z"/><path id="d" d="M64.09 45c.13 0 .22.07.22.21v1.46c0 .14-.09.21-.22.21h-2.87v8.51c0 .15-.07.22-.2.22h-1.74c-.14 0-.21-.07-.21-.22v-8.51h-2.86c-.14 0-.21-.07-.21-.21v-1.46c0-.14.07-.21.21-.21z"/><path id="e" d="M68.63 46.88c1.63 0 2.06.33 2.06 1.7 0 1.39-.43 1.61-2.06 1.61h-1.48v-3.31zm4.24 1.64c0-2.74-1.02-3.52-4.02-3.52-1.12 0-2.44.04-3.64.15-.17.01-.21.04-.21.19v10.13c0 .18.04.22.21.22h1.74c.16 0 .2-.04.2-.22v-3.62h1.26l2.25 3.54c.14.2.23.3.55.3h1.73c.2 0 .27-.12.16-.27l-2.33-3.7c1.55-.31 2.1-1.13 2.1-3.2z"/><path id="f" d="M76.18 50.4c0-2.69.37-3.52 2.26-3.52 1.89 0 2.26.83 2.26 3.52 0 2.66-.37 3.48-2.26 3.48-1.89 0-2.26-.82-2.26-3.48zm6.7 0c0-4.17-.94-5.4-4.44-5.4-3.49 0-4.44 1.23-4.44 5.4 0 4.14.95 5.36 4.44 5.36 3.5 0 4.44-1.22 4.44-5.36z"/><path id="g" d="M85.95 45c.13 0 .2.07.2.21v8.52h4.5c.13 0 .2.07.2.21v1.46c0 .14-.07.21-.2.21h-6.44c-.14 0-.21-.07-.21-.22V45.21c0-.14.07-.21.21-.21z"/><path id="h" d="M47.73 33.64h1.59c.68 0 1.23.01 1.67.07 1.18.17 1.58.7 1.58 2.31s-.4 2.11-1.58 2.25c-.44.06-.99.06-1.67.06h-1.59zm-.13 9.02c.09 0 .13-.03.13-.12v-3.56h2.53l.73 1.14 1.5 2.37c.08.12.13.17.3.17h.44c.11 0 .14-.08.09-.15l-2.27-3.59c1.66-.17 2.23-.77 2.23-2.92s-.59-2.77-2.29-2.94c-.46-.05-1-.06-1.63-.06-.53 0-1.49.01-2.23.05-.09 0-.13.03-.13.12v9.37c0 .09.04.12.13.12z"/><path id="i" d="M59.65 42.66c2.91 0 3.64-.97 3.64-4.86v-4.68c0-.09-.03-.12-.12-.12h-.48c-.09 0-.11.03-.11.12v4.68c0 3.37-.58 4.22-2.93 4.22s-2.94-.85-2.94-4.22v-4.68c0-.09-.02-.12-.11-.12h-.48c-.08 0-.12.03-.12.12v4.68c0 3.89.73 4.86 3.65 4.86"/><path id="j" d="M72.3 33h-.48c-.07 0-.11.04-.11.12v8.37l-5.61-8.27c-.14-.21-.22-.22-.43-.22h-.54c-.08 0-.13.04-.13.12v9.36c0 .08.05.11.13.11h.47c.07 0 .13-.03.13-.11v-8.57l5.77 8.53c.12.15.21.15.3.15h.5c.08 0 .12-.03.12-.11v-9.36c0-.08-.04-.12-.12-.12"/><path id="k" d="M51 10.28l1.8 6.35a31.18 31.18 0 0 1 8.08-1.2V9c-3.09.05-7.04.46-9.88 1.28"/><path id="l" d="M51.12 14A34.44 34.44 0 0 0 35 25.19l2.28 1.77c3.79-4.7 8.89-8.3 14.75-10.22z"/><path id="m" d="M62.64 14a33.44 33.44 0 0 1 32.39 29.01l-1.04.14a32.39 32.39 0 0 0-31.35-28.1zM31.97 59.59a32.44 32.44 0 0 0 25.04 19.77l-.17 1.04A33.5 33.5 0 0 1 31 59.99z"/><path id="n" d="M85.01 64a28.2 28.2 0 0 1-27.04 11.38 28.02 28.02 0 0 1-16.56-8.84L39 68.81a31.3 31.3 0 0 0 18.48 9.84 31.52 31.52 0 0 0 30.27-12.78z"/><path id="o" d="M84.12 25.04A30.64 30.64 0 0 0 63 16v4.76a26.16 26.16 0 0 1 23.39 16.03l4.4-1.84a30.82 30.82 0 0 0-6.67-9.91"/><path id="p" d="M50.96 14.15L50.25 12A36.9 36.9 0 0 0 25 46.97a36.6 36.6 0 0 0 3.96 16.6l2.02-1.02a34.63 34.63 0 0 1 19.98-48.4"/><path id="q" d="M85.59 58A26.14 26.14 0 0 1 63 73.32l.07 1.56a27.7 27.7 0 0 0 23.95-16.23z"/></defs><g transform="translate(-24 -9)"><use fill="#3c3c3b" xlink:href="#a"/><use fill="#3c3c3b" xlink:href="#b"/><use fill="#3c3c3b" xlink:href="#c"/><use fill="#3c3c3b" xlink:href="#d"/><use fill="#3c3c3b" xlink:href="#e"/><use fill="#3c3c3b" xlink:href="#f"/><use fill="#3c3c3b" xlink:href="#g"/><use fill="#3c3c3b" xlink:href="#h"/><use fill="#3c3c3b" xlink:href="#i"/><g><use fill="#3c3c3b" xlink:href="#j"/></g><g><use fill="#e74142" xlink:href="#k"/></g><g><use fill="#f4c22d" xlink:href="#l"/></g><g><use fill="#82bc4d" xlink:href="#m"/></g><g><use fill="#f4c22d" xlink:href="#n"/></g><g><use fill="#82bc4d" xlink:href="#o"/></g><g><use fill="#e74142" xlink:href="#p"/></g><g><use fill="#e74142" xlink:href="#q"/></g></g></svg>
      </div>
    </b-navbar-brand>
    <b-collapse is-nav
                id="nav_collapse">
      <b-navbar-nav>
        <app-menu :items="itemsMenu"
                  :reportsCount="reports"></app-menu>
      </b-navbar-nav>
      <!-- Right aligned nav items -->
      <b-navbar-nav class="ml-auto navbar-search"
                    v-if="search">
        <div class="header-search">
          <figure>
            <form class="form-inline my-2 my-lg-0 d-flex flex-nowrap">
              <button class="btn btn-search my-2 my-sm-0"
                      type="submit"
                      @click.prevent>
                <svg width="20" class="search-icon" height="20" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1216 832q0-185-131.5-316.5t-316.5-131.5-316.5 131.5-131.5 316.5 131.5 316.5 316.5 131.5 316.5-131.5 131.5-316.5zm512 832q0 52-38 90t-90 38q-54 0-90-38l-343-342q-179 124-399 124-143 0-273.5-55.5t-225-150-150-225-55.5-273.5 55.5-273.5 150-225 225-150 273.5-55.5 273.5 55.5 225 150 150 225 55.5 273.5q0 220-124 399l343 343q37 37 37 90z" />
                </svg>
              </button>
              <input class="form-control mr-sm-2 search-input"
                     type="text"
                     placeholder="">
            </form>
          </figure>
        </div>
      </b-navbar-nav>
      <accountDropdown :dropItems="accoutItems" v-if="accoutItems"></accountDropdown>
      <slot name="right"></slot>
    </b-collapse>
  </b-navbar>
</div>
</template>
<script>
import {mapGetters} from 'vuex'
export default {
  name: 'appHeader',
  data: () => ({
  }),
  props: {
    itemsMenu: {
      type: Array,
      default: function() {
        return [{
            name: "Employees",
            link: "/employees-list"
          },
          {
            name: "Tasks",
            link: "/task-calendar"
          },
          {
            name: "Branches",
            link: "/branch-data"
          },
          {
            name: "Reports",
            link: "/reports-page"
          },
          {
            name: "Support",
            link: "#"
          },
        ]
      }
    },
    reports: Number,
    search: {
      type: Boolean,
      default: true
    },
    accoutItems: {
      type: Array/Boolean,
      default: function() {
        return [{
            name: "Settings",
            link: "/settings",
            icon: '<svg  width="15" fill="#97a7af" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1152 896q0-106-75-181t-181-75-181 75-75 181 75 181 181 75 181-75 75-181zm512-109v222q0 12-8 23t-20 13l-185 28q-19 54-39 91 35 50 107 138 10 12 10 25t-9 23q-27 37-99 108t-94 71q-12 0-26-9l-138-108q-44 23-91 38-16 136-29 186-7 28-36 28h-222q-14 0-24.5-8.5t-11.5-21.5l-28-184q-49-16-90-37l-141 107q-10 9-25 9-14 0-25-11-126-114-165-168-7-10-7-23 0-12 8-23 15-21 51-66.5t54-70.5q-27-50-41-99l-183-27q-13-2-21-12.5t-8-23.5v-222q0-12 8-23t19-13l186-28q14-46 39-92-40-57-107-138-10-12-10-24 0-10 9-23 26-36 98.5-107.5t94.5-71.5q13 0 26 10l138 107q44-23 91-38 16-136 29-186 7-28 36-28h222q14 0 24.5 8.5t11.5 21.5l28 184q49 16 90 37l142-107q9-9 24-9 13 0 25 10 129 119 165 170 7 8 7 22 0 12-8 23-15 21-51 66.5t-54 70.5q26 50 41 98l183 28q13 2 21 12.5t8 23.5z"/></svg>',
            submenuList: [{
              name: "Profile",
              link: "/profile"
            }, {
              name: "Company Information",
              link: "/company_information"
            }, {
              name: "Company Settings",
              link: "/company_settings"
            }, {
              name: "Billing",
              link: "/billing"
            }]
          },
          {
            name: "Email Subscription",
            link: "/email_subscription",
            icon: '<svg fill="#97a7af" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1792 710v794q0 66-47 113t-113 47h-1472q-66 0-113-47t-47-113v-794q44 49 101 87 362 246 497 345 57 42 92.5 65.5t94.5 48 110 24.5h2q51 0 110-24.5t94.5-48 92.5-65.5q170-123 498-345 57-39 100-87zm0-294q0 79-49 151t-122 123q-376 261-468 325-10 7-42.5 30.5t-54 38-52 32.5-57.5 27-50 9h-2q-23 0-50-9t-57.5-27-52-32.5-54-38-42.5-30.5q-91-64-262-182.5t-205-142.5q-62-42-117-115.5t-55-136.5q0-78 41.5-130t118.5-52h1472q65 0 112.5 47t47.5 113z"/></svg>',
            submenuList: []
          },
          {
            name: "Notifications",
            link: "/notification",
            icon: '<svg fill="#97a7af" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M912 1696q0-16-16-16-59 0-101.5-42.5t-42.5-101.5q0-16-16-16t-16 16q0 73 51.5 124.5t124.5 51.5q16 0 16-16zm816-288q0 52-38 90t-90 38h-448q0 106-75 181t-181 75-181-75-75-181h-448q-52 0-90-38t-38-90q50-42 91-88t85-119.5 74.5-158.5 50-206 19.5-260q0-152 117-282.5t307-158.5q-8-19-8-39 0-40 28-68t68-28 68 28 28 68q0 20-8 39 190 28 307 158.5t117 282.5q0 139 19.5 260t50 206 74.5 158.5 85 119.5 91 88z"/></svg>',
            submenuList: []
          },
          {
            name: "Log out",
            link: "#",
            icon: '<svg fill="#97a7af" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M704 1440q0 4 1 20t.5 26.5-3 23.5-10 19.5-20.5 6.5h-320q-119 0-203.5-84.5t-84.5-203.5v-704q0-119 84.5-203.5t203.5-84.5h320q13 0 22.5 9.5t9.5 22.5q0 4 1 20t.5 26.5-3 23.5-10 19.5-20.5 6.5h-320q-66 0-113 47t-47 113v704q0 66 47 113t113 47h312l11.5 1 11.5 3 8 5.5 7 9 2 13.5zm928-544q0 26-19 45l-544 544q-19 19-45 19t-45-19-19-45v-288h-448q-26 0-45-19t-19-45v-384q0-26 19-45t45-19h448v-288q0-26 19-45t45-19 45 19l544 544q19 19 19 45z"/></svg>',
            submenuList: []
          },
        ]
      }
    },
    slotClickHandler: {
      type: Array/Boolean,
      default: function() {
        return false
      }
    }
  },

  watch: {
    branches: {
      handler() {

        if (this.branches[0] && this.$route.name !== 'Welcome' && this.itemsMenu[0].name != 'Dashboard' && this.itemsMenu[0].name != 'Branch') {

          if (this.branches.length > 1 ) {
            this.itemsMenu.unshift({
              name: "Dashboard",
              link: "/dashboard-page"
            })
          } else {
            this.itemsMenu.unshift({
              name: "Branch",
              link: "/branch-page/" + this.branches[0].id
            })
            this.itemsMenu.splice(  this.itemsMenu.indexOf(this.itemsMenu.find(item => item.name === 'Branches')), 1)
          }
        }
      },
      deep: true,
      immediate: true
    }
  },

  computed: {
    ...mapGetters(['branches']),
  },

  methods: {
    clickLogo() {
      location.reload()
    }
  },

  components: {
    appMenu: require('../Menu'),
    accountDropdown: require('../AccountDropdown'),
  }
}
</script>

<style lang="scss" src="./style.scss"></style>
