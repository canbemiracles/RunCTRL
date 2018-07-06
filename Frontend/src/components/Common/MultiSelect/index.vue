<template>
    <div class="search-select" ref="autocomplete" :class="{'open':openSuggestion}">
        <div class="search-field">
            <input class="input-search form_input"
                :placeholder="placeholder"
                :class="{active: isActive}" type="text"
                v-model="selection"
                autocomplete="off"
                @keydown.enter = 'enter'
                @keydown.down = 'down'
                @keydown.up = 'up'
                @keydown.esc="esc"
                @click='openDrop'
                @input = 'change'>
        </div>
        <div v-show="openSuggestion" class="select-options" :style="{height: heightWrap}" >
            <div ref="scroll" class="scroll-pane" :class="dropDownClass">
                <ul class="select-options-list" ref="dropdownMenu">
                    <select-item 
                    v-for="(suggestion, index) in matches" 
                    :key="index" 
                    :selOption="suggestion"
                    :selected="suggestion.selected" 
                    @itemClick="changeActiveSelect"
                    :class="{'active': isActive(index)}" 
                    :imgSrc="suggestion.icon ? suggestion.icon : false" 
                    :text="suggestion.text"></select-item>
                </ul>
            </div>
            <div class="bottom-btn" ref="bottomBtn">
                <slot name="lastField"></slot>
            </div>
        </div>
    </div>
</template>
<script>
import pointerScroll from "../Mixins/pointerScroll";
import autocomplete from '../Mixins/autocompleteMixin'
export default {
    mixins: [pointerScroll, autocomplete],
    props: {
        selectClass: "",
        id: String,
        placeholder: {
            default: 'Start Typing Name...'
        },
        suggestions: {
            type: Array,
            required: true
        },
        isError: {
            default: false
        },
        dropDownClass: "",
        fieldName: {
            type: String,
            required: true
        },
        text: {
            type: String,
            required: false
        }
    },
    data: function() {
        return {
            selected: [],
            heightWrap: ''
        }
    },
    methods: {
        changeActiveSelect(option) {
            //если элемент выбран, добавить в выборку. иначе удалить
            if (option[0]) {
                option[1].selected = true;
                this.selected.push(option[1]);
            } else {
                option[1].selected = false;
                this.selected = this.selected.filter(item => item.value != option[1].value)
            }
            this.$emit('changeSelectedItems', this.selected, option);
        },
        enter() {
            this.$refs.dropdownMenu.children[this.current].click();
        },
        initSelected(){
            this.suggestions.forEach(element => {
                if(element.selected){
                   this.selected.push(element);
                }
            });
        },
        setHeightWrap(){
            this.heightWrap = $(this.$refs.scroll).height() + $(this.$refs.bottomBtn).find('> *').height() + 'px';
        }

    },
    mounted(){
        this.initSelected();
        this.$on('changeHeightDrop', this.setHeightWrap);
    },
    components: {
        selectItem: require('./SelectItem')
    }
}
</script>
<style lang='scss' src='../Select/select.scss' scoped></style>
<style lang='scss' src='./style.scss' scoped></style>