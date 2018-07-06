import { setTimeout } from "timers";

const autocomplete = {
    data: function() {
        return {
          open: false,
          current: 0,
          selection: "",
          apiScroll: undefined,
        };
      },
    
      computed: {
        //Filtering the suggestion based on the input
        matches() {
          let that = this;
          return this.suggestions.filter(function(obg) {
            return (
              obg[that.fieldName]
                .toLowerCase()
                .indexOf(that.selection.toLowerCase()) >= 0
            );
          });
        },
    
        //The flag
        openSuggestion() {
          return this.matches.length != 0 && this.open === true;
        }
      },
      mounted() {
        let that = this;
        var container = $(this.$refs.autocomplete);
        $(document).mouseup(function(e) {
          if (container.has(e.target).length === 0) {
            that.open = false;
          }
        });
      },
      methods: {
        openDrop() {
          console.log('openDrop');
          if(this.disabled){
            return false;
          }
          if (this.open == true) {
            this.open = false;
            
          }else{
            this.change();
            let scrollelem = $(this.$refs.scroll).jScrollPane({
              autoReinitialise: true
            });
            this.apiScroll = scrollelem.data("jsp");
          }
        },
        esc(){
          if (this.open == true) {
            this.open = false;
          }
        },
        //When up pressed while suggestions are open
        up() {
          if (this.current > 0) {
            this.current--;
            this.count--;
            if (this.count < 0) {
              this.apiScroll.scrollToY(this.pixelsToPointerTop());
              this.count++;
            }
          }else if (this.current==0){
            this.current = this.matches.length-1;
            this.apiScroll.scrollToY(this.pixelsToPointerTop());
          }
        },
    
        //When up pressed while suggestions are open
        down() {
          if (this.current < this.matches.length - 1) {
            this.current++;
            this.count++;
            if (this.count >= this.maxCount) {
              this.apiScroll.scrollToY(
                this.pixelsToPointerTop() -
                  this.viewport().bottom +
                  this.pointerHeight()
              );
              this.count--;
            }
          }else if (this.current==this.matches.length - 1){
              this.current = 0;
              this.apiScroll.scrollToY(this.pixelsToPointerTop());
          }
        },
    
        //For highlighting element
        isActive(index) {
          return index === this.current;
        },
    
        //When the user changes input
        change() {
          if (this.open == false) {
            this.open = true;
            this.current = 0;
          }
          let that = this;
          setTimeout(function() {
            let listHeight = $(that.$refs.dropdownMenu).height();
            $(that.$refs.scroll).height(listHeight);
            that.$emit('changeHeightDrop');
            if (that.apiScroll) {
              that.apiScroll.reinitialise();
            }
          }, 50);
        },
      }
}
export default autocomplete;