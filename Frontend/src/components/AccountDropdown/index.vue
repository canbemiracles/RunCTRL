<template>
<div class="header-account-block d-flex dropdown btn-group">
  <div class="user-name">{{dataUser.name}}</div>
  <div class="user-picture">
    <b-img :src="dataUser.imgName"
           alt=""
           class="user-picture__image" />
  </div>
  <b-dropdown id="ddown3"
              @shown="dropShow"
              right
              class="m-md-2 d-flex"
              @hidden="closeAccordion">
    <a :href="item.link"
       v-for="(item, index) in dropItems"
       :key="index"
       @click.prevent="handleClick(item)"
       class="dropdown-item d-flex">
                <b-button  v-b-toggle ="'collapse' + index"
                           :aria-controls="'collapse' + index" class="collapse-item d-flex" role="tab">
                    <i class="dropmenu-icon d-flex" v-html="item.icon"></i>
                    <div class="dropdown-item-name">{{item.name}}</div>
                    <span class="notifications-count" v-if=" item.name =='Notifications'">{{notify}}</span>
                </b-button>
                <b-collapse  v-if="item.submenuList.length" :ref="'collapse' + index" :id="'collapse' + index" accordion="my-accordion" role="tabpanel" @hide="dropdownItemHide(index)">
                    <b-card-body class="submenu-wrapper">
                        <div class="submenu-block">
                            <ul class="submenu">
                                <li class="submenu__item" v-for="(subItem, index) in item.submenuList" :key="index">
                                    <router-link class="submenu__link" :to="subItem.link">{{subItem.name}}</router-link>
                                </li>
                            </ul>
                        </div>
                    </b-card-body>
                </b-collapse>
            </a>
  </b-dropdown>
</div>
</template>
<script>
export default {
  data: () => ({
    dataUser: {
      imgName: "",
      name: ""
    },
    notify: 5,
    dropIsOpen: false,
  }),
  props: {
    dropItems: Array
  },
  methods: {
    dropShow() {
      this.dropIsOpen = true;
    },
    dropdownItemHide(index) {
      if (this.dropIsOpen) {
        var id = "#collapse" + index;
        var link = $(id)
          .closest(".dropdown-item")
          .attr("href");
        this.$router.push(link);
      }
    },
    closeAccordion() {
      this.dropIsOpen = false;
      for (let i = 0; i < this.dropItems.length; i++) {
        let ref = "collapse" + i;
        if (this.$refs[ref]) {
          this.$refs[ref][0].show = false;
        }
      }
    },
    setUser() {
      let user = this.$auth.user();
      if (this.$router.currentRoute.name == 'Registration' &&
        this.$router.currentRoute.params.step == 'step-3' &&
        !user.first_name) {
        this.$auth.fetch()
          .then(res => {
            user = this.$auth.user();
            this.dataUser.imgName = require("images/user_default.jpg");
            this.dataUser.name = `${user.first_name} ${user.last_name}`;
          });
      } else {
        this.dataUser.imgName = user.avatar ?
          this.$http.options.root + '/' + user.avatar.path :
          require("images/user_default.jpg");
        this.dataUser.name = user.first_name ?
          `${user.first_name} ${user.last_name}` :
          "Anonym";
      }

    },
    handleClick(item) {
      if (!item.submenuList.length) {
        if (item.name == "Log out") {
          this.$auth.logout({
            success: function() {
              this.$router.push({
                name: 'login'
              });
            }
          });
        } else {
          this.$router.push(item.link);
        }
      }
    }
  },
  mounted() {
    this.setUser();
  }
};
</script>

<style lang="scss" src="./style.scss" scoped></style>
<style lang="scss" src="./style-no-scoped.scss"></style>
