<template>
    <section class="slide-section-container d-flex">
        <div class="slide-content d-flex flex-column" >
            <div class="row">
                <h3 class="title-header col-md-12">Choose Assignment Type</h3>
            </div>
            <div class="row icon-crad-wrapper">
                <icon-card @selected="selected" :reset="!!!value" class="col-md-12"></icon-card>
            </div>
        </div>
        <recommend>
            <div slot="recommend">
                Analytics only available on paid plans. Please upgrade your account to see.
            </div>
        </recommend>
    </section>
</template>
<script>
import 'images/sprites/user.svg'

export default {
    props:{
        value: String
    },
    methods:{
        selected(value){
            this.$emit('input', value);
            this.$emit('selected', value);
        }
    },
    components:{
        iconCard: require('../../../IconCard'),
        recommend: require('../../../SidebarRecommend'),
    }
}
</script>
<style lang='scss' src='./style.scss' scoped></style>