<template>
	<transition name="modal">
		<b-modal id="taskModal" ref="modal" class="task-create-modal" :class="{'choose-type-modal' : typeModal =='choose'}" 
		:hide-header="true"
		:hide-footer="true"
		:lazy="true"
		@show="close=false"
		@hide="closeModal"
		>		
				<div id="modal-inner">
					<assignment-type v-if="typeModal =='choose'" class="choose-type" @selected="chooseTaskType" :popover="true"></assignment-type>
					<task-card v-else :task="task"
					id="taskCard" 
					:close="close" 
					@sendData="sendData"
					:disabledDays="disabledDays" 
					@validFalse="close = false"></task-card>
				</div>	
				<b-popover target="modal-inner"
					placement="right"
					:show.sync="popoverShow"
					title="Close confirm"
					triggers=""
					content="Popover!">
      			</b-popover>
		</b-modal>
		
	</transition>
</template>
<script>
import {mapGetters, mapMutations} from 'vuex';
	export default {
		name: 'Modal',
		props:['task', 'disabledDays'],
		data(){
			return {
				close: false,
				validation: false,
				popoverShow: false
			}
		},
		computed:{
			...mapGetters(['typeModal']),
		},
		methods: {
			...mapMutations(['setTypeModal', 'setCurrentTaskEdit', 'resetTypeModal', 'deleteTempTask', 'setStartGetBranchStatus']),
			chooseTaskType(type){
				this.setTypeModal(type);
			},
			closeModal(e){
				if(this.typeModal!='choose'){
					if(this.validation){
						this.validation = false;
						this.close = false;
						this.setStartGetBranchStatus(true);
						this.setCurrentTaskEdit(null);
						this.resetTypeModal();
					}else{
						e.preventDefault();
						this.close=true;
					}
				}else{
					this.deleteTempTask(this.task.index);
				}
				
				this.setStartGetBranchStatus(true);
				// this.popoverShow = true;
				
				
			},
			sendData(){
				this.validation = true;
				this.$refs.modal.hide();
			},
		},
		components:{
			assignmentType: require('../../IconCard'),
			taskCard: require('../../TaskCard')
		}
	}
</script>
<style lang="scss" scoped>

.modal-enter{
	opacity: 0;
}
.modal-leave-active {
	opacity: 0;
}
.modal-enter .modal-container,
.modal-leave-active .modal-container {
	-webkit-transform: scale(1.1);
	transform: scale(1.1);
}

.modal-backdrop{
    background-color: #d9dedead;
}
.task-create-modal{
	/deep/ .choose-type{
		margin: 0;
		display: flex;
		flex-direction: column;
		.icon{
			max-width: 50%;
		}
		.rectangle{
			min-height: 95px;
		}
		.row{
			margin: 0;
		}
		.item-task__desc{
			bottom: 5px;
		}
	}
	/deep/ .modal-content{
		box-shadow: 2px 6px 10px rgba(28, 60, 77, 0.1);
		border: none;
		border-radius: 2px;
		color: $lightgray;
		text-align: center;
	}
	/deep/ .modal-dialog{
		width: 380px;
	}
	/deep/ .modal-body{
		padding: 0;
	}
}
.choose-type-modal{
	/deep/ .popover {
			&.bs-popover-left .arrow{
				&:before, &:after{
					border-width: 8px;
					top: 3px;
					right: -0.5em;
				}
				&:before{
					border-left-color: rgba(0,0,0,.25);
					
				}
				&:after{
					border-left-color: #DFEFF8;
					right: -0.4em;
				}
			}
			&.bs-popover-right {
				.arrow{
					&:before, &:after{
						border-width: 8px;
						top: 3px;
						left: -0.5em;
					}
					&:before{
						border-right-color: rgba(0,0,0,.25);
					}
					&:after{
						left: -0.4em;
						border-right-color: #DFEFF8;
					}
				}
			}
			.popover-header{
				background-color: #DFEFF8;
				font-family: 'Roboto-Bold', sans-serif;
				font-size: 15px;
				color: #608191;
				border: none;
			}
			.popover-body{
				background-color: #DFEFF8;
				font-family: 'Roboto-Regular', sans-serif;
				font-size: 15px;
				color: #7e8a90;
				width: 100%;
			}  
	} 
}
</style>