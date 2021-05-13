$( document ).ready(function() {
    $('.how-recordent-work').owlCarousel({
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
    $('.video-section-slider').owlCarousel({
        loop: true,
        margin:0,       
        items:1,
        dots:true,
        nav:false,
        autoplay:true,
        autoplayTimeout:8000,
        autoplayHoverPause:true,
        responsiveClass: true,
        responsive:{       
            220:{
                items:1,
                margin:0,                
            },
        }
    });    
   
});


//header fixed on scroll
/*$(window).scroll(function(){
    if ($(window).scrollTop() >= 100) {
        $('.fixed-this').addClass('fixed-header');        
    }
    else {
        $('.fixed-this').removeClass('fixed-header');        
    }
})*/

$(".scroll-to-video").click(function(e)   {
    e.preventDefault();
    $('html, body').animate({
        scrollTop: $("#watch-video").offset().top - 100
    }, 2000);
});

