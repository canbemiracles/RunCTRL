<template>
    <div class="container-fluid p-0 d-flex flex-column container-one-page">
        <app-header :reports="16"></app-header>
        <div class="page-container d-flex">
            <section class="container-fluid p-0 wrap-main">
                <div class="branches">
                    <board-filter :view.sync="view" :filter.sync="filter" />
                    <board-grid :timeFormat="timeFormat" v-if="view === 'grid'" :branches="filteredBranches"/>
                    <board-list :timeFormat="timeFormat" v-if="view === 'list'" :branches="filteredBranches"/>
                </div>
            </section>
        </div>
        <info-panel :info="info"></info-panel>
        <income></income>
        <reports></reports>
    </div>
</template>
<script>
import {mapGetters, mapActions} from 'vuex';
    export default {
    
        data: function() {
            return {
                view: 'grid',
                filter: 'default',
                info: {},

            }
        },

        created(){
            this.$http.get(`api/v1/companies/${this.$auth.user().company_id}/get_live_data`).then(
                res => this.info = res.body);
            this.getCompanyData();
        },
        computed: {
            ...mapGetters(['timeFormat']),
            filteredBranches() {
                switch (this.filter) {
                    case 'open': 
                        return this.info.branches_list.filter(branch => branch.workday_start && !branch.expected_workday_end)
                    case 'close': 
                        return this.info.branches_list.filter(branch => branch.expected_workday_end || !branch.workday_start)
                    case 'late': 
                        return this.info.branches_list.filter(branch => branch.workday_start > branch.expected_workday_start)
                    case 'problem': 
                        return this.info.branches_list.filter(branch => branch.tasks.problems.length > 0)
                    default: 
                        return this.info.branches_list
                }
            }
        },
        components: {
            appHeader: require('../Header'),
            boardGrid: require('./BoardGrid'),
            boardList: require('./BoardList'),
            boardFilter: require('./BoardFilter'),
            infoPanel: require('./InfoPanel'),
			income: require('./Income'),
            reports: require('./Reports'),

        },
        methods:{
           ...mapActions(['getCompanyData']) 
        }
    }
</script>
<style lang='scss' src='./style.scss' scoped></style>