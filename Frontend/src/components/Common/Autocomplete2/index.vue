<template>
  <div class="autocomplete" :style="{ width }">
    <input
      ref="input"
      type="text"
      v-model="search"
      :class="{ with_border: border }"
      :placeholder="multiselect && selected.length ? '' : placeholder"
      :disabled="disabled"
      @focus="openList"
      @blur="closeList"
      @keydown="onKeyDown"
      @input="initScroll"
    >
    <div class="scroll-pane autocomplete-dropdown" ref="scroll" v-show="showList">
      <ul class="items" ref="list">
        <li
          v-for="(option, i) in filteredOptions" :key="i"
          v-text="option[fieldName]"
          :class="{ active: i === focusIndex, selected: selected.includes(option) }"
          @mouseover="setFocusOption(i, $event)"
          @mousedown="select(option)"
        ></li>
        <li v-if="options.length && !filteredOptions.length">Not found...</li>
      </ul>
    </div>
    

    <ul v-if="multiselect" class="badges">
      <li
        v-for="(option, i) in selected" :key="i"
        v-text="option[fieldName]"
        @mousedown.prevent="select(option)"
      ></li>
    </ul>

  </div>
</template>

<script>
export default {
  props: {
    placeholder: {
      type: String,
      default: 'Search...'
    },
    width: {
      type: String,
      default: '100%'
    },
    border: {
      type: Boolean,
      default: true
    },
    disabled: {
      type: Boolean,
      default: false
    },
    options: {
      type: Array,
      default: () => [] // Массив объектов
    },
    fieldName: { // Поле, по которому идет поиск
      type: String,
      default: 'text'
    },
    multiselect: {
      type: Boolean,
      default: false
    },
    text: {
      type: String
    }
  },

  data() {
    return {
      search: '',       // Искомое значение
      showList: false,  // Показывать ли опции
      focusIndex: 0,    // Фокус при наведении на опцию или при нажатии down/up
      selected: [],      // Выбранные опции для multiselect,
      apiScroll: null
    }
  },

  watch: {
    search(value) {
      value && this.$emit('search', value)
    },
    text: {
      handler(value) {
        if (value && value != '') {
          this.search = value
        }
      },
      immediate: true
    }
  },

  computed: {
    // Результаты поиска
    filteredOptions() {
      if (!this.options.length) return []
      const s = this.search.toLowerCase()
      return this.options.filter(o => o[this.fieldName].toLowerCase().indexOf(s) > -1)
    }
  },

  methods: {

    select(option) {
      if (this.multiselect) {

        // Тогглим опцию
        const i = this.selected.indexOf(option)
        i > -1
          ? this.selected.splice(i, 1)
          : this.selected.push(option)

        this.$emit('select', this.selected)

        // Возвращаем фокус на инпут, чтобы список опций не закрывался
        this.$nextTick(() => this.$refs.input.focus())
      } else {
        this.search = option[this.fieldName]
        this.$emit('select', option)
        this.showList && this.$refs.input.blur()
      }
    },

    onKeyDown(e) {
      switch (e.keyCode) {
        case 40: // down
          e.preventDefault()
          this.setFocusOption(this.focusIndex + 1)
          break
        case 38: // up
          e.preventDefault()
          this.setFocusOption(this.focusIndex - 1)
          break
        case 13: // enter
          e.preventDefault()
          this.select(this.filteredOptions[this.focusIndex])
          break
      }
    },
    openList() {
      this.showList = true
      this.focusIndex = 0;
      let scrollelem = $(this.$refs.scroll).jScrollPane({
          autoReinitialise: true
      });
      this.apiScroll = scrollelem.data("jsp");
      this.initScroll();
    },
    closeList() {
      this.showList = false
    },

    // При нажатиях клавиш down/up и наведении мыши выделяем опцию и прокручиваем список
    setFocusOption(i, e) {
      if (this.filteredOptions[i]) {
        // Если смена активной опции вызвана мышью - не скроллим
        !e && (this.apiScroll.scrollToY(i > 2 ? (i - 2) * 38 : 0)); 
        this.focusIndex = i
      }
    },
    initScroll(){
      let that=this;
      setTimeout(function() {
        let listHeight = $(that.$refs.list).height();
        $(that.$refs.scroll).height(listHeight);
        if (that.apiScroll) {
          that.apiScroll.reinitialise();
        }
      }, 50);
    }
  }
}
</script>

<style lang="scss" scoped>
.autocomplete {
  display: inline-block;
  height: 100%;
  position: relative;

  input {
    width: 100%;
    height: 100%;
    border: none;
    outline: none;
    border-bottom: 1px solid transparent;

    &.with_border {
      border: 1px solid #ccd7dd;
      border-radius: 2px;
      padding-left: 10px;
    }

    &:focus {
      border-color: $blue;
    }
  }

  ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }
  .autocomplete-dropdown{
    max-height: 200px;
    box-shadow: 1px 3px 10px rgba(0, 0, 0, .15);
    background: #fff;
    width: 100%;
    position: absolute;
    top: 100%;
    z-index: 999;
  }
  .items {
    li {
      padding: 5px 20px;
      cursor: pointer;
      position: relative;

      &.active {
        background: $lightblue
      }

      &.selected {
        padding-right: 30px;

        &:after {
          content: url('data:image/svg+xml; utf8, <svg width="100%" viewBox="0 0 1792 1792" xmlns="http://www.w3.org/2000/svg"><path d="M1671 566q0 40-28 68l-724 724-136 136q-28 28-68 28t-68-28l-136-136-362-362q-28-28-28-68t28-68l136-136q28-28 68-28t68 28l294 295 656-657q28-28 68-28t68 28l136 136q28 28 28 68z"  fill="#0fd5f7"/></svg>');
          width: 15px;
          height: 15px;
          position: absolute;
          right: 10px;
          top: 7px;
        }
      }
    }
  }

  .badges {
    position: absolute;
    top: 5px;
    left: 5px;

    li {
      display: inline-block;
      background: $lightblue;
      padding: 3px 16px 3px 7px;
      border-radius: 3px;
      margin-right: 5px;
      position: relative;
      cursor: pointer;

      &:after {
        content: '+';
        transform: rotate(45deg);
        position: absolute;
        right: 5px;
        top: 3px;
        opacity: .5
      }
      &:hover:after {
        opacity: 1
      }
    }
  }
}
</style>
