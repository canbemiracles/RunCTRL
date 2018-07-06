<template>
    <div class="select" ref="select" :class="[selectClass, {disabled: disabled}]" >
        <div class="select-styled"  :class="[{active: isActive}, {focus: isFocus}, {'field-error': isError}, { 'placeholder' : activeSelect.value === null }]" @click.stop="activeToggle">
            <img v-if="img" :src="activeSelect.icon" class="select-icon" />
            <slot name="icon"></slot>
            <div class="select-active-field" v-if="activeSelect.text && !hideFirstTxt" > {{activeSelect.text}}</div>
            <input type="text" class="hidden-input-for-select" ref="hideInput"
            @keydown.enter = 'enter'
            @keydown.down = 'down'
            @keydown.up = 'up'
            @keydown.esc="esc"
            >
        </div>
        <div :class="dropDownClass" ref="scroll" class="scroll-pane select-options" v-show="isActive">
            <ul class="select-options-list" ref="dropdownMenu">
                <li :rel="option.value" v-for="(option, index) in options" 
                :key="index"
                @click.stop="changeActiveSelect($event, option)" class="select-option-item" 
                :class="[{disable: option.value==null}, {'active': isActiveElement(index)}]">
                    <slot name="icon" icon></slot>
                    <img v-if="img" :src="option.icon" class="select-icon" />
                    <div v-if="option.text"> {{option.text}}</div>
                    <span class="label-hint" v-if="option.label">
                        &ndash;&nbsp; {{ option.label }}
                    </span>    
                </li>
            </ul>
        </div>

    </div>
</template>
<script>
import {$eventBus} from '../../../main'
import pointerScroll from "../Mixins/pointerScroll";
export default {
    mixins: [pointerScroll],
    props: {
        selectClass: "",
        dropDownClass: "",
        isError:{
            default: false
        },
        hideFirstTxt: false,
        disabled: false,
        img: false,
        options: {
            type: Array / Object,
        },
        initValue: {
            type: Boolean / String / Number,
            default: false
        },
    },
    data: function() {
        return {
            isActive: false,
            isFocus: false,
            current: 0,
            activeSelect: {
                value: "",
                icon: "",
                text: ""
            },
            apiScroll: undefined,
        }
    },
    mounted() {
        if(this.options.length){
          this.initActiveSelect();  
        }
        var container = $(this.$refs.select);
        let that = this;
        $(document).mouseup(function(e) {
            if (container.has(e.target).length === 0) {
                that.isActive=false;
            }
        });
        $eventBus.$on('changeSelectCountries', this.changeSelectCountriesCallback);
    },
    watch:{
        disabled(value){
            this.initActiveSelect();
        },
        initValue(value){
            if(value){
               this.initActiveSelect(); 
            }
        }
    },
    methods: {
        //When enter pressed on the input
        enter() {
            if(!this.isActive){
                this.activeToggle();
            }else{
                let option = this.options[this.current];
                if(option.value == null){
                    return false;
                }
                this.activeSelect = option;
                this.$emit('changeSelection', option);
                this.activeToggle();
            }
            
        },
        //For highlighting element
        isActiveElement(index) {
        return index === this.current;
        },
        esc(){
            if(this.isActive){
                this.isActive=false;  
            } 
        },
        //When up pressed while select are open
        up() {
            if (this.current > 0) {
                this.current--;
                this.count--;
                if(!this.options[this.current].value){
                    this.up();
                }else{
                    if (this.count <= 0) {
                        this.apiScroll.scrollToY(this.pixelsToPointerTop());
                        this.count++;
                    }
                }
            }else if (this.current==0){
                this.current = this.options.length-1;
                if(!this.options[this.current].value){
                    for(let i=this.options.length - 1; i>0; i--){
                        if(this.options[i].value){
                            this.current = i;
                            break;
                        }
                    }
                }else{
                  this.apiScroll.scrollToY(this.pixelsToPointerTop());  
                }
            }
        },

        //When up pressed while select are open
        down() {
            if (this.current < this.options.length - 1) {
                this.current++;
                this.count++;
                if(!this.options[this.current].value){
                    this.down();
                }else{
                    if (this.count >= this.maxCount) {
                    this.apiScroll.scrollToY(
                        this.pixelsToPointerTop() -
                        this.viewport().bottom +
                        this.pointerHeight()
                    );
                    this.count--;
                    } 
                }
            }else if (this.current==this.options.length - 1){
                this.current = 0;
                if(!this.options[this.current].value){
                    for(let i=0; i<this.options.length; i++){
                        if(this.options[i].value){
                            this.current = i;
                            break;
                        }
                    }
                }else{
                  this.apiScroll.scrollToY(this.pixelsToPointerTop());  
                }
            }
        },

        activeToggle() {
            
            if(this.disabled){
                return false;
            }
            this.isActive = !this.isActive;
            let that=this;
            if(this.isActive){
                let scrollelem = $(this.$refs.scroll).jScrollPane({
                    autoReinitialise: true,
                    showArrows: true,
			        verticalArrowPositions: 'os',
                });
                this.apiScroll = scrollelem.data("jsp");
            }
        },
        initActiveSelect() {
            if(this.initValue !== false){
                if(!this.options[0].value){
                    for(let i=0; i<this.options.length; i++){
                        if(this.options[i].value){
                            this.current = i;
                            break;
                        }
                    }
                }
                for(let i=0; i<this.options.length; i++){
                    if(this.options[i].value == this.initValue){
                        this.activeSelect = this.options[i];
                    }
                }
            }else{
                this.activeSelect = this.options[0];
                if(!this.options[0].value){
                    for(let i=0; i<this.options.length; i++){
                        if(this.options[i].value){
                            this.current = i;
                            break;
                        }
                    }
                }
            }
            this.$emit('changeSelection', this.activeSelect);
        },
        changeActiveSelect(e, option) {
            if(e){
               e.stopImmediatePropagation();  
            }
            if(option.value == null){
                return false;
            }
            this.activeSelect = option;
            this.$emit('changeSelection', option);
            this.activeToggle();
        },
        changeSelectCountriesCallback(option){
             for(let i=0; i<this.options.length; i++){
                if(this.options[i].data_attr==option.name){
                    this.changeActiveSelect(null, this.options[i]);
                    if(this.isActive){
                        this.isActive=false;
                    }
                    break;
                }
            }
        }
    },
    beroreDestroy(){
        $eventBus.$off('changeSelectCountries', this.changeSelectCountriesCallback);
    }
}
</script>
<style lang="scss" src="./select.scss"></style>

