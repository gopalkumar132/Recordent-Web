



  <!-- Copyright Section -->

  <section class="copyright py-4 text-center text-white">

    <div class="container">
		{!!menu('front-footer-menu','layouts_front.footer-menu')!!}
      <small>Copyright &copy; {{setting('site.title')}} 2019</small>

    </div>

  </section>





<!-- Bootstrap core JavaScript -->

  <script src="{{asset('front/vendor/jquery/jquery.min.js')}}"></script>

  <script src="{{asset('front/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>



  <!-- Plugin JavaScript -->

  <script src="{{asset('front/vendor/jquery-easing/jquery.easing.min.js')}}"></script>



  <!-- Contact Form JavaScript -->

  <script src="{{asset('front/js/jqBootstrapValidation.js')}}"></script>

  <script src="{{asset('front/js/contact_me.js')}}"></script>



  <!-- Custom scripts for this template -->

  <script src="{{asset('front/js/freelancer.min.js')}}"></script>
<script>
    $(document).ready(function(){
        if ( 'serviceWorker' in navigator ) {
        window.addEventListener( 'load', function () {
            navigator.serviceWorker.register( "{{config('app.url')}}sw.js" ).then( function ( registration ) {
                // Registration was successful
                console.log( 'ServiceWorker registration successful with scope: ', registration.scope );
            }, function ( err ) {
                // registration failed :(
                console.log( 'ServiceWorker registration failed: ', err );
            } );
        } );
    }
    });
</script>
<script>

/*$(document).ready(function(){

	if ('serviceWorker' in navigator) {

		  window.addEventListener('load', function() {

			navigator.serviceWorker.register("{{config('app.url')}}sw.js").then(function(registration) {

			  // Registration was successful

			  console.log('ServiceWorker registration successful with scope: ', registration.scope);

			}, function(err) {

			  // registration failed :(

			  console.log('ServiceWorker registration failed: ', err);

			});

		  });

		}



});

	

window.ga=window.ga||function(){(ga.q=ga.q||[]).push(arguments)};ga.l=+new Date;

ga('create', '{{setting('site.google_analytics_tracking_id')}}', 'auto');

ga('send', 'pageview');

*/



</script>

<script async src='https://www.google-analytics.com/analytics.js'></script>



