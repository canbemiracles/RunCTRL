<template>
<section class="slide-section-container d-flex">
	<div class="slide-content d-flex flex-column" >
		<div class="row section-header">
			<div class="col-md-12">
				<div class="title-header">What is your company?</div>
				<div class="title-subtitle">Choose the following options to determine how to set up your company branches</div>
			</div>
		</div>
		<div class="row wrap-layers d-flex">
			<div class="item-layers col-4 d-flex flex-column">
				<div class="item-content d-flex flex-column">
					<svg class="icon"><use xlink:href="images/icons-sprite.svg#write-icon"></use></svg>
					<div class="item-title">Duplicate Branch</div>
					<div class="item-description">Create additional duplicated branches</div>
					<counter class="counter" :min="2" @changeValue="setCount"></counter>	
				</div>	
				<a class="button-layers submit-btn" :class="{disabled: isSendData}" href="#" @click.prevent="saveTemplates">Save as template</a>
			</div>
			<div class="item-layers col-4 d-flex flex-column">
				<div class="item-content d-flex flex-column">
					<svg class="icon"><use xlink:href="images/icons-sprite.svg#add-icon"></use></svg>
					<div class="item-title">Create Additional Branch</div>
					<div class="item-description">
						I want to create additional branch<br>
						with no relation to the first branch I have<br>
						just created.
					</div>
				</div>	
				<a class="button-layers submit-btn" href="#" @click.prevent="saveAndCreate">Save & Create New Branch</a>
			</div>
			<div class="item-layers col-4 d-flex flex-column">
				<div class="item-content d-flex flex-column">
					<svg class="icon"><use xlink:href="images/icons-sprite.svg#ready-icon"></use></svg>
					<div class="item-title">{{branchCount}} Branch</div>
					<div class="item-description" v-if="branchCount==1">I have only 1 branch, i’m done!</div>
					<div class="item-description" v-if="branchCount>1">I have {{branchCount}} branches, i’m done!</div>
				</div>
				<a class="button-layers submit-btn" href="#" @click.prevent="complete">Complete!</a>
			</div>
		</div>
	</div>	
</section>
</template>

<script>
import 'images/sprites/company-branches/add-icon.svg'
import 'images/sprites/company-branches/ready-icon.svg'
import 'images/sprites/company-branches/write-icon.svg'

export default {
	props: {
		branchCount: {
			type: Number,
			default: 1
		},
		isSendData: {
			type: Boolean
		}
	},
	data: ()=>({
		count: 2,
	}),
	methods:{
		setCount(value){
			this.count = value;
		},
		saveTemplates: _.debounce(function(e){
			this.$emit('saveTemplates', this.count);
		}, 250),
		saveAndCreate: _.debounce(function(e){
			this.$emit('saveAndCreate');
		}, 250),
		complete: _.debounce(function(e){
			this.$emit('complete', this.branchCount);
		}, 250),
	},
	components:{
		counter: require('../../../Common/Counter')
	}
}
</script>

<style lang='scss' src='./style.scss' scoped></style>