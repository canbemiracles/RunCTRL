<template>
	<tr class="branch__item--list status-on-wrap--list" :class="{'status-off-wrap--list': branch.tasks.problems.length > 0, 'status-s-wrap--list': leftTime === 'status-closed'}">
		<td class="branch__item-cell branch__item-status-cell">
			<div class="status--list"></div>
		</td>
		<td class="branch__item-cell branch__item-address-cell">
			<h3 class="user-address--list" v-if="branch.geographical_area">{{branch.geographical_area.street_address}}</h3>
		</td>
		<td class="branch__item-cell branch__item-region-cell">
			<h4 class="user-region--list" v-if="branch.geographical_area">{{branch.geographical_area.region}}</h4>
		</td>
		<td class="branch__item-cell branch__item-branch-cell d-flex align-items-stretch">
			<div class="user-photo--list" v-if="branch.branch_manager">
				<img class="branch-photo-list" v-if="branch.branch_manager && branch.branch_manager.avatar" :src="$http.options.root  +'/'+ branch.branch_manager.avatar.path" alt="userpic">
        		<img class="branch-photo-list" v-else src="images/user_default.jpg" alt="userpic">
			</div>
			<div class="user-name--list" v-if="branch.branch_manager">{{branch.branch_manager.first_name}}  {{branch.branch_manager.last_name}} <span class="user-name--list-super">Branch Manager</span></div>
			
			<div class="user-photo--list--branch-hover" v-if="branch.supervisor">
				<img class="branch-photo-list" v-if="branch.supervisor && branch.supervisor.avatar" :src="$http.options.root  +'/'+ branch.supervisor.avatar.path" alt="userpic">
        		<img class="branch-photo-list" v-else src="images/user_default.jpg" alt="userpic">
			</div>
			<div class="user-name--list--branch-hover" v-if="branch.supervisor">{{branch.supervisor.first_name}}  {{branch.supervisor.last_name}} <span class="user-name--list-super">Supervisor</span></div>
		</td>
		<td class="branch__item-cell branch__item-times-cell">
			<div class="wrap-user-info-time--list">
				<ul class="user-info-time--list">
					<li class="user-info-time--list-li time--list" :class="leftTime">{{getLeftTime()}}
						<ol class="detailed-time--list" v-if="leftTime === 'late'">
							<li class="detailed-time--list-li">Open Time: <b>{{branch.expected_workday_start | moment('h:mm a')}}</b></li>
							<li class="detailed-time--list-li"><strong>{{getLateTime()}} min late</strong></li>
						</ol>
					</li> -
					<li class="user-info-time--list-li status-open--list" :class="rightTime">{{getRightTime()}}
						<ol class="detailed-time--list" v-if="leftTime === 'late'">
							<li class="detailed-time--list-li">Open Time: <b>{{branch.expected_workday_start | moment('h:mm a')}}</b></li>
							<li class="detailed-time--list-li"><strong>{{getLateTime()}} min late</strong></li>
						</ol>
					</li>
				</ul>
			</div>
		</td>
		<td class="branch__item-cell branch__item-panel-cell">
			<div class="user-panel-task--list d-flex justify-content-center">
				<div class="task__item--list task__wrap-ready--list d-flex justify-content-center">
					<ready></ready>
					<span class="task--list">{{branch.tasks.finished.length}}</span>
				</div>
				<div class="task__item--list task__wrap-pending--list d-flex justify-content-center">
					<pending></pending>
					<span class="task--list">{{branch.tasks.pending.length}}</span>
				</div>
				<div class="task__item--list task__wrap-cancel--list d-flex justify-content-center">
					<cancel></cancel>
					<span class="task--list">{{branch.tasks.problems.length}}</span>
				</div>
			</div>
		</td>
		<div class="branch__item-desc--list">
			<ul class="branch__item-desc--list-text d-flex flex-row">
				<li class="branch__item-desc--list-text-title">Problems</li>
				<li class="branch__item-desc--list-text-item">Problem report</li>
				<li class="branch__item-desc--list-text-item">Role is not being stationed</li>
				<li class="branch__item-desc--list-text-item">Task sent to branch manager wasn’t done</li>
			</ul>
			<div class="branch__item-desc--list-graf d-flex flex-row">
				<div class="branch__item-desc--list-graf-text">
					{{ totalTasks }} <span class="branch__item-desc--list-graf-text-span">TASKS</span>
				</div>
				<div class="branch__item-desc--list-graf-info">

				</div>
			</div>
		</div>
	</tr>
</template>

<script>

export default {
	name: 'branch-list-item',
	props: {
		branch: {
			type: Object
		},
		timeFormat: String
	},

    data: () => ({
        leftTime: 'status-closed',
        rightTime: 'status-closed'
    }),
	computed:{
		totalTasks(){
			return this.branch.tasks.finished.length + this.branch.tasks.pending.length + this.branch.tasks.problems.length;
		}
	},
    methods: {
        getLeftTime() {
            if (this.branch.workday_start) {
                if (this.branch.workday_start > this.branch.expected_workday_start) {
                        this.leftTime = 'late'
                } else {
                    this.leftTime = 'time'
                }
                return this.$moment(this.branch.workday_start).format(this.timeFormat ? this.timeFormat : 'h:mm a')
            } else {
                this.leftTime = 'status-closed'
                return 'closed'
            }
        },
        getRightTime() {
            if (this.branch.workday_end) {
                if (this.branch.workday_start > this.branch.expected_workday_start) {
                        this.rightTime = 'late'
                } else {
                    this.rightTime = 'time'
                }
                return this.$moment(this.branch.workday_end).format(this.timeFormat ? this.timeFormat : 'h:mm a')
            } else if (this.branch.workday_start) {
                this.rightTime = 'status-open'
                return 'open'
            } else {
                this.rightTime = 'status-closed'
                return 'closed'
            }
        },
        getLateTime() {
            return this.$moment(this.branch.workday_start).subtract(this.branch.expected_workday_start).format('mm')
        }
    },
		components: {
			ready: require('../../../StateTask/ready'),
			pending: require('../../../StateTask/pending'),
			cancel: require('../../../StateTask/cancel')
		}
	}
</script>

<style lang='scss' src='./style.scss' scoped></style>