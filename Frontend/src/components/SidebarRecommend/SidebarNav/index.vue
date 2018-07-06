<template>
    <div class="section-navigation is-fixed" v-if="show">
        <a href="#" class="section-navigation__prev" @click.prevent="clickPrev">
            <svg width="20" class="icon">
                <use xlink:href="images/icons-sprite.svg#arrow-up-usage"></use>
            </svg>
        </a>
        <div class="bullets-list">
            <a href="#" class="bullet-item"
            v-for="(bullet, index) in bullets"
            @click.prevent="moveTo(index)"
            :class="{active: current==index}"
            :key="index"></a>
        </div>
        <a href="#" class="section-navigation__next" @click.prevent="clickNext">
            <svg width="20" class="icon">
                <use xlink:href="images/icons-sprite.svg#arrow-down-usage"></use>
            </svg>
        </a>
    </div>
</template>
<script>
import 'images/sprites/arrow-up.svg'
import 'images/sprites/arrow-down.svg'
import {$eventBus} from '../../../main';
export default {
    props:{
        bullets: Number,
        current: Number
    },
    data: function(){
        return{
            currentBullet: 0,
            showBullets: 0,
            show: true,
        }
    },
    watch: {
        current(value){
           this.currentBullet = value;
        //    if(this.$router.currentRoute.name == 'branchFlow'){
        //        if(value == this.showBullets){
        //            this.show=false;
        //        }else{
        //            this.show=true;
        //        }
        //    }
        }
    },
    mounted(){
        // if(this.$router.currentRoute.name == 'branchFlow'){
        //     var unwatch = this.$watch('bullets', function(value){
        //         if(value>0){
        //            this.showBullets = this.bullets-1;
        //         }
        //     }, {immediate: true});
        //     if(this.showBullets>0){
        //         unwatch();
        //     }
        // }
    },
    methods:{
        clickPrev(){
            if(this.current-1>=0){
                this.currentBullet--;
                this.$emit('moveTo', this.currentBullet);
            }
        },
        clickNext(){
            if(this.current+1 < this.bullets){
                this.currentBullet++;
                this.$emit('moveTo', this.currentBullet);
                $eventBus.$emit('nextSlide');
            }
        },
        moveTo(index){
            $('.bullet-item').removeClass('active');
            $('.bullet-item').eq(this.current).addClass('active');
            this.$emit('moveTo', index);
        }
    },
}
</script>
<style lang='scss' src='./style.scss' scoped></style>
