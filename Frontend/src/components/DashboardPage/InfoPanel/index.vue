<template>
	<div class="temp-wrap__main">
		<div class="info-panel__wrap d-flex justify-content-around">
			<div class="item-info logged-in">
				<h4 class="item-info-title"><svg class="icon icn-panel group-icn-panel"><use xlink:href="images/icons-sprite.svg#group"></use></svg> Employees Logged In</h4>
				<div class="info-panel-num" >{{ employees }}<div class="info-desc__logged-in"><span>{{ notStationed }} roles</span> not being stationed</div></div>
			</div>
			<div class="item-info new-reports">
				<h4 class="item-info-title"><svg class="icon icn-panel reports-icn-panel"><use xlink:href="images/icons-sprite.svg#reports"></use></svg> New Reports Today</h4>
				<div class="info-panel-num">{{getReports()}}</div>
				<ul class="info-desc__new-reports d-flex justify-content-between">
					<li><svg class="icon boxes-icn-panel"><use xlink:href="images/icons-sprite.svg#boxes"></use></svg> <span>{{getComodityReports()}}</span></li>
					<li><span class="currency-icon">{{ currencyIcon }}</span><span>{{getCashierReports()}}</span></li>
					<li><svg class="icon warning-icn-panel"><use xlink:href="images/icons-sprite.svg#warning"></use></svg> <span>{{getProblemsReports()}}</span></li>
					<li><svg class="icon end-icn-panel"><use xlink:href="images/icons-sprite.svg#end"></use></svg> <span>{{getEndOfShiftsReports()}}</span></li>
				</ul>
			</div>
			<div class="item-info today-finance">
				<h4 class="item-info-title"><svg class="icon icn-panel calculator-icn-panel"><use xlink:href="images/icons-sprite.svg#calculator"></use></svg> Today’s Employees Finance</h4>
				<div class="wrap-finance d-flex justify-content-between">
					<div class="info-panel-num">{{info.total_work_time ? Math.round(info.total_work_time) : 0}} h. <span>Worked Today</span></div>
					<div class="info-panel-num">{{ (info.total_employee_budget && info.total_work_time ? Math.round(info.total_employee_budget/info.total_work_time) : 0 ) | currency(0, 0)}} <span>Avg. {{ currencyIcon }}/h.</span></div>
					<div class="info-panel-num">{{ (info.total_employee_budget ? Math.round(info.total_employee_budget) : 0) | currency(0, 0)}} <span>Total Spent</span></div>
					<div class="info-panel-num"><b >{{info.ration ? Math.round(info.ration) : 0}} %</b> <span>Ratio</span></div>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
	import 'images/sprites/info-panel/group.svg'
	import 'images/sprites/info-panel/reports.svg'
	import 'images/sprites/info-panel/calculator.svg'
	import 'images/sprites/info-panel/boxes.svg'
	import 'images/sprites/info-panel/warning.svg'
	import 'images/sprites/info-panel/end.svg'
import { mapGetters, mapActions } from 'vuex';
import company from '../../../store/modules/company';

	export default {
		props: {
			info: {
				type: Object
			},
			branchID: String
		},

		data: () => ({
			currency: ''
		}),
		created(){
			this.getCompanyData().then(()=>{
				this.currency = this.company.currency.currency;
			});
		},
		computed: {
			...mapGetters(['company']),
			employees(){
				let result = 0;
				if (this.info.branches_list) {
					if(!this.branchID){
						this.info.branches_list.forEach(branch => result += branch.employees.length)
					}else{
						result = this.info.employees.length;
					}
				}
				return result
			},
			notStationed(){
				let result = 0;
				if (this.info.branches_list || this.branchID) {
					if(!this.branchID){
						this.info.branches_list.forEach(branch => result += branch.roles_count);
					}else{
						result = this.info.roles_count;
					}
				}
				return result - this.employees;
			},
			currencyIcon(){
				if(this.currency){
					let formatter = new Intl.NumberFormat(this.$store.getters.language, {
						style: "currency",
						currency : this.currency,
						currencyDisplay: 'symbol',
						minimumFractionDigits: 0,
						maximumFractionDigits: 0
					});
					let currency_value = formatter.format(0);
					let symbol = currency_value.replace(/0/, '');
					return symbol;
				}
        	}
		},

		methods: {
			...mapActions(['getCompanyData']),
			getComodityReports() {
				let result = 0;
				if (this.info.branches_list || this.branchID) {
					if(!this.branchID){
						this.info.branches_list.forEach(branch => result += branch.commodity_reports.length);
					}else{
						result = this.info.commodity_reports.length;
					}	
				}
				return result
			},

			getCashierReports() {
				let result = 0;
				if (this.info.branches_list || this.branchID) {
					if(!this.branchID){
						this.info.branches_list.forEach(branch => result += branch.cashier_reports.length)
					}else{
						result = this.info.cashier_reports.length;
					}	
				}
				return result
			},

			getProblemsReports() {
				let result = 0;
				if (this.info.branches_list || this.branchID) {
					if(!this.branchID){
						this.info.branches_list.forEach(branch => result += branch.problem_reports.length)
					}else{
						result = this.info.problem_reports.length;
					}
				}
				return result
			},

			getEndOfShiftsReports() {
				let result = 0;
				if (this.info.branches_list || this.branchID) {
					if(!this.branchID){
						this.info.branches_list.forEach(branch => result += branch.end_of_shifts_reports.length)
					}else{
						result = this.info.end_of_shifts_reports.length;
					}
				}
				return result
			},

			getReports() {
				return  this.getComodityReports() + this.getCashierReports() + this.getProblemsReports() + this.getEndOfShiftsReports()
				
			}
			
		}
	}
</script>
<style lang='scss' src='./style.scss' scoped></style>