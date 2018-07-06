<template>
    <div class="counter-wrap">
        <div class="counter d-flex">
            <button type="button" class="but counterBut dec" @click="decrement">◄</button>
            <div class="input-wrap d-flex" :class="{'field-error': validation.hasError('value')}" >
                <input type="text" 
                ref="inputCounter"
                class="field fieldCount"
                @input="resizeInput" 
                v-model="value">
                <div class="input-buffer" ref="inputBuffer"></div>
                <span v-if="unit" class="counter-units">{{ unit }}</span> 
            </div>
            <button type="button" class="but counterBut inc"  @click="increment">►</button>
        </div>
        <div class="error-message">{{validation.firstError('value')}}</div>
    </div>
</template>
<script>
import { Validator } from "simple-vue-validator";
export default {
    mixins: [require("simple-vue-validator").mixin],
    props:{
        min:{
            type: Number,
            default: 1
        },
        max:{
            type: Number/Boolean,
            default: false
        },
        disabled:{
            type: Boolean,
            default: false
        },
        unit: {
            type: String,
            default: ''
        },
        init:{
            type: Number
        }
    },
	data: function(){
		return {
            value: 1
		}
    },
    computed: {
    },
	validators: {
        'value'(val) {
            if(this.max){
                return Validator.value(val).required().integer().greaterThanOrEqualTo(this.min).lessThanOrEqualTo(this.max);
            }
            return Validator.value(val).required().integer().greaterThanOrEqualTo(this.min);
        },
    },
    watch:{
        value: function(val){
            this.validateCount();
        },
        max(){
            this.validateCount();
        }
    },
    mounted(){
        if(this.init){
            this.value = this.init;
        }
    },
	methods:{
		decrement(){
			if(!this.disabled){
				let val = parseInt(this.value)-1;
				if(val >= this.min) {
					this.value = val;
				}
			}
		},
		increment(){
			if(!this.disabled){
				let val = parseInt(this.value)+1;
				if(!this.max || val <= this.max) {
					this.value = val;
				}
			}
        },
        validateCount(){
            this.$validate().then(success => {
                if(success){
                   this.$emit('changeValue', this.value);  
                }
            }); 
        },
        resizeInput(){
            var $input = $(this.$refs.inputCounter),
            $buffer = $(this.$refs.inputBuffer);
            $buffer.text(this.value + ' ');
            $input.width($buffer.width() + 10);
        }
	}
}
</script>
<style lang='scss' src='./style.scss' scoped></style>