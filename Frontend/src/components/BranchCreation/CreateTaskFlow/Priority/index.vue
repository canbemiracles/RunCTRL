<template>

    <div class="d-flex flex-column mb-4" >
        <div class="row">
            <h3 class="title-header col-md-12">Task Priority</h3>
        </div>
        <div class="row no-gutters wrap">
            <div class="stars-wrap">
                <h6 class="title-stars">Send problem report</h6>
                <div class="stars">
                    <div class="starss">
                        <div class="star star-1 star__active-star-1" @click="$emit('input', 1)">
                            <svg class="icon"><use xlink:href="images/icons-sprite.svg#star"></use></svg>
                            <p class="title-star">Branch Manager</p>
                        </div>
                        <div class="star star-2"
                            :class="{'star__active-star-2': value === 2 || value === 3}"
                            @click="$emit('input', 2)">
                            <svg class="icon"><use xlink:href="images/icons-sprite.svg#star"></use></svg>
                            <p class="title-star">Supervisor</p>
                        </div>
                        <div class="star star-3"
                            :class="{'star__active-star-3': value === 3}"
                            @click="$emit('input', 3)">
                            <svg class="icon"><use xlink:href="images/icons-sprite.svg#star"></use></svg>
                            <p class="title-star">Company Owner</p>
                        </div>
                        <ul class="lines-star">
                            <li class="star-line star-line-1"><span class="line-plus line-plus-1">+</span></li>
                            <li class="star-line star-line-2"><span class="line-plus line-plus-2">+</span></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
import 'images/sprites/stars/star.svg'
import 'images/sprites/stars/star-line-1.svg'
import 'images/sprites/stars/star-line-2.svg'
export default {
  props: {
    value: {
      type: Number,
      required: true
    }
  }
}
</script>

<style lang='scss' src='./style.scss' scoped></style>