<template>
    <draggable  class="roles-list d-flex flex-wrap"  
    v-model="dragList" :options="dragOptions" @start="drag('start')" @end="drag('end')">
        <roles-item v-for="(role, index) in dragList"
        :key="index"
        :name="role.name"
        :color="role.color"
        @click="chooseRoleTemplate(role)"
        ></roles-item>
    </draggable>
</template>
<script>
import draggable from 'vuedraggable'
export default {
    props:{
        rolesList: Array,
    },
    data: ()=>({
        dragList: []
    }),
    components:{
        rolesItem: require('./RolesItem'),
        draggable
    },
    watch: {
        rolesList: {
            handler: function(){
                this.dragList = this.rolesList;
            },
            immediate: true
        }
    },
    computed: {
        dragOptions:function(){
            return {
                group:{
                    name: 'role',
                    pull: 'clone', 
                    draggable: '.roles-item',
                    dragClass: 'roles-item'
                }
            }
        }
    },
    methods:{
        chooseRoleTemplate(role){
            this.$emit('chooseRoleTemplate', role);
        },
        drag(move){
           this.$emit('dragRoleTempl', move); 
        }
    }
}
</script>
<style lang='scss' scoped>
.roles-list{
    padding: 20px;
}
</style>