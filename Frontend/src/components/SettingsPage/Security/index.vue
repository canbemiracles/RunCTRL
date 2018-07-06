<template lang="html">
  <div class="">
    <h2>Security</h2>
    <div class="top">
      <div class="icon-wrapper">
         <svg class="icon"><use :xlink:href="'images/icons-sprite.svg#' + (enable2fa ? 'closed' : 'open')"></use></svg>
      </div>
      <div class="">
        <h4>Secure your account</h4>
        <p>Two-factor authentication is an extra layer of security for your Apple ID designed to ensure that you're the only person who can access your account, even if someone knows your password.</p>
      </div>
      <div class="switch-wrapper">
        <label class="switch">
          <input type="checkbox" v-model="checked">
          <span class="slider round"></span>
        </label>
      </div>
    </div>
    <div class="table-wraper">
      <table class="m-table">
        <thead>
          <th>Date</th>
          <th>Time</th>
          <th>Country</th>
          <th>IP</th>
        </thead>
        <tbody>
          <tr v-for="(item, i) in logins" :key="i">
            <td>{{item.date | moment('DD MMM YYYY')}}</td>
            <td>{{item.date | moment('H:MM a')}}</td>
            <td>{{item.country_name}}</td>
            <td>{{item.ip}}</td>
          </tr>
        </tbody>
      </table>
      <button class="load-more" @click="page += 1; loadLogins()">load more</button>

      <b-modal size="lg"
               v-model="showEnableDialog"
               hide-header
               hide-footer
               @hidden="hiddenEnableDialog">

               <close-button class="close" @click="showEnableDialog = false; checked = false" />
               <h3>Connect your app</h3>
               <p>Once signed in, you won’t be asked for a verification code on that device again unless you sign out completely, erase the device, or need to change your password for security reasons. </p>
               <div class="qr-wrapper">
                 <img src="../../../assets/images/qr-code.png" alt="">
               </div>
               <div class="trouble">
                 <p>Having trouble scanning the code? <a href="#">Try this.</a></p>
               </div>
               <div class="controls">
                 <input type="text" name="" value="" placeholder="Enter code">
                 <button @click="enable2fa = true; showEnableDialog = false">Submit</button>
               </div>

      </b-modal>

      <b-modal size="md"
               v-model="showDisableDialog"
               hide-header
               hide-footer
               @hidden="hiddenDisableDialog">
               <close-button class="close" @click="showDisableDialog = false; checked = true" />
               <h3>Disable two-factor authentication?</h3>
               <p>This reduces the security level of your account and not recommended.</p>
               <p>Are you sure to continue?</p>
               <div class="controls">
                 <button @click="showDisableDialog = false">Cancel</button>
                 <button @click="enable2fa = false; showDisableDialog = false" class="blue">Yes</button>
               </div>
      </b-modal>

    </div>
  </div>
</template>

<script>
import 'images/sprites/security/open.svg'
import 'images/sprites/security/closed.svg'
export default {
  data: () => ({
    enable2fa: false,
    checked: false,
    showEnableDialog: false,
    showDisableDialog: false,
    logins: [],
    page: 1,

  }),
  methods: {
    hiddenEnableDialog () {
      if(!this.enable2fa) this.checked = false
    },
    hiddenDisableDialog () {
      if(this.enable2fa) this.checked = true
    },
    loadLogins() {
      this.$http.get(`api/v1/users/${this.$auth.user().id}/recent_logins/?page=${this.page}`).then(
        res => this.logins = this.logins.concat(res.body.items)
      )
    }
  },
  created() {
    this.loadLogins()
  },
  watch: {
    checked(value) {
      if (value !== this.enable2fa) {
        if (value) {
          this.showEnableDialog = true
        } else {
          this.showDisableDialog = true
        }
      }
    }
  },
  components: {
    closeButton: require('../../Common/CloseBtn'),
  }
}
</script>

<style lang="scss" src="../Notification/style.scss" scoped></style>
<style lang="scss" src="./style.scss" scoped></style>
<style lang="scss">
.modal-content {
    box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
    border-radius: 2px;
    padding: 30px;
}

.fade {
    background-color: rgba(43, 69, 83, 0.2);
}
</style>
