<template>
    <ul class="nav">
        <li class="nav-item" v-for="(item, index) in items" :key="index" >
            <router-link class="nav-link" :to="item.link" @click="activeMenu($event)">{{item.name}}</router-link>
            <span class="reports-count" v-if="item.name =='Reports'">{{reportsCount}}</span>
        </li>
    </ul>
</template>
<script>
export default {
    name: 'appMenu',
    props: {
       items:{
           type: Array,
       },
        reportsCount: Number,
    },
    methods: {
        activeMenu: function(event){
           $('.nav-link').removeClass('active'); 
           event.target.classList.add('active')
        }
    }   
}
</script>

<style lang="scss" src="./style.scss" scoped></style>
<style>
.navbar-nav{
    justify-content: flex-start;
    margin-right: auto;
}
</style>


