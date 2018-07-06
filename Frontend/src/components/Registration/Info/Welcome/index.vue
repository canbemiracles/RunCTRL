<template>
<div class="d-flex container-fluid p-0 flex-column">
  <app-header :itemsMenu="this.menu"
              :search="false"
              :accoutItems="false">
    <a href="#"
       slot="right"
       :slot-scope="{ logout }"
       @click.prevent="logout()"
       class="nav-link">Log out</a>
  </app-header>
  <div class="thank_you pad_top_info flex-grow">
    <div class="ty_top">
      <span>Welcome to Run Control</span>
    </div>
    <div class="round_130 step1_2_bor_col">
      <div class="round_95">
        <svg xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink"
             version="1.1"
             id="Capa_1"
             x="0px"
             y="0px"
             viewBox="0 0 27.857 27.857"
             style="enable-background:new 0 0 27.857 27.857;"
             xml:space="preserve"
             width="30px"
             height="30px">
                        <path d="M2.203,5.331l10.034,7.948c0.455,0.36,1.082,0.52,1.691,0.49c0.608,0.03,1.235-0.129,1.69-0.49    l10.034-7.948c0.804-0.633,0.622-1.152-0.398-1.152H13.929H2.604C1.583,4.179,1.401,4.698,2.203,5.331z" fill="#0fd5f7" />
                        <path d="M26.377,7.428l-10.965,8.325c-0.41,0.308-0.947,0.458-1.482,0.451    c-0.536,0.007-1.073-0.144-1.483-0.451L1.48,7.428C0.666,6.811,0,7.142,0,8.163v13.659c0,1.021,0.836,1.857,1.857,1.857h12.071H26    c1.021,0,1.857-0.836,1.857-1.857V8.163C27.857,7.142,27.191,6.811,26.377,7.428z" fill="#0fd5f7" />
                    </svg>
      </div>

      <div class="round_30">
        <svg data-v-2bb3b3ff=""
             width="15"
             height="11"
             viewBox="0 0 1792 1792"
             xmlns="http://www.w3.org/2000/svg"
             fill="#0ecdee"><path data-v-2bb3b3ff="" d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"></path></svg>
      </div>
    </div>

    <div class="ty_bot">
      <div class="success"
           v-if="success_message">{{success_message}}</div>
      Please Confirm your account by clicking on the verification link in the e-mail we sent you.
    </div>
    <div class="ty_mail">
      {{data.email}}
    </div>

    <div class="welcome_link">
      <a href="#"
         @click.prevent="resendLink">Resend verification link</a>
    </div>
  </div>
  <app-footer></app-footer>
</div>
</template>
<script>
import {
  mapGetters,
  mapMutations
} from 'vuex';
export default {
  data: function() {
    return {
      data: "",
      success_message: "",
      menu: [{
          name: "Welcome",
          link: "/welcome"
        },
        {
          name: "FAQ",
          link: "/faq"
        },
      ],
      accoutItems: [
        {
          name: "Log out",
          link: "#",
          icon: '<svg fill="#97a7af" width="15" height="15" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M704 1440q0 4 1 20t.5 26.5-3 23.5-10 19.5-20.5 6.5h-320q-119 0-203.5-84.5t-84.5-203.5v-704q0-119 84.5-203.5t203.5-84.5h320q13 0 22.5 9.5t9.5 22.5q0 4 1 20t.5 26.5-3 23.5-10 19.5-20.5 6.5h-320q-66 0-113 47t-47 113v704q0 66 47 113t113 47h312l11.5 1 11.5 3 8 5.5 7 9 2 13.5zm928-544q0 26-19 45l-544 544q-19 19-45 19t-45-19-19-45v-288h-448q-26 0-45-19t-19-45v-384q0-26 19-45t45-19h448v-288q0-26 19-45t45-19 45 19l544 544q19 19 19 45z"/></svg>',
          submenuList: []
        },
      ]
    }
  },
  computed: {
    ...mapGetters(['getUserData', 'getLoginData'])
  },
  mounted() {
    //Получаем данные для отправки письма на подтверждение
    this.data = this.getUserData
    if (!this.data.email) {
      this.data = {
        email: this.getLoginData.username
      }
    }
    this.setStep(2);
  },
  methods: {
    ...mapMutations(['setStep']),
    resendLink() {
      if (this.data) {
        this.$http({
            url: "api/v1/users/resend_confirmation",
            method: "POST",
            body: this.data,
            emulateJSON: true
          })
          .then(response => {
            this.success_message = response.body.message;
          }, response => {
            this.error = response.data;
          });
      }
    },
    logout() {
      this.$auth.logout({
        success: function() {
          location.reload();
        }
      });

    }
  },
  components: {
    appHeader: require('../../../Header'),
    appFooter: require('../../../Footer')
  }
}
</script>

<style lang="scss" src="../style.scss" scoped></style>
<style lang="scss" src="../../style.scss" scoped></style>
