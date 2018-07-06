<template>
	<section class="slide-section-container d-flex">
		<div class="slide-content d-flex flex-column" >
			<div class="row">
				<h3 class="title-header col-md-12">Assign to Employee</h3>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="wrap">
						<div class="top">
							<div class="employee" v-for="(item, i) in assignments" :key="i">
								<div class="picture-placeholder" :style="{backgroundImage: `url(${item.selectedEmployee.imgSrc})`}"></div>
        						<span>{{ item.selectedEmployee.employee_name }}</span>
								<close-btn class="close-btn" @click="removeEmployee(item.selectedEmployee)"></close-btn> 
							</div>
						</div>
						<div class="bottom">
							<div class="employee-icon">
                            	<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="18" viewBox="0 0 16 19"><defs><path class="back-user-icon" fill="#fff" id="ks7ha" d="M65.7 1346.83a9.2 9.2 0 0 1-3.2-2.96c.16-1.27 1.1-2.25 2.81-2.93a12.92 12.92 0 0 1 4.69-1.01c1.4 0 2.97.33 4.69 1.01 1.72.68 2.65 1.66 2.81 2.93A9.2 9.2 0 0 1 70 1348a9.1 9.1 0 0 1-4.3-1.17zm6.94-15.87c.7.71 1.05 1.62 1.05 2.72s-.35 2-1.05 2.72a3.54 3.54 0 0 1-2.64 1.07 3.54 3.54 0 0 1-2.64-1.07 3.73 3.73 0 0 1-1.05-2.72c0-1.1.35-2 1.05-2.72a3.54 3.54 0 0 1 2.64-1.08c1.05 0 1.93.36 2.64 1.08z"/></defs><g><g transform="translate(-62 -1329)"><use fill="#fff" xlink:href="#ks7ha"/></g></g></svg>
                        	</div>
							<multi-autocomplete :suggestions="employeesList" v-if="employeesList.length" 
								fieldName="employee_name"
								id="employee"
								ref="multiAutocompEmployee"
								:iconSearch="false"
								placeholder="Add Employee"
								labelField="label"
								@suggestionClick="setEmployee"
							></multi-autocomplete>
							<div class="no-employee" v-else>
								No employees on the current shift
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<recommend>
			<div slot="recommend">
				Analytics anly available on paid plans. Please upgrade your account to see.
			</div>
	  	</recommend>
	</section>
</template>

<script>
import 'images/sprites/combined-shape.svg'
import { mapGetters, mapActions } from 'vuex'

export default {
	props:{
		reset: {
			type: Boolean,
			default: false,
		}
	},
	data() {
		return {
            assignments: [],
            selectOptions: [],
			employeesList: [],
			listTest:[
				{	
					id: 1,
					employee_name: 'Kevin Smith', 
					imgSrc: require('images/user_default.jpg'),  
					label: 'Front Manager, 508 Garnet Crest Suite',
					selected: false
				},
				{	
					id: 2,
					employee_name: 'John Smith', 
					imgSrc: require('images/user_default.jpg'), 
					label: 'Back Manager, 508 Garnet Crest Suite',
					selected: false
				}, 	 
			]
		}
	},

	watch: {
		assignments(items) {
			if(items.length){
				this.$emit('input', items)	
			}
		},
		reset(value){
			if(value){
				this.assignments = [];
			}
		}
	},

	computed: {
		...mapGetters(['branches', 'roles']),
	},

	methods: {
        ...mapActions(['getCompanyEmployeesCurrentShift']),
		setEmployee(status, empl) {
			if(status){
				this.assignments.push({ 
					selectedEmployee : empl,
					branches: 550,
					selectedRole: 488,
				});
			}else{
				this.assignments = this.assignments.filter(({selectedEmployee})=>(selectedEmployee.id != empl.id));
			}
		},
		removeEmployee(empl){
			this.$refs.multiAutocompEmployee.suggestionClick([false, empl]);
		}
    },    
	created() {
		this.getCompanyEmployeesCurrentShift().then((res)=>{
			if(res.body.length){
				this.employeesList = res.body.map(item=>{
					item.employee_name = `${item.employee.first_name} ${item.employee.last_name}`;
					if(item.employee.branches && item.employee.branches.geographical_address){
						item.label =   `${item.role.role}, ${item.employee.branches.geographical_address.street_address}`;
					}
					return item;
				})
			}else{
				this.employeesList=[];
			}
        })
    },
    mounted(){
        let unwatchRoles = this.$watch('roles', (value)=>{
            if(value.length){
                this.getBranchRoles();
            }
        });
    },
	components:{
		recommend: require('../../../SidebarRecommend'),
        autocomplete: require('../../../Common/Autocomplete'),
        multiAutocomplete: require('../../../Common/AutocompleteMultiSelect'),
		buttonPlus: require('../../../Common/ButtonPlus'),
		closeBtn: require('../../../Common/CloseBtn')
	}
}
</script>

<style lang='scss' scoped>

.title-header {
	margin-bottom: 25px;

	text-align: center;

	font-size: 22px;
	color: #033040;
}
.branch-icon{
	width: 20px;
    height: 20px;
    border: 3px solid $blue;
    border-radius: 50%;
    flex-shrink: 0;
	margin-right: 10px;
	align-self: center;
}
.role-icon{
	width: 20px;
	height: 20px;
	background-color: $cancelRed;
	border-radius: 50%;
	align-self: center; 
}
.wrap {
	background: #fff;
	position: relative;
	box-shadow: 0 0 10px #ccc;

	.close-btn{
		margin-left: auto;
	}

	.employee-icon {
		background-color: #1dcdee;
		border-radius: 50%;
		margin-right: 7px;
		display: flex;
		align-items: center;
		justify-content: center;
		width: 20px;
		height: 20px;
	}
	.employee{
		display: flex;
		align-items: center;
		padding: 10px 0;
		.picture-placeholder{
			width: 40px;
			height: 40px;
			border-radius: 50%;
			background-size: cover;
			background-position: center center;
			margin-right: 1em;
			border: 1px solid #e6ecee;
		}
	}
	.top {
		display: flex;
		flex-direction: column;
		border-bottom: 1px solid #e6ecee;
		padding: 0 30px;
	}
	.no-employee{
		display: flex;
		align-items: center;
	}
	.bottom {
		height: 70px;
		display: flex;
		align-items: center;
		padding: 0 30px;
	}
}

.add-role {
	background-color: #fbfcfc;
	height: 70px;
	padding: 0 30px;
	position: relative;
	cursor: pointer;
	box-shadow: 0 0 10px #ccc;

	&:hover .add-btn {
		color: $white;
		background-color: darken(#e9eff2, 10);
	}

	line-height: 70px;
	color: #97a7af;

	.add-btn {
		display: inline-flex;
		width: 22px;
		height: 22px;
		margin-right: 7px;
		font-size: 16px;
	}
}
.autocomplete-row{
	/deep/ &.autocomplete-wrap{
		width: 100%;
		height: 100%;
		.form_input{
			width: 100%;
			height: 100%;
			border: none;
			outline: none;
			font-size: 16px;
			border-bottom: 1px solid transparent;
			&:focus {
      			border-color: $blue;
			}
		}
		.search_icon {
			right: 30px;
		}
	}
	/deep/ .autocomplete-drop{
		max-height: 200px;
	}
}

.input-row.disabled{
	position: relative;
	&:after{
		content: '';
		position: absolute;
		top: 0;
		left: 0;
		height: 100%;
		width: 100%;
		background-color: rgba(#ccc, .3);
		z-index: 20;
	}
	.icon{
		fill: $lightgray;
	}
	.role-icon{
		background-color: $lightgray;
	}
}
</style>
