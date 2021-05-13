<footer class="app-footer">
   {{-- <div class="site-footer-right">
        @if (rand(1,100) == 100)
            <i class="voyager-rum-1"></i> {{ __('voyager::theme.footer_copyright2') }}
        @else
            {!! __('voyager::theme.footer_copyright') !!} <a href="http://thecontrolgroup.com" target="_blank">The Control Group</a>
        @endif
        @php $version = Voyager::getVersion(); @endphp
        @if (!empty($version))
            - {{ $version }}
        @endif
    
    </div>  --}}

    @if (!Auth::user()->hasRole('admin'))
        @if( config('app.env') == "production" || config('app.url') == "https://www.stage.recordent.com/" ) 
            @include('partials.google-analytics')
            @include('partials.hot-jar-tracking')  
        @endif    
    @endif
</footer>
