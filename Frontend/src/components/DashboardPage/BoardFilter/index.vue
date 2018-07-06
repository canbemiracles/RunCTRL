<template>
        <div class="filter-branches">
        <ul class="d-flex justify-content-end">
            <li class="mr-auto d-flex align-items-center">
                <h4 class="title-branches">Branches</h4>
            </li>
            <li :class="{'active-button-filter': filter === 'default'}">
                <button @click="selectFilter('default')" type="button">Default</button>
            </li>
            <li :class="{'active-button-filter': filter === 'open'}">
                <button @click="selectFilter('open')" type="button">Open</button>
            </li>
            <li :class="{'active-button-filter': filter === 'late'}">
                <button @click="selectFilter('late')" type="button">Late</button>
            </li>
            <li :class="{'active-button-filter': filter === 'close'}">
                <button @click="selectFilter('close')" type="button">Close</button>
            </li>
            <li :class="{'active-button-filter': filter === 'problem'}">
                <button @click="selectFilter('problem')" type="button">Problem</button></li>
            <li>
                <div>
                    by Tasks:
                    <select name="" id="">
                        <option value="">All</option>
                        <option value="">2</option>
                    </select>
                </div>
            </li>
            <li>
                <div>
                    by Manager:
                    <select name="" id="">
                        <option value=""> All</option>
                        <option value="">2</option>
                    </select>
                </div>
            </li>
            <li>
                <div class="wrap-sort-users">
                    <input @click="$emit('update:view', 'grid')" id="mesh" type="radio" name="sort-users" checked>
                    <label class="mesh" for="mesh"><svg class="icon"><use xlink:href="images/icons-sprite.svg#sort-users-mesh"></use></svg></label>

                    <input @click="$emit('update:view', 'list')"id="column" type="radio" name="sort-users">
                    <label class="column" for="column"><svg class="icon"><use xlink:href="images/icons-sprite.svg#sort-users-column"></use></svg></label>
                </div>
            </li>
            <li>
                <router-link tag="button" class="added-user" to="/branch-flow">+</router-link>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    data: () => ({
        filter: 'default'
    }),
    methods: {
        selectFilter(filter) {
            this.filter = filter
            this.$emit('update:filter', this.filter)
        }
    }
}
</script>

<style lang="scss" src="./style.scss" scoped></style>