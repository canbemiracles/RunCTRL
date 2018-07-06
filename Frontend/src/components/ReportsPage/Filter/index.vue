<template lang="html">
  <div>
    <p class="th__filter"
       @click="showFilter= !showFilter">
        <slot></slot>
       <svg class="icn__filter"><use xlink:href="images/icons-sprite.svg#filter"></use></svg>
     </p>
    <div v-if="showFilter"
         class="filter filter__manager">
      <div class="filter__manager-search-wrap">
        <input class="filter__manager-search"
               type="text"
               name="search"
               placeholder="Search..."
               v-model="searchQuery">
        <svg class="icon icn-search"><use xlink:href="images/icons-sprite.svg#search"></use></svg>
      </div>
      <ul class="filter__manager-branches">
        <li class="filter__manager-branches-item"
            v-for="(item, i) in filtredItems"
            :key="i">
          <label class="container active-manager-branches">{{getText(item)}}
            <input type="checkbox"
                   v-model="item.selected">
            <span class="checkmark"></span>
          </label>
        </li>
        <li v-if="!filtredItems.length">
          <p class="container active-manager-branches">nothing found</p>
        </li>
      </ul>
      <div class="filter__manager-button-wrap">
        <button class="filter__manager-button" @click="applyFilter">Ок</button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    items: {
      type: Array
    },
    name: String
  },
  data: () => ({
    searchQuery: '',
    showFilter: false
  }),

  methods: {
    applyFilter() {
      this.showFilter = false
      this.$emit('applyFilter', this.selectedItems, this.name)
    },
    getText(item) {
      switch (this.name) {
        case 'managersFilter':
          return `${item.first_name} ${item.last_name}`
        case 'branchesFilter':
          return item.geographical_area ? item.geographical_area.street_address : 'Branch without address'
        case 'regionsFilter':
          return item.id

      }
    }
  },

  computed: {
    selectedItems() {
      return this.items.filter(item => item.selected)
    },
    filtredItems() {
      return this.items.filter(item => this.getText(item)
        .indexOf(this.searchQuery) !== -1)
    }
  }
}
</script>

<style lang="scss" scoped src="../style.scss"></style>
