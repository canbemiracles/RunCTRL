<template lang="html">
  <b-modal size="lg"
           v-model="showModal"
           hide-header
           hide-footer
           v-if="report"
           @hidden="closePopup">
           <header class="header" :class="report.type">
             {{report.type}} Report
             <button class="close" @click="closePopup">×</button>
           </header>

           <div class="body">
             <div class="section">
               <div class="avatar-wrapper" v-if="report.branch_manager">
                 <img v-if="report.branch_manager.avatar"
                      :src="$http.options.root +'/' + report.branch_manager.avatar.path">
                 <img v-else
                      src="images/user_default.jpg">
                 <div class="">
                   <h3>{{report.branch_manager.first_name}} {{report.branch_manager.last_name}}</h3>
                   <p>Branch Manager</p>
                 </div>
               </div>
               <div class="right">
                 <h3>{{report.geographical_area && report.geographical_area.street_address ? report.geographical_area.street_address : ''}}</h3>
                 <p>{{report.created | moment(`dddd ${timeForamted}`)}}</p>
               </div>
             </div>
             <div v-if="report.type == 'Cashier'">
               <hr>
               <h2>Cashier report from {{report.geographical_area && report.geographical_area.street_address ? report.geographical_area.street_address : ''}} on {{report.created | moment(dateFormated)}}</h2>
               <div class="counters">
                 <counter title="Credits:" value="0">
                   <svg width="25" height="21"><use xlink:href="images/icons-sprite.svg#credits"></use></svg>
                 </counter>
                 <counter title="Cash:" value="0">
                   <svg width="21" height="20"><use xlink:href="images/icons-sprite.svg#cash"></use></svg>
                 </counter>
                 <counter title="Checks:" value="0">
                   <svg width="25" height="16"><use xlink:href="images/icons-sprite.svg#checks"></use></svg>
                 </counter>
                 <counter title="PayPal:" value="0">
                   <svg width="22" height="22"><use xlink:href="images/icons-sprite.svg#paypal"></use></svg>
                 </counter>
                 <counter title="Overal:" value="0" />
               </div>


             </div>
             <div v-else>
               <h2>{{report.title}}</h2>
               <p>{{report.description}}</p>
             </div>

           </div>

  </b-modal>
</template>

<script>
import 'images/sprites/cashier/credits.svg'
import 'images/sprites/cashier/checks.svg'
import 'images/sprites/cashier/cash.svg'
import 'images/sprites/cashier/paypal.svg'
export default {
  props: {
    show: Boolean,
    report: Object,
    timeFormat: String,
    dateFormat: String
  },
  data: () => ({
    showDisableDialog: true
  }),
  methods: {
    closePopup() {
      this.$emit('update:show', false)
    }
  },
  computed: {
    showModal: {
      get() {
        return this.show
      },
      set(value) {
        this.$emit('update:show', value)
      }
    },
    timeForamted(){
     return this.timeFormat ? this.timeFormat : 'H:MMa';
    },
    dateFormated(){
      return this.dateFormat ? this.dateFormat : 'DD MMM. YYYY';
    }
  },
  components: {
    cntxBtn: require('../../Common/CntxBtn'),
    counter: require('./Counter'),
  }
}
</script>
<style lang="scss" src="./style.scss" scoped>
</style > <style lang="scss" > .modal-content {
    box-shadow: 0 10px 20px rgba(28, 60, 77, 0.3), 0 0 10px rgba(28, 60, 77, 0.2);
    border-radius: 2px;
}

.modal-body {
    padding: 0;
}

.fade {
    background-color: rgba(43, 69, 83, 0.2);
}
</style>
