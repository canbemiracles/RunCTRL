<template>
    <div class="container-fluid d-flex flex-column p-0 page-container">
        <app-header :reports="16"></app-header>
        <schedule type="full"></schedule>
        <app-footer></app-footer>
    </div>
</template>
<script>
export default {
    components:{
        schedule: require('../Schedule'),
        appHeader: require('../Header'),
        appFooter: require('../Footer'),
    }
}
</script>
