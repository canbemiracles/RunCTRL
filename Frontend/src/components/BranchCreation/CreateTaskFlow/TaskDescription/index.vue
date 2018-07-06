<template>
	<section class="slide-section-container d-flex">
		<div class="slide-content d-flex flex-column" >
			<div class="row">
				<h3 class="title-header col-md-12">Task Description</h3>
			</div>
			<div class="row">
				<div class="col-md-12">

					<div class="wrap" v-if="type === 'standard'">
						<input 
							class="input-title" 
							type="text"
							placeholder="Enter task title"
							v-model="value.title">
						<div class="svg-task">
							<svg class="icon standard_task"><use xlink:href="images/icons-sprite.svg#standard_task"></use></svg>
						</div>
						<input 
							class="desc"
							type="text"
							placeholder="Task Decriprion"
							v-model="value.info">
					</div>

					<div class="wrap" v-if="~type.indexOf('notification')">
						<input v-model="value.title" class="input-title" type="text" placeholder="Enter task title">
						<div class="svg-task">
							<svg class="icon notification"><use xlink:href="images/icons-sprite.svg#notification"></use></svg>
						</div>
						<input type="text" v-model="value.info" class="desc" placeholder="Task Decriprion">
					</div>
					
					<div class="wrap" v-if="type === 'checklist'">
						<input
							v-model="value.title"
							class="input-title"
							type="text"
							placeholder="Enter task title">
						<div class="svg-tasks">
							<svg class="icon checklist"><use xlink:href="images/icons-sprite.svg#checklist"></use></svg>
						</div>
						<div class="wrap__input-title-one">
							<input
								v-for="(item, index) in value.checklist"
								class="input-title-one"
								:key="index"
								v-model="item.task"
								type="text"
								placeholder="Add new task to the checklist">
							<div class="add-btn"><span class="plus-icon" @click.prevent="addToChecklist">+</span></div>
						</div>
					</div>

					<div class="wrap" v-if="type === 'question_text'">
						<input v-model="value.title" class="input-title" type="text" placeholder="Enter task title">
						<div class="svg-task">
							<svg class="icon question_text_input"><use xlink:href="images/icons-sprite.svg#question_text_input"></use></svg>
						</div>
					</div>

					<div class="wrap" v-if="type === 'question_range'">
						<input v-model="value.title" class="input-title" type="text" placeholder="Do something right now">
						<div class="svg-task">
							<svg class="icon question_range_from_x_to_y"><use  xlink:href="images/icons-sprite.svg#question_range_from_x_to_y"></use></svg>
						</div>
					</div>

					<div class="wrap" v-if="type === 'question_numeric'">
						<input v-model="value.title" class="input-title" type="text" placeholder="Enter task title">
						<div class="svg-task">
							<svg class="icon question_numeric_input"><use xlink:href="images/icons-sprite.svg#question_numeric_input"></use></svg>
						</div>
					</div>

					<div class="wrap" v-if="type === 'question_yes_no'">
						<input v-model="value.title" class="input-title" type="text" placeholder="Enter task title">
						<div class="svg-task">
							<svg class="icon question_yes_no"><use xlink:href="images/icons-sprite.svg#question_yes_no"></use></svg>
						</div>
					</div>

					<div class="wrap" v-if="type === 'question_answer_list'">
						<input v-model="value.title" class="input-title" type="text" placeholder="Do something right now">
						<div class="svg-task">
							<svg class="icon question_4_answers"><use xlink:href="images/icons-sprite.svg#question_4_answers"></use></svg>
						</div>
						<input 
							type="text"
							v-for="(item, index) in value.answers"
							class="desc"
							:key="index"
							:placeholder="'Option ' + (index + 1)"
							v-model="item.answer">
					</div>

					<div class="wrap" v-if="type === 'message'">
						<input v-model="value.title" class="input-title" type="text" placeholder="Title message">
						<div class="svg-task">
							<svg class="icon message_to_employee"><use xlink:href="images/icons-sprite.svg#message_to_employee"></use></svg>
						</div>
						<input 
							class="desc"
							type="text"
							placeholder="Message to employee"
							v-model="value.info">
					</div>

				</div>
			</div>
		</div>
		<recommend>
            <div slot="recommend">
                Analytics only available on paid plans. Please upgrade your account to see.
            </div>
        </recommend>
	</section>
</template>
<script>
import 'images/sprites/user.svg'
import 'images/sprites/taskIcons/standard_task.svg'
import 'images/sprites/taskIcons/question_yes_no.svg'
import 'images/sprites/taskIcons/question_4_answers.svg'
import 'images/sprites/taskIcons/question_numeric_input.svg'
import 'images/sprites/taskIcons/question_range_from_x_to_y.svg'
import 'images/sprites/taskIcons/checklist.svg'
import 'images/sprites/taskIcons/question_text_input.svg'
import 'images/sprites/taskIcons/notification.svg'
import 'images/sprites/taskIcons/message_to_employee.svg'
export default {
	props: {
		type: {
			type: String,
			required: true
		},
		value: {
			type: Object,
			required: true
		}
	}, 
	watch: {
		type(type) {
			if (this.type === 'checklist') {
				!this.value.checklist.length && this.addToChecklist()
			}
			if (this.type === 'question_answer_list') {
				!this.value.checklist.length && this.addToAnswerslist()
			}
		}
	},

	methods: {
		addToChecklist() {
			this.value.checklist.push({ task: '' })
		},
		addToAnswerslist(){
			for(let n in [0, 1, 2, 3]){
				this.value.answers.push({ answer: '' })
			}
		}
	},
	components:{
		recommend: require('../../../SidebarRecommend')
	}
}
</script>
<style lang='scss' src='./style.scss' scoped></style>