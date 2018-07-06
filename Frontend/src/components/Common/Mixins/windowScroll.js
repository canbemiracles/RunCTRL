import {mapGetters, mapMutations} from 'vuex';
const windowScroll={
    data: function(){
        return{
            currentSlide: 0,
            total: 0,
        }
    },
    computed:{
        ...mapGetters(['validate','validateArr'])
    },
    created(){
        this.onScrollEventListener();
    },
    watch:{
        validate: {
            handler: function (value){
                if(value){
                    if(!$('.slide-section').hasClass('slidescroll')){
                        $('.slide-section').addClass('slidescroll'); 
                    }
                }else{
                    $('.slide-section').removeClass('slidescroll');  
                }
            },
            immediate: true
        },
        currentSlide(value){
            if(value!=this.total-1){
                if(!this.validateArr[value+1]){
                    this.setValidate(false);
                }else{
                    this.setValidate(true); 
                }
            }else{
                $('.slide-section').removeClass('slidescroll'); 
            }
        }
    },
    mounted(){
        this.$nextTick(function () {
            this.initParallaxScroll();
        });
    },
    beforeDestroy(){
       this.offScrollEventListener();
    },
    methods: {
        ...mapMutations(['setValidate', 'initValidateArr']),        
        initParallaxScroll(reinit=false){
            if(!reinit){
               this.setValidate(false); 
            }
            console.log(this.validate);
            let validateArr = [];
            var sections=$('.slide-section-container');
            var total = this.total = sections.length;
            $.each(sections, function(i){
                $(this).css({
                        position: "fixed",
                        zIndex: total-i,
                        marginTop: '90px',
                    });
                    if(!reinit){
                        if(i==0){
                            validateArr.push(true); 
                        }else{
                            validateArr.push(false); 
                        }
                    }
            });
            if(!reinit){
                this.initValidateArr(validateArr);
            }
            sections.first().css({
                    position: "relative",
                    marginTop: '0' 
            }).addClass('slide-shadow');
        },
        reInitScroll(){
            this.initParallaxScroll(true);
        },
        parallaxScroll(){
                var sections=$('.slide-section-container');
                let heightScreen=sections.eq(this.currentSlide).outerHeight();
                let totalHeight=0;
                for(let i=0; i<=this.currentSlide; i++){
                    totalHeight+=sections.eq(i).outerHeight();
                }
                if($(document).scrollTop()>=totalHeight){
                    if(this.currentSlide!=this.total-1){
                        this.currentSlide++;
                        this.moveDown(this.currentSlide); 
                    }else{
                        sections.eq(this.currentSlide-1).removeClass('slide-shadow');
                        if($('.slide-section').hasClass('slidescroll')){
                            $('.slide-section').removeClass('slidescroll'); 
                        } 
                    }
                };
                
                if($(document).scrollTop()<totalHeight - heightScreen){
                    if(this.currentSlide!=0){
                        if(this.currentSlide==this.total-1){
                            $('.slide-section').addClass('slidescroll'); 
                        }
                        this.moveUp(this.currentSlide-1);
                        let heightPrev = sections.eq(this.currentSlide-1).outerHeight();
                        if($(document).scrollTop()<=totalHeight-heightScreen-heightPrev){
                            this.currentSlide--;
                        }
                        if($(document).scrollTop()==0){
                            this.currentSlide=0;
                        }
                    }
                };
                if($(document).scrollTop() == totalHeight - heightScreen){
                    this.moveDown(this.currentSlide); 
                    if(this.currentSlide==this.total-1){
                        $('.slide-section').removeClass('slidescroll'); 
                    }
                }
        },
        moveUp(ind){
            var sections=$('.slide-section-container');  
            sections.eq(ind).addClass('slide-shadow');
            sections.eq(ind+1).css({
                position: "fixed",
                marginTop: '90px' 
            }).removeClass('slide-shadow');
        },
        moveDown(ind){
            var sections=$('.slide-section-container');  
            sections.removeClass('slide-shadow')
            sections.eq(ind).css({
                position: "relative",
                marginTop: '0' 
            }).addClass('slide-shadow');
        },
        moveSlide(index){
                if(!this.validateArr[index]){
                    return false;
                }
                if(index<this.currentSlide){
                    this.moveUpTo(index);
                }else{
                    this.moveDownTo(index);
                }
        },
        moveUpTo(index){
            var sections=$('.slide-section-container');
            let moveSections = sections.slice(index+1, this.currentSlide+1);
            sections.removeClass('slide-shadow');
            sections.eq(index).addClass('slide-shadow');
            $("html,body").animate({
                scrollTop: sections.eq(index).offset().top - 90}, 
                800, 
                function(){
                    $.each(moveSections, function(i){
                        $(this).css({
                            position: "fixed",
                            marginTop: '90px',
                        });
                    });
                }
            );
        },
        moveDownTo(index){
            var sections=$('.slide-section-container');
            let moveSections = sections.slice(this.currentSlide, index);
            sections.removeClass('slide-shadow');
            sections.eq(index-1).addClass('slide-shadow');
            $.each(moveSections, function(i){
                $(this).css({
                    position: "relative",
                    marginTop: '0',
                });
            });
            $("html,body").animate(
                {scrollTop: sections.eq(index-1).offset().top + sections.eq(index-1).outerHeight() - 90},
                800
            );
            
        },
        offScrollEventListener(){
            $(window).off('scroll', this.parallaxScroll);
        },
        onScrollEventListener(){
            $(window).on('scroll', this.parallaxScroll);
        },  
    }
  }

  export default windowScroll;