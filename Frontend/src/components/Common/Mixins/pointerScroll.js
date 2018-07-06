const pointerScroll={
    data(){
      return {
        count: 0
      }
    },
    computed:{
        maxCount: function(){
            return Math.round(this.viewport().bottom / this.pointerHeight());
        }
    },
    methods: {
      /**
       * The distance in pixels from the top of the dropdown
       * list to the top of the current pointer element.
       * @returns {number}
       */
      pixelsToPointerTop() {
        let pixelsToPointerTop = 0
        for (let i = 0; i < this.current; i++) {
          pixelsToPointerTop += this.$refs.dropdownMenu.children[i].offsetHeight
        }
        return pixelsToPointerTop
      },
  
      /**
       * The distance in pixels from the top of the dropdown
       * list to the bottom of the current pointer element.
       * @returns {*}
       */
      pixelsToPointerBottom() {
        return this.pixelsToPointerTop() + this.pointerHeight()
      },
  
      /**
       * The offsetHeight of the current pointer element.
       * @returns {number}
       */
      pointerHeight() {
        let element = this.$refs.dropdownMenu.children[this.current]
        return element ? element.offsetHeight : 0
      },
  
      /**
       * The currently viewable portion of the dropdownMenu.
       * @returns {{top: (string|*|number), bottom: *}}
       */
      viewport() {
        return {
          top: this.$refs.scroll.scrollTop + this.$refs.scroll.offsetHeight,
          bottom: this.$refs.scroll.offsetHeight + this.$refs.scroll.scrollTop
        }
      }
    }
  }

  export default pointerScroll;