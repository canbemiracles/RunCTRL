<template>
<div class="container-fluid d-flex flex-column p-0 page-container">
  <app-header></app-header>

  <create-employee :show.sync="showEmployeeDialog"
                   @updateList="updateList" />

  <main class="employees-list page-container">
    <header class="d-flex justify-content-start align-items-center">
      <h2 class="heading-2">Employees</h2>
      <div class="ml-auto d-flex align-items-center">
        <b-button-group class="button-group">
          <b-button>Assign to Branch</b-button>
          <span class="btn-separator"></span>
          <b-button @click="showExportDialog = !showExportDialog">Export</b-button>
          <div v-if="showExportDialog"
               class="export-dialog row">
            <div class="col-4">
              <svg class="icon-close">
                  <use xlink:href="images/icons-sprite.svg#close-usage"></use>
                </svg>
              <p>Word</p>
            </div>
            <div class="col-4">
              <svg class="icon-close">
                  <use xlink:href="images/icons-sprite.svg#close-usage"></use>
                </svg>
              <p>Exel</p>
            </div>
            <div class="col-4">
              <svg class="icon-close">
                  <use xlink:href="images/icons-sprite.svg#close-usage"></use>
                </svg>
              <p>CSV</p>
            </div>
          </div>
          <span class="btn-separator"></span>
          <b-button>Archive</b-button>
          <span class="btn-separator"></span>
          <b-button @click="deleteSelected">Delete</b-button>
          <span class="btn-separator"></span>
          <b-button>
            <svg class="icon-close">
                <use xlink:href="images/icons-sprite.svg#close-usage"></use>
              </svg>
          </b-button>
        </b-button-group>
        <button-plus class="btn-circle"
                     @click="showEmployeeDialog = true"></button-plus>
      </div>
    </header>
    <div class="employee__wrap-search">
      <svg class="employee__icn-svg"><use xlink:href="images/icons-sprite.svg#search"></use></svg>
      <input class="employee__search"
             type="text"
             placeholder="Search"
             v-model="query"
             @keyup="search(query)">
    </div>

    <table class="employees-list-table">
      <thead>
        <th>
          <!-- <input type="checkbox" v-model="selectAll"> -->
          <label class="checkbox-wrapper">
            <input v-model="selectAll"
                   class="checkbox"
                   type="checkbox">
            <span class="checkmark"></span>
          </label>
        </th>
        <th>Emploee</th>
        <th>Branch</th>
        <th>Region</th>
      </thead>
      <transition-group tag="tbody" name="slide">
      <tr v-for="(item, index) in items"
          :key="index"
          :class="{ selected: item.selected }">
        <td>
          <div class="d-flex employee-wrapper">
            <!-- <input
                class="employee-checkbox"
                type="checkbox"
                v-model="item.selected"> -->
            <label class="checkbox-wrapper employee-checkbox">
              <input v-model="item.selected"
                     class="checkbox"
                     type="checkbox">
              <span class="checkmark"></span>
            </label>
            <div class="avatar"
                 v-if="item.avatar"
                 :style="{ backgroundImage: `url(${$http.options.root}/${item.avatar.path}`}" />
            <div class="avatar"
                 v-else
                 :style="{ backgroundImage: 'url(' + require('images/user_default.jpg')+')'}" />

            <div class="over"
                 @click="select(item)" />
            <div class="online-indicator"></div>
          </div>
        </td>
        <td @click="goToEmployee(item.id)">{{item.first_name}} {{item.last_name}}</td>
        <td @click="goToEmployee(item.id)"><span v-if="item.geographical_area">{{item.geographical_area.street_address}}</span></td>
        <td @click="goToEmployee(item.id)">{{ item.geographical_area && item.geographical_area.country ? item.geographical_area.country.name : ''}}</td>
      </tr>
      </transition-group>
    </table>
    <div class="load"></div>
  </main>
  <app-footer></app-footer>
</div>
</template>

<script>
export default {
  name: 'EmployeesList',
  data: () => ({
    selectAll: false,
    showExportDialog: false,
    items: [],
    currentPage: 1,
    showEmployeeDialog: false,
    employee: null,
    query: '',
    block: false,
  }),
  created() {
    this.loadEployees(this.currentPage)
  },
  mounted(){
    $(window).on('scroll', this.infiniteScroll);
  },
  computed: {
    selected() {
      return this.items.filter(item => item.selected)
    }
  },
  methods: {
    goToEmployee(id) {
      this.$router.push({
        path: `employee-profile-page/${id}`
      })
    },
    select(item) {
      this.$set(item, 'selected', !item.selected)
    },
    search(query, clean) {
      if (query === '') {
        this.items = []
        this.currentPage = 1
        this.loadEployees(this.currentPage, clean)
        return
      } else {
        this.$http.post(`api/v1/companies/${this.$auth.user().company_id}/employees/search`, {
            term: query
          })
          .then(
            responce => {
              this.items = responce.body.map(item => item.data)
            })
      }
    },
    updateList() {
      console.log('update!!! q:' + this.query)
      this.items = []
      this.search(this.query, true)
    },
    deleteSelected() {
      this.selected.forEach(item => {
        this.$http.delete(`api/v1/companies/${this.$auth.user().company_id}/employees/${item.id}`)
          .then(
            responce => {},
            responce => {
              console.error(responce.statusText, responce.status)
            }
          )
        this.items = this.items.filter(item => this.selected.indexOf(item) < 0, this.selected)
      })
    },
    loadEployees(page, clean) {
      this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/employees/?page=${page}`)
        .then(
          responce => {
            console.log('items: ' + this.items.length)
            console.log(responce.body.total_count);
            if (!clean) {
              this.items = this.items.concat(responce.body.items)
            } else {
              this.items = responce.body.items
            }
            console.log('new items: ' + this.items.length)
            $(".load").fadeOut(500);
            this.block = false;
            if(responce.body.total_count == this.items.length){
              $(window).off('scroll', this.infiniteScroll);
            }
            // this.employee = this.items[2]

          },
          responce => {
            console.error(responce.statusText, responce.status)
          }
        )
    },
    infiniteScroll(){
      if($(window).height() + $(window).scrollTop() >= $(document).height() && !this.block) {
        this.block = true;
        $(".load").fadeIn(500, ()=> {
          this.loadEployees(++this.currentPage)
        })
      }  
    }
  },
  watch: {
    selectAll(selected) {
      this.items.forEach((item, index, items) => {
        items[index].selected = selected
      })
    }
  },
  beforeDestory(){
    $(window).off('scroll', this.infiniteScroll);
  },
  components: {
    appHeader: require('../Header'),
    appFooter: require('../Footer'),
    buttonPlus: require('../Common/ButtonPlus'),
    createEmployee: require('./CreateEmployee')
  }
}
</script>

<style lang="scss" src="./style.scss" scoped></style>
