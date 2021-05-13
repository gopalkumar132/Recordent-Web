<div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('{{ $image }}');">

    <div class="dimmer"></div>

    <div class="panel-content">

        @if (isset($icon))<i class='{{ $icon }}'></i>@endif

        <h4 style="font-size:17px;">{!! $title !!}</h4>

        <p>{!! $text !!}</p>

    </div>

</div>



<!--<div>-->

<!--	<p>@if($helper != ''){!! $helper !!}@endif &nbsp;</p>-->

<!--</div>-->



