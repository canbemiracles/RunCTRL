<template>
<div class="container-fluid p-0 d-flex flex-column container-one-page">
  <app-header :reports="16"></app-header>
  <div class="main-wrap__branch-data">
    <button-plus class="plus"
                 @click="$router.push({name: 'branchFlow'})" />
    <back class="back"
          v-if="branches.length > 1"></back>
    <h2>Branch Data</h2>
    <table class="wrap-branch-data">
      <tbody>
        <branch-item v-for="(branch, index) in branches"
                     :key="branch.id"
                     :branch="branch"
                     :managers="managers"
                     :googlePlacesLoad="googlePlacesLoad"
                     @deleteBranch="showDeleteModal"
                     :supervisors="supervisors">{{index + 1}}</branch-item>
      </tbody>
    </table>

  </div>
  <!-- Modal Component MODAL ERROR -->
  <b-modal id="modalError"
           centered
           ref="modal"
           :ok-only="true"
           header-class="error-header">
    <div slot="modal-header"
         class="modal-title d-flex">
      <svg class="warning-icon"
           xmlns="http://www.w3.org/2000/svg"
           xmlns:xlink="http://www.w3.org/1999/xlink"
           width="42"
           height="42"
           viewBox="0 0 42 42"><defs><path id="63g8a" d="M117 781a20 20 0 1 1 40 0 20 20 0 0 1-40 0z"/><path id="63g8b" d="M135 783v-12h4v12zm0 7v-4h4v4z"/></defs><g><g transform="translate(-116 -760)"><use fill="#fff" fill-opacity="0" stroke="#f53f3f" stroke-miterlimit="50" stroke-width="1.5" xlink:href="#63g8a"/></g><g transform="translate(-116 -760)"><use fill="#f53f3f" xlink:href="#63g8b"/></g></g></svg>
      <span class="warn-title"> Warning! </span>
      <button type="button"
              aria-label="Close"
              class="close"
              @click="hideModal">×</button>
    </div>
    <p class="my-4">{{ errorMessage }}</p>
  </b-modal>

   <!-- Modal Component DELETE BRANCH -->
  <b-modal id="confirmDelBranch" centered>
    <div slot="modal-header" class="modal-title d-flex">
        <div class="icon-warning-wrap">
          <svg class="warning-icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="42" height="42" viewBox="0 0 42 42"><defs><path id="63g8a" d="M117 781a20 20 0 1 1 40 0 20 20 0 0 1-40 0z"/><path id="63g8b" d="M135 783v-12h4v12zm0 7v-4h4v4z"/></defs><g><g transform="translate(-116 -760)"><use fill="#fff" fill-opacity="0" stroke="#f47070" stroke-miterlimit="50" stroke-width="1.5" xlink:href="#63g8a"/></g><g transform="translate(-116 -760)"><use fill="#f47070" xlink:href="#63g8b"/></g></g></svg>
        </div>
        <span class="warn-title">Confirm delete </span>
        <button type="button" aria-label="Close" class="close" @click="hideDeleteModal">×</button>
    </div>
    <p class="my-4">Are you sure you want to delete this branch?</p>
    <div class="modal-buttons" slot="modal-footer">
        <button class="confirm-btn" @click="deleteBranchItem">ok</button>
        <button class="cancel-btn" @click="hideDeleteModal">Cancel</button>
    </div>
  </b-modal>
</div>
</template>
<script>
import {
  mapActions,
  mapGetters,
  mapMutations
} from 'vuex';
import 'images/sprites/arrow-up.svg'
import 'images/sprites/arrow-down.svg'
import {
  loadJs
} from '../Common/utils.js'
export default {

  data() {
    return {
      supervisors: [],
      managers: [],
      googlePlacesLoad: false,
      deleteBranchId: null,
    }
  },
  computed: {
    ...mapGetters(['apiKey', 'language', 'branches', 'loadStatus', 'errorMessage']),
  },
  created() {
    this.fetchBranches();

    this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/users/supervisors/`)
      .then(
        res => this.supervisors = res.body.map(item => ({
          full_name: item.first_name + ' ' + item.last_name,
          id: item.id
        })))
        // .filter((manager) => {
        //   return manager.confirmed;
        // }));

    this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/users/branch_managers/`)
      .then(
        res => this.managers = res.body.map(item => ({
          full_name: item.first_name + ' ' + item.last_name,
          id: item.id
        })))
        // .filter((supervisor) => {
        //   return supervisor.confirmed;
        // }));
  },
  watch: {
    'loadStatus' (value) {
      if (value) {
        this.fetchBranches();
        if (this.errorMessage) {
          this.$root.$emit('bv::show::modal', 'modalError');
        }
      }
    }
  },
  mounted() {
    if (!(window.google && google.maps.places)) {
      loadJs(`https://maps.googleapis.com/maps/api/js?key=${this.apiKey}&libraries=places&language=${this.language}`, () => {
        this.googlePlacesLoad = true;
      });
    }else{
      this.googlePlacesLoad = true;
    }
  },
  methods: {
    ...mapActions({
      fetchBranches: 'fetchBranches', 
      deleteBranchRequest : 'deleteBranch'
    }),
    hideModal() {
      this.$root.$emit('bv::hide::modal', 'modalError');
    },
    deleteBranchItem(){
      this.deleteBranchRequest(this.deleteBranchId);
      this.hideDeleteModal();
    },
    showDeleteModal(id){
      this.deleteBranchId = id;
      this.$root.$emit('bv::show::modal','confirmDelBranch');
    },
    hideDeleteModal(){
      this.deleteBranchId = null;
      this.$root.$emit('bv::hide::modal','confirmDelBranch');
    },
  },
  components: {
    appHeader: require('../Header'),
    branchItem: require('./BranchItem'),
    addBtn: require('../Common/ButtonPlus'),
    branchPhones: require('../BranchPhones'),
    back: require('../BranchPage/Back'),
    buttonPlus: require('../Common/ButtonPlus'),
  }
}
</script>
<style lang='scss' src='./style.scss' scoped></style> 
<style lang='scss'> 
.branch-td .branch__manager .form_input {
    height: 100%;
    border: none;
}
.close{
  &:focus{
    box-shadow: none;
    outline: none;
    border: none;
  }
}
.modal-header{
   background: #e9eff2;
   border: 5px solid #fff;
}
.modal-content {
    box-shadow: 2px 6px 10px rgba(28, 60, 77, 0.1);
    border: none;
    color: $lightgray;
    text-align: center;
}
.modal-backdrop {
    background-color: #d9dedead;
}
.modal-title {
    color: $cancelRed;
    width: 100%;
    display: flex;
    align-items: center;
}
.warning-icon {
    width: 42px;
    height: 42px;
    margin-right: 15px;
}
.warn-title {
    font-family: 'Roboto-Medium', sans-serif;
}
.modal-footer {
    .btn.btn-primary {
        background-color: $blue;
        border: none;
        border-radius: 100px;
        padding: 0.3em 1.8em;
        transition: all ease 300ms;
        &:active,
        &:focus,
        &:hover {
            box-shadow: none;
            outline: none;
            border: none;
            background-color: lighten($blue, 20);
        }
    }
}
.icon-warning-wrap{
    margin-right: 14px;
    width: 42px;
    height: 42px;
}
.warning-icon{
    width: 100%;
    height: 100%;
    stroke: #fff;
    fill: #fff;
}
.modal-title{
    color: $gray;
    width: 100%;
    display: flex;
    align-items: center;
}
.modal-footer{
    display: flex;
    .modal-buttons{
      width: 100%;
      display: flex;
    }
    .confirm-btn, .cancel-btn{
        border: none;
        width: 120px;
        border-radius: 110px;
        padding: 0.3em 1.8em;
        color: #fff;
        text-transform: uppercase;
        transition: all ease 300ms;
        &:hover, &:focus, &:active{
            box-shadow: none;
            outline: none;
            border: none;
        }
    }
    .confirm-btn{
        background-color: lighten($blue, 10);
        margin-right: auto;
        &:hover, &:focus, &:active{
            background-color: $blue;
        }
    }
    .cancel-btn{
        background-color: lighten($lightgray, 20);
        &:hover, &:focus, &:active{
            background-color: $lightgray;
        }
    }
}
</style>
