<template>
    <aside class="sidebar d-flex">
        <div class="recommendations d-flex flex-column">
            <div class="recommend-title">Recommendations</div>
            <div class="recommend-content">
                <slot name="recommend"></slot>
            </div>
        </div>
    </aside>
</template>
<script>
export default {
};
</script>
<style lang='scss' src='./style.scss' scoped></style>