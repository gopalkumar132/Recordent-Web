  <script src="{{config('app.url')}}front-ib/js/main.js"></script>   
  <script async src="https://www.googletagmanager.com/gtag/js?id={{setting('site.google_analytics_tracking_id')}}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '{{setting('site.google_analytics_tracking_id')}}');
</script>  

<script type="text/javascript" src="{{config('app.url')}}front-ib/js/voyager-assets"></script> 
<script type="text/javascript" src="{{config('app.url')}}front-ib/js/inputmask.js"></script>
<!-- END FOOTER -->