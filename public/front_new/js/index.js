$( document ).ready(function() {
    $('.how-recordent-work').owlCarousel({
        loop: true,
        margin:0,       
        items:1,
        dots:false,
        nav:true,
        autoplay:true,
        autoplayTimeout:11000,
        autoplayHoverPause:true,
        responsiveClass: true,
        responsive:{       
            220:{
                items:1,
                margin:0,                
            },
        }
    });
    $('.video-section-slider').owlCarousel({
        loop: true,
        margin:0,       
        items:1,
        dots:true,
        nav:false,
        autoplay:true,
        autoplayTimeout:4000,
        autoplayHoverPause:true,
        responsiveClass: true,
        responsive:{       
            220:{
                items:1,
                margin:0,                
            },
        }
    });


    $('.bus-indi-slider').owlCarousel({
        loop:true,
        margin:0,       
        items:1,
        dots:false,
        nav:false,
        autoplay:true,
        autoplayTimeout:2000,
        autoplayHoverPause:true,
        responsiveClass: true,
        responsive:{       
            220:{
                items:1,
                margin:0,                
            },
        }
    });

    $('.report-step-slider').owlCarousel({
        loop: false,
        margin:0,       
        items:1,
        dots:false,
        nav:true,
        autoplay:true,
        autoplayTimeout:2000,
        autoplayHoverPause:true,
        responsiveClass: true,
        responsive:{       
            220:{
                items:1,
                margin:0,                
            },
        }
    });


    $('.journey-points-slider').owlCarousel({
        loop:true,
        margin:0,       
        items:1,
        dots:true,
        // autoHeight:true,
        nav:true,
        autoplay:false,
        autoplayTimeout:2000,
        autoplayHoverPause:true,
        responsiveClass: true,
        responsive:{       
            220:{
                items:1,
                margin:0,                
            },
        }
    });
    
    $('.partner-slider').owlCarousel({
        loop:true,
        margin:30,       
        items:4,
        dots:false,        
        nav:false,
        autoplay:true,
        autoplayTimeout:2000,
        autoplayHoverPause:true,
        responsiveClass: true,
        responsive:{       
            1200:{
                items:4
            },
            992:{
                items:3
            },
            767:{
                items:2
            },
            220:{
                items:1,
                margin:0,                
            },
        }
    });
});


//header fixed on scroll
$(window).scroll(function(){
    if ($(window).scrollTop() >= 100) {
        $('.fixed-this').addClass('fixed-header');        
    }
    else {
        $('.fixed-this').removeClass('fixed-header');        
    }
})

$(".scroll-to-video").click(function(e)   {
    e.preventDefault();
    $('html, body').animate({
        scrollTop: $("#watch-video").offset().top - 100
    }, 2000);
});

$(document).on("click", function(event){
        var $trigger = $(".navbar.navbar-expand-lg.p-0");
        if($trigger !== event.target && !$trigger.has(event.target).length){
            $trigger.find(".navbar-collapse").removeClass("show");
            
        }            
    });
