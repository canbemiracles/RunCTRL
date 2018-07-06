<template>
    <div class="select" ref="select" :class="[selectClass, {disabled: disabled}]" >
        <div class="active-select" @click.stop="activeToggle">
            <plus-button class="plus"/>
            <input type="text" class="hidden-input-for-select" ref="hideInput"
            @keydown.enter = 'enter'
            @keydown.down = 'down'
            @keydown.up = 'up'
            @keydown.esc="esc"
            >
            <div v-if="!options.length"  :class="[dropDownClass, align]" class="no-data">
                <div class="no-data-inner" :class="{active: isActive}">
                   <slot name="no-data"></slot> 
                </div>
            </div>
        </div>
        <div class="select-options" v-if="options.length" v-show="isActive">
        <div class="arrow"></div>
        <div  :class="dropDownClass" ref="scroll" class="scroll-pane" >
            <ul class="select-options-list" ref="dropdownMenu">
                <select-item
                    v-for="(option, index) in options"
                    :rel="option[valueFieldName]"  
                    :key="index"
                    :index="index"
                    :img="img"
                    :selected="option.selected"
                    :option="option"
                    :roleIcon="roleIcon"
                    :textFieldName="textFieldName"
                    :valueFieldName="valueFieldName"
                    :imgFieldName="imgFieldName"
                    @changeSelection="changeActiveSelect"
                    > 
                </select-item>
            </ul>
        </div>
        </div>
        
    </div>
</template>
<script>
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
        valueFieldName: String,
        textFieldName: String,
        imgFieldName: String,
        selectedOptions: Array,
        roleIcon: Boolean,
        align: {
            default: 'right',
            type: String
        }
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
            selectedItems: []
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
    },
    watch:{
        disabled(value){
            this.initActiveSelect();
        },
        options:{
            handler(value){
                if(value.length){
                    this.initActiveSelect();
                }
            },
        },
        selectedOptions:{
             handler(value){
                if(value.length){
                    this.initActiveSelect();
                }
            },
            deep: true
        }
    },
    methods: {
        //When enter pressed on the input
        enter() {
            if(!this.isActive){
                this.activeToggle();
            }else{
                this.$refs.dropdownMenu.children[this.current].click();
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
                setTimeout(function() {
                    let listHeight = $(that.$refs.dropdownMenu).height();
                    $(that.$refs.scroll).height(listHeight);
                    if (that.apiScroll) {
                        that.apiScroll.reinitialise();
                    }
                }, 50);
            }
            
        },
        initActiveSelect() {
            this.options.forEach(item=>{
                if(~this.selectedOptions.indexOf(item[this.valueFieldName])){
                    this.$set(item, 'selected',true);
                }else{
                    this.$set(item, 'selected',false);
                }    
            });
            this.selectedItems = this.selectedOptions;
        },
        changeActiveSelect(option) {
            this.activeSelect = option;
            if(option[0]){
                option[1].selected = true;
                this.selectedItems.push(option[1]);
            }else{
                option[1].selected = false;
                this.selectedItems = this.selectedItems.filter(item => item.id != option[1].id)
            }
            this.$emit("changeActiveSelect", [option[0], option[1]]);
        }
    },
    components:{
        plusButton: require('../ButtonPlus'),
        selectItem: require('./SelectItem')
    }
}
</script>
<style lang="scss" src="../Select/select.scss" scroped></style>
<style lang="scss" src="./style.scss" scoped></style>

