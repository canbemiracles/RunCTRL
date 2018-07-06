<template>
    <div class="google-map" :id="name">
    </div>
</template>
<script>
import { $eventBus } from '../../main.js';
import { mapGetters } from 'vuex';
export default {
    name: 'google-map',
    props: {
      name: String,
      address: Object,
      zoom: {
        type: Number,
        default: 16
      }
    },
    data: function() {
        return {
            map: '',
            getAddress: [],
            googleLoad: false,
        }
    },
    computed: {
        ...mapGetters(['apiKey', 'language']),
        mapMarkers: function() {
            return this.markers
        }
    },
    methods: {
        getCoordsFromAdress(){
            return new Promise((resolve)=>{
                let address = `${this.address.country ? this.address.country : ''} ${this.address.region ? this.address.region : ''} ${this.address.street_address ? this.address.street_address : ''}`;
                address = address.trim();
                $.ajax({
                    url:`https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=${this.apiKey}&language=${this.language}`,
                    type:'get',
                    dataType:'json'
                }).done((res)=>{
                    this.location = res.results;
                    for (let i = 0; i < res.results.length; i++) {
                        this.getAddress.push(res.results[i].geometry);
                    }
                    resolve();
                });
            });
        },
        createMap() {
            const element = document.getElementById(this.name)
            const options = {
                zoom: this.zoom,
                center: new google.maps.LatLng({
                    lat: this.getAddress[0].location.lat,
                    lng: this.getAddress[0].location.lng - 0.007 //смещаем центр карты влево
                }),
                disableDefaultUI: true
            }
            this.map = new google.maps.Map(element, options);
            let marker={};
            const position = new google.maps.LatLng(this.getAddress[0].location)
            marker.map = this.map
            marker.position = position
            marker.icon = require('../../assets/images/pin.png');
            new google.maps.Marker(marker);
        },
    },
    mounted() {
        $eventBus.$on('googleload', ()=>{
            this.googleLoad = true;
        });
        let unwatch = this.$watch('address', (value)=>{
            if(!$.isEmptyObject(value)){
                this.getCoordsFromAdress().then(()=>{
                    if(this.googleLoad && this.getAddress.length){
                        this.createMap();
                        unwatch();
                    }
                });
            }
        });

    }

}
</script>
<style lang='scss' src='./style.scss' scoped></style>
