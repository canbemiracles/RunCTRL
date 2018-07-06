<template>
    <div class="wrap">
    <icon-card></icon-card>
    <task-card></task-card>
    </div>
</template>
<script>
    import IconCard from "../IconCard";
    import TaskCard from "../TaskCard";
    export default {
        name: 'QuickTaskFlow',
        data: ()=>({

        }),
        components: {
            IconCard,
            TaskCard
        },
    }
</script>
<style lang="scss" src="./style.scss" scoped=""></style>