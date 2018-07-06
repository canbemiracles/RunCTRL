<template>
	<div class="income__wrap">
		<div class="income">
			<div class="d-flex justify-content-between">
				<div class="income__wrap-graf">
					<div class="income__wrap__title d-flex justify-content-between">
						<h2 class="income__title">Branch Income</h2>
						<div class="income__date-wrap">
							<ul class="income__date d-flex flex-row">
								<li class="income__date-item"
									:class="{'income__date-active': range === 'day'}"
									@click="range = 'day'">{{this.$moment().format('Do')}}</li>
								<li class="income__date-item"
									:class="{'income__date-active': range === 'month'}"
									@click="range = 'month'">{{this.$moment().format('MMMM')}}</li>
								<li class="income__date-item"
									:class="{'income__date-active': range === 'year'}"
									@click="range = 'year'">{{this.$moment().format('YYYY')}}</li>
							</ul>
						</div>
					</div>

					<div class="income__graf">
						<bar-chart :values="income" :average="average" :typeChart="brnachId ? 'line' : 'bar'">
						</bar-chart>
						<div v-if="!income.length" class="d-flex no__data align-items-center justify-content-center">
							<h6>Cashier reports didn’t received yet</h6>
						</div>
					</div>
				</div>
				<div class="income__block">
					<ul class="income__block-cont">
						<li class="income__block-item" v-for="(item, ind) in income" :key="ind">{{item.title}}</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
	export default {
		props:{
			brnachId:{
				type: String/Number,
			}
		},
		data: () => ({
			income: [
				{},
			],
			range: 'day',
			average: 0
		}),
		computed:{
			...mapGetters(['dateFormat'])
		},
		watch: {
			range: {
				handler(range) {
					let from = moment().startOf(range).toISOString(true);
					let to = moment().endOf(range).toISOString(true);
					this.getIncome(from, to);
				},
				immediate: true
			}
		},
		methods: {
			...mapActions(['getCompanyData']),
			getIncome(from, to) {
				let url = this.brnachId ? `api/v1/branches/${this.brnachId}/get_income` : 'api/v1/branches/get_income'
				this.$http.post(url, {
					date_start: from,
					date_end: to
				}).then(res => {
					let data = this.brnachId ? res.body : res.body.branches;
					this.income = data.map(branch => ({
						value: parseInt(branch.income),
						title: branch.address
					}))

					this.average = parseInt(res.body.avg)

				})
			},
			selectRange(range) {
				this.range
			}
		},
		created() {
			this.getCompanyData();
		},
		components: {
			barChart: require('../../Common/BarChart')
		}
	}
</script>
<style lang='scss' src='./style.scss' scoped></style>
