<template>
    <ul class="phones col-12 d-flex">
        <phone-item 
        v-for="phone in phones" 
        :phoneItem="phone" 
        :key="phone.name"
        @inputFailedValid="inputFailedValid"
        ></phone-item>
    </ul>
</template>

<script>
import 'images/sprites/phone-icons/star-i.svg'
import 'images/sprites/phone-icons/fire.svg'
import 'images/sprites/phone-icons/plus.svg'

export default {
    props: {
        branch: {
            type: Object
        }
    },
    data: ()=>{
        return{
            phones:[
                {icon: 'images/icons-sprite.svg#star-i-usage', name: 'police'},
                {icon: 'images/icons-sprite.svg#fire-usage', name: 'fire'},
                {icon: 'images/icons-sprite.svg#plus-usage', name: 'ambulance'},
            ]  
        }
    },
    created() {
        this.$watch('branch', function(branch){
            if(branch){
                this.$set(this.phones[0], 'phone', branch.police_phone);
                this.$set(this.phones[1], 'phone', branch.fire_phone);
                this.$set(this.phones[2], 'phone', branch.ambulance_phone);
            }
        }, { immediate: true });
    },
    methods:{
        inputFailedValid(val){
            this.$emit('inputValid', val);
        }
    },
    components:{
        phoneItem: require('./PhoneItem')
    }
}
</script>

<style lang='scss' src='./style.scss' scoped></style>

