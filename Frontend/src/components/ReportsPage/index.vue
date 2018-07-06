<template>
<div class="container-fluid p-0 d-flex flex-column container-one-page">
  <app-header :reports="16"></app-header>
  <div class="page-container d-flex">
    <div class="reports__wrap">
      <div class="row">
        <h2 class="reports__title"
            :class="{'reports__title__active': filterAT === 'all'}"
            @click="filterAT = 'all'">Reports</h2>
        <h2 class="reports__title"
            :class="{'reports__title__active': filterAT === 'archived'}"
            @click="filterAT = 'archived'">Archived</h2>
        <h2 class="reports__title"
            :class="{'reports__title__active': filterAT === 'trash'}"
            @click="filterAT = 'trash'">Trash</h2>
      </div>
      <div class="row">
        <div class="reports__wrap-search">
          <svg class="reports__icn-svg"><use xlink:href="images/icons-sprite.svg#search"></use></svg>
          <input class="reports__search"
                 type="text"
                 placeholder="Search"
                 v-model="searchQuery"
                 @keyup="search($event.target.value)">
        </div>
      </div>
      <div class="row d-flex justify-content-between">
        <div class="reports__filter-wrap">
          <ul class="reports__filter d-flex flex-row">
            <li @click="selectFilter('default')"
                class="reports__filter-li reports__filter-all"
                :class="{'reports__filter-active-all': filter === 'default'}">All Reports</li>
            <li @click="selectFilter('Commodity')"
                class="reports__filter-li reports__filter-comm"
                :class="{'reports__filter-active-comm': filter === 'Commodity'}">Commodity</li>
            <li @click="selectFilter('Problem')"
                class="reports__filter-li reports__filter-prob"
                :class="{'reports__filter-active-prob': filter === 'Problem'}">Problem</li>
            <li @click="selectFilter('EndOfShift')"
                class="reports__filter-li reports__filter-end"
                :class="{'reports__filter-active-end': filter === 'EndOfShift'}">End of Shift</li>
          </ul>
        </div>
        <div class="reports__functions-wrap">
          <btn-group @delete="deleteSelected"
                     @markRead="markReadSelected(true)"
                     @markUnread="markReadSelected(false)"
                     @archive="archiveSelected" />
        </div>
      </div>
      <div class="row">
        <table class="table">
          <thead>
            <th>
              <p>
                <input type="checkbox"
                       v-model="selectAll">
              </p>
            </th>
            <th>
              <p class="th__filter">Time <svg class="icn__filter"><use xlink:href="images/icons-sprite.svg#filter"></use></svg> </p>
            </th>
            <th>
              <p class="th__filter">Title</p>
            </th>
            <th>
              <t-filter :items="managers"
                        @applyFilter="applyFilter" name="managersFilter">Managers</t-filter>
            </th>
            <th>
              <t-filter :items="branches" @applyFilter="applyFilter" name="branchesFilter">Branch</t-filter>
            </th>
            <th>
              <t-filter :items="regions" @applyFilter="applyFilter" name="regionsFilter">Region</t-filter>
            </th>
          </thead>
          <tbody>
            <tr class="report__item"
                v-for="(report, index) in reports"
                :key="index">
              <td class="report report__checked" @click="select(report)">
                <div class="report__status"
                     :class="{'report__status-end': report.type === 'EndOfShift', 'report__status-com': report.type === 'Commodity', 'report__status-prob': report.type === 'Problem', 'report__status-prob': report.type === 'Problem'}"></div>
                <label class="container-check">
                  <input class="checkbox-check"
                         type="checkbox"
                         v-model="report.selected">
                  <span class="checkmark-check"></span>
                </label>
              </td>
              <td class="report report__time">{{report.created | moment(timeFormated)}}</td>
              <td class="report report__name"
                  :class="{'report__name__read': report.read}"
                  @click="showSimplePopup(report)">{{report.title}}</td>
              <td class="report report__maneger">{{report.branch_manager.first_name}} {{report.branch_manager.last_name}}</td>
              <td class="report report__branch">{{report.geographical_area ? report.geographical_area.street_address : ''}}</td>
              <td class="report report__region">{{report.geographical_area ? report.geographical_area.region : ''}}</td>
              <td class="report report__cntxbtn">
                <cntx-btn @delete="deleteOne(report, index)"
                          @markRead="markReadOne(report, true)"
                          @markUnread="markReadOne(report, false)"
                          @archive="archiveOne(report, index)" />
              </td>
            </tr>
          </tbody>
        </table>
        <h5 class="text-center no-reports"
            v-if="!reports.length">No reports yet!</h5>

        <div class="reports__more"
             @click="page += 1"><button>Load more</button></div>
      </div>
    </div>
  </div>

  <popup :report="currentReport" :dateFormat="dateFormat" :timeFormat="timeFormat" :show.sync="showPopup" />

</div>
</template>
<script>
import 'images/sprites/filter.svg'
import 'images/sprites/search.svg'
import { mapGetters, mapActions} from 'vuex';
export default {
  data: () => ({
    filterAT: 'all',
    filter: 'default',
    page: 1,
    searchQuery: '',
    selectAll: false,
    reports: [],
    managers: [],
    branches: [],
    regions: [],
    managersFilter: [],
    branchesFilter: [],
    regionsFilter: [],
    showPopup: false,
    currentReport: {}

  }),
  watch: {
    filter: {
      handler() {
        this.page = 1
        this.searchQuery = ''
        this.reports = []
        this.getReportsByType()
      },
      immediate: true
    },
    selectAll() {
      this.reports.forEach(item => this.select(item, true))
    },
    page() {
      this.getReportsByType()
    },
    filterAT() {
      this.page = 1
      this.searchQuery = ''
      this.reports = []
      this.getReportsByType()
    }
  },
  computed: {
    ...mapGetters(['timeFormat', 'dateFormat']),
    selected() {
      return this.reports.filter(item => item.selected)
    },
    type() {
      switch (this.filter) {
        case 'Commodity':
          return 'commodity'
        case 'Problem':
          return 'problem'
        case 'EndOfShift':
          return 'eos'
        case 'default':
          return ''
      }
    },
    timeFormated(){
			return this.timeFormat ? this.timeFormat : 'hh:mmA';
		}

  },
  created() {
    this.getCompanyData();
    this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/users/branch_managers/`)
      .then(res => this.managers = res.body)
    this.$http.get(`api/v1/branches/free_data`)
      .then(res => this.branches = res.body)
    this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/regions`)
      .then(res => this.regions = res.body.map(item => ({id: item})))
  },
  methods: {
    ...mapActions(['getCompanyData']),
    getReportsByType() {
      let query = `api/v1/companies/${this.$auth.user().company_id}/reports${(this.filterAT === 'archived') ? '/archived?' : '?'}`
      query += `${this.type ? 'type=' + this.type : ''}&all=true&page=${this.page}&`
      query += `${this.managersFilter.length ? this.arrayToQuery(this.managersFilter, 'managers') : ''}`
      query += `${this.branchesFilter.length ? this.arrayToQuery(this.branchesFilter, 'branches') : ''}`
      query += `${this.regionsFilter.length ? this.arrayToQuery(this.regionsFilter, 'regions') : ''}`
      console.log(query)
      this.$http.get(query)
        .then(
          res => this.reports = this.reports.concat(res.body.items)
        )
    },

    selectFilter(filter) {
      this.filter = filter
    },

    select(item) {
      if (this.selectAll) {
        this.$set(item, 'selected', !this.selectAll)
      }
      this.$set(item, 'selected', !item.selected)
    },

    getType(type) {
      switch (type) {
        case 'Problem':
          return 'problem_reports'
        case 'Commodity':
          return 'commodity_reports'
        case 'EndOfShift':
          return 'end_of_shift_reports'
        case 'default':
          return 'report_alls'
      }
    },

    search(query) {
      if (query === '') {
        this.getReportsByType()
        return
      } else {
        this.$http.post(`api/v1/companies/${this.$auth.user().company_id}/reports/search`, {
            term: query,
            type: this.getType(this.filter)
              .slice(0, -1)
          })
          .then(
            responce => {
              this.reports = responce.body.map(item => item.data)
            })
      }
    },

    arrayToQuery(array, name) {
      let query = ''
      array.forEach(item => query += `${name}[]=${item}&`)
      return query
    },

    applyFilter(items, name) {
      this[name] = []
      this[name] = items.map(({
        id
      }) => id)
      this.reports = []
      this.page = 1
      this.getReportsByType()
    },

    markReadSelected(read) {
      this.selected.forEach(item => this.markReadOne(item, read))
    },

    deleteSelected() {
      this.selected.forEach(item => this.delete(item))
      this.reports = this.reports.filter(item => this.selected.indexOf(item) < 0, this.selected)
    },

    archiveSelected() {
      this.selected.forEach(item => this.archive(item))
      this.reports = this.reports.filter(item => this.selected.indexOf(item) < 0, this.selected)
    },

    deleteOne(item, index) {
      this.delete(item)
      this.reports.splice(index, 1)
    },

    archiveOne(item, index) {
      this.archive(item)
      this.reports.splice(index, 1)
    },

    markReadOne(item, read) {
      let type = this.getType(item.type)
      if (item.type === 'EndOfShift') {
        this.$http.patch(`api/v1/branches/${item.branch_id}/branch_shifts/${item.branch_shift.id}/reports/${type}/${item.id}`, {
          read: read
        })
      } else {
        this.$http.patch(`api/v1/branches/${item.branch_id}/stations/${item.branch_station_id}/reports/${type}/${item.id}`, {
          read: read
        })
      }

      item.read = read
    },

    archive(item) {
      let type = this.getType(item.type)
      if (item.type === 'EndOfShift') {
        this.$http.patch(`api/v1/branches/${item.branch_id}/branch_shifts/${item.branch_shift.id}/reports/${type}/${item.id}`, {
          archive: true
        })
      } else {
        this.$http.patch(`api/v1/branches/${item.branch_id}/stations/${item.branch_station_id}/reports/${type}/${item.id}`, {
          archive: true
        })
      }
    },

    delete(item) {
      let type = this.getType(item.type)
      if (item.type === 'EndOfShift') {
        this.$http.delete(`api/v1/branches/${item.branch_id}/branch_shifts/${item.branch_shift.id}/reports/${type}/${item.id}`)
      } else {
        this.$http.delete(`api/v1/branches/${item.branch_id}/stations/${item.branch_station_id}/reports/${type}/${item.id}`)
      }
    },
    showSimplePopup(report) {
      if (report.type !== 'EndOfShift') {
        this.currentReport = report;
        this.showPopup = true;
      }
    }
  },

  components: {
    appHeader: require('../Header'),
    btnGroup: require('../Common/ButtonGroup'),
    cntxBtn: require('../Common/CntxBtn'),
    tFilter: require('./Filter'),
    popup: require('./SimplePopup'),
  }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
