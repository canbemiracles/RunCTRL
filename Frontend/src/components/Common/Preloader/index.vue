<template>
    <div class="loader-wrap" v-if="show">
        <div class="loader" id="loader"></div>
    </div>
</template>
<script>
export default {
    props:{
        show:{
            type: Boolean,
            default: true
        },
    },
    data(){
        return {

        }
    }
}
</script>
<style lang='scss' scoped>
.loader-wrap{
    display: flex;
    align-items: center;
    justify-content: center;
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(#ccc, .5);
    z-index: 20;
}
.loader{
  width: 100px;
  height: 100px;
  border-radius: 100%;
  position: relative;
  margin: 0 auto;
}
#loader:before, #loader:after{
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  border-radius: 100%;
  border: 10px solid transparent;
  border-top-color: darken($blue, 5);
}

#loader:before{
  z-index: 100;
  animation: spin 1s infinite;
}

#loader:after{
    z-index: 21;
    border: 10px solid #ccc;
}
@keyframes spin{
  0%{
    -webkit-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }

  100%{
    -webkit-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
</style>