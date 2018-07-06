<template>
    <div class="wrap-branch-users container-fluid d-flex ">
        <div class="branch-wrap-inner">
            <branch-grid-item
                v-for="(branch, index) in branches"
                :branch="branch" :key="index"
                :timeFormat="timeFormat"
                @click.native="$router.push({ path: `branch-page/${branch.branch_id}` })" />
            <h6 v-if="branches && !branches.length" >No such branches</h6>
        </div>
    </div>
</template>

<script>
import 'images/sprites/sort-users-mesh.svg'
import 'images/sprites/sort-users-column.svg'

export default {
    props: {
        branches: {
            type: Array
        },
        timeFormat: String
    },
	components: {
		ready: require('../../StateTask/ready'),
		pending: require('../../StateTask/pending'),
		cancel: require('../../StateTask/cancel'),
        branchGridItem: require('./BranchGridItem')
	}
}
</script>

<style lang='scss' src='./style.scss' scoped></style>