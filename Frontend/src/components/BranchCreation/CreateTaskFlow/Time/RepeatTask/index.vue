<template>
	<div class="switcher">
		<input :checked="checked" @change="changeValue" class="switcher__input" type="checkbox" name="watched" :id="'switcher'+ index">
		<label class="switcher__label" :for="'switcher'+ index"></label>
	</div>
</template>
<script>
export default {
	props:{
		index: Number/String,
		checked: Boolean
	},
	model: {
		prop: 'checked',
		event: 'change'
  	},
	data: () => ({
	}),
	methods:{
		changeValue(event){
			this.$emit('change', event.target.checked);
		}
	}
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
