<template>
	<div class="reports__wrap">
		<div class="row">
			<h2 class="reports__title">Reports</h2>
		</div>
		<div class="row">
			<div class="reports__wrap-search">
				<svg class="reports__icn-svg"><use xlink:href="images/icons-sprite.svg#search"></use></svg>
				<input class="reports__search" type="text" placeholder="Search" v-model="searchQuery"  @keyup="search($event.target.value)">
			</div>
		</div>
		<div class="row d-flex justify-content-between">
			<div class="reports__filter-wrap">
				<ul class="reports__filter d-flex flex-row">
					<li @click="selectFilter('default')" class="reports__filter-li reports__filter-all" :class="{'reports__filter-active-all': filter === 'default'}">All Reports</li>
					<li @click="selectFilter('Commodity')" class="reports__filter-li reports__filter-comm" :class="{'reports__filter-active-comm': filter === 'Commodity'}">Commodity</li>
					<li @click="selectFilter('Problem')" class="reports__filter-li reports__filter-prob" :class="{'reports__filter-active-prob': filter === 'Problem'}">Problem</li>
					<li @click="selectFilter('EndOfShift')" class="reports__filter-li reports__filter-end" :class="{'reports__filter-active-end': filter === 'EndOfShift'}">End of Shift</li>
				</ul>
			</div>
			<div class="reports__functions-wrap" v-if="functionsBar">
				<btn-group
					@delete="deleteSelected"
					@markRead="markReadSelected(true)"
					@markUnread="markReadSelected(false)"
					@archive="archiveSelected" />
			</div>
			<button v-else class="btn-check-status" @click="checkBranchStatus">Check Branch Status</button>
		</div>
		<div class="row">
			<table>
				<tr v-for="(report, index) in reports" :key="index" class="report__item">
					<td class="report report__checked" @click="select(report)">
						<div class="report__status"
							:class="{'report__status-end': report.type === 'EndOfShift', 'report__status-com': report.type === 'Commodity', 'report__status-prob': report.type === 'Problem', 'report__status-prob': report.type === 'Problem'}"></div>

						<label class="container"> <!--active-manager-branches-->
							<input v-model="report.selected" class="checkbox" type="checkbox">
							<span class="checkmark"></span>
						</label>
					</td>
					<td class="report report__time">{{report.created | moment(timeFormated)}}</td>
					<td class="report report__name" :class="{'report__name__read': report.read}" @click="showSimplePopup(report)">{{report.title}} <span v-if="report.archive">archived?!</span></td>
					<td class="report report__user">{{report.branch_manager.first_name}} {{report.branch_manager.last_name}}</td>
					<td class="report report__address">{{report.geographical_area.street_address}}</td>
					<td class="report report__cntxbtn">
						<cntx-btn
							@delete="deleteOne(report, index)"
							@markRead="markReadOne(report, true)"
							@markUnread="markReadOne(report, false)"
							@archive="archiveOne(report, index)" />
					</td>
				</tr>
			</table>
			<div class="text-center no-reports" v-if="!reports.length">No reports</div>
			<div class="reports__more" v-else>
				<router-link to="/reports-page" tag="button">View all</router-link>
			</div>
		</div>

		<popup :report="currentReport" :timeFormat="timeFormat" :show.sync="showPopup" />

	</div>
</template>
<script>
	import 'images/sprites/search.svg'
	import 'images/sprites/icn-ellipsis.svg'
	import 'images/sprites/file-word.svg'
	import 'images/sprites/file-excel.svg'
	import 'images/sprites/file-o.svg'
import { mapGetters, mapActions } from 'vuex';
export default {
	props:{
		functionsBar: {
			type: Boolean,
			default: true
		},
		branchID: String/Number
	},
	data: () => ({
		filter: 'default',
		reports: [],
		searchQuery: '',
		currentReport: null,
		showPopup: false,
		commodity_reports: [],
		problem_reports: [],
		end_of_shifts_reports: []

	}),
	watch: {
		filter(){
			this.searchQuery = ''
			this.reports = [];
			if(this.branchID){
				this.getBranchReportsByType(this.type)
			}else{
				this.getReportsByType(this.type);	
			}
		}
	},
	computed: {
		...mapGetters(['timeFormat']),
		selected() {
			return this.reports.filter(item => item.selected)
		},
		type() {
			switch (this.filter) {
					case 'Commodity': return 'commodity'
					case 'Problem': return 'problem'
					case 'EndOfShift': return 'eos'
					case 'default': return ''
				}
		},
		timeFormated(){
			return this.timeFormat ? this.timeFormat : 'hh:mmA';
		}
	},
	methods: {
		...mapActions(['getCompanyData']),
		getReports() {
			if(this.branchID){
				this.$http.get(`api/v1/branches/${this.branchID}/get_live_data`).then((res)=>{
					this.$emit('getBranchLiveData', res.body);
					this.commodity_reports = res.body.commodity_reports.map(item =>{ item.branch_id = this.branchID; return item});
					this.problem_reports = res.body.problem_reports.map(item =>{ item.branch_id = this.branchID; return item});
					this.end_of_shifts_reports = res.body.end_of_shifts_reports.map(item =>{ item.branch_id = this.branchID; return item});
					this.reports = [...this.commodity_reports, ...this.problem_reports, ...this.end_of_shifts_reports];
				});
				
			}else{
				this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/reports/`).then(
					res => this.reports = (res.body.items)
				);
			}
		},

		getReportsByType(type) {
			if (type) {
				this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/reports?type=${type}`).then(
					res => this.reports = this.reports.concat(res.body.items)
				)
			} else {
				this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/reports?`).then(
					res => this.reports = this.reports.concat(res.body.items)
				)
			}
		},
		getBranchReportsByType(type) {
			switch(type){
				case 'commodity':
					this.reports = this.commodity_reports;
					break;
				case 'problem':
					this.reports = this.problem_reports;
					break;
				case 'eos':	
					this.reports = this.end_of_shifts_reports;
					break;
				default:
					this.reports = [...this.commodity_reports, ...this.problem_reports, ...this.end_of_shifts_reports];
					break;	
			}
		},	
		selectFilter(filter) {
			this.filter = filter
		},

		select(item) {
			this.$set(item, 'selected', !item.selected)
		},

		getType(type) {
			switch (type) {
				case 'Problem': return 'problem_reports'
				case 'Commodity': return 'commodity_reports'
				case 'EndOfShift': return 'end_of_shift_reports'
			}
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
				this.$http.patch(`api/v1/branches/${item.branch_id}/branch_shifts/${item.branch_shift.id}/reports/${type}/${item.id}`, {read: read})
			} else {
				this.$http.patch(`api/v1/branches/${item.branch_id}/stations/${item.branch_station_id}/reports/${type}/${item.id}`, {read: read})
			}

			item.read = read
		},

		archive(item) {
			let type = this.getType(item.type)
			if (item.type === 'EndOfShift') {
				this.$http.patch(`api/v1/branches/${item.branch_id}/branch_shifts/${item.branch_shift.id}/reports/${type}/${item.id}`, {archive: true})
			} else {
				this.$http.patch(`api/v1/branches/${item.branch_id}/stations/${item.branch_station_id}/reports/${type}/${item.id}`, {archive: true})
			}
		} ,

		delete(item) {
			let type = this.getType(item.type)
			if (item.type === 'EndOfShift') {
				this.$http.delete(`api/v1/branches/${item.branch_id}/branch_shifts/${item.branch_shift.id}/reports/${type}/${item.id}`)
			} else {
				this.$http.delete(`api/v1/branches/${item.branch_id}/stations/${item.branch_station_id}/reports/${type}/${item.id}`)
			}
		},

		getType(type) {
			switch (type) {
				case 'Problem': return 'problem_reports'
				case 'Commodity': return 'commodity_reports'
				case 'EndOfShift': return 'end_of_shift_reports'
				case 'default': return 'report_alls'
			}
		},

		search(query) {
			if (query === '') {
				if(this.branchID){
					this.getBranchReportsByType(this.type)
				}else{
					this.getReportsByType(this.type);	
				}
				return
			} else {
				let optionsSearch = {
					term: query,
					type: this.getType(this.filter).slice(0, -1)
				}
				if(this.branchID){
					// optionsSearch.branches = [this.branchID, ]
				}
				this.$http.post(`api/v1/companies/${this.$auth.user().company_id}/reports/search`, optionsSearch).then(responce => {
					this.reports = responce.body.map(item => item.data)
				})
			}
		},

		checkBranchStatus(){
			this.$emit('checkBranchStatus');
		},

		showSimplePopup(report) {
			if (report.type !== 'EndOfShift') {
				this.currentReport = report;
				this.showPopup = true;
			}
		}
	},
	created() {
		this.getCompanyData();
		this.getReports();
	},
	components: {
		cntxBtn: require('../../Common/CntxBtn'),
		btnGroup: require('../../Common/ButtonGroup'),
		popup: require('../../ReportsPage/SimplePopup'),
	}
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
