<template>
<div class="wrap-container d-flex flex-column">
    <div class="row">
        <h3 class="title-header col-md-12">Task Importance</h3>
    </div>
    <div class="row no-gutters wrap">
        <div class='switcher'>
            <input
                v-model.number="importance"
                id='low1'
                class='switcher-radio-low'
                type='radio'
                name='value1'
                value='1'>
            <label class='switcher-label switcher-button  switcher-low' for='low1'>
                <span class="switcher-button switcher-button-low">low</span>
                <span class="point point-left"></span>
            </label>

            <input
                v-model.number="importance"
                id='medium1' class='switcher-radio-medium'
                type='radio'
                name='value1'
                value='2'>
            <label class='switcher-label switcher-button switcher-medium' for='medium1'>
                <span class="switcher-button switcher-button-medium">medium</span>
                <span class="point point-center"></span>
            </label>

            <input
                v-model.number="importance"
                id='high1'
                class='switcher-radio-high'
                type='radio'
                name='value1'
                value='3'>
            <label class='switcher-label switcher-button switcher-high' for='high1'>
                <span class="switcher-button switcher-button-high">high</span>
                <span class="point point-right"></span>
            </label>

            <div class='switcher-slider'></div>
        </div>
    </div>
    <div class="buttons">
        <button class="submit-btn" @click="$emit('duplicate')">duplicate</button>
        <button class="submit-btn" @click="$emit('complete')">complete</button>
    </div>
</div>
</template>

<script>
export default {
    data() {
      return {
        importance: 1
      }
    },
    watch: {
        importance(val) {
            this.$emit('input', val)
        }
    }
}
</script>

<style lang='scss' src='./style.scss' scoped></style>
