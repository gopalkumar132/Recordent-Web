@php
$dimmers = Voyager::dimmers();
$count = $dimmers->count();
$classes = [
    'col-xs-12',
    'col-sm-'.($count >= 2 ? '6' : '12'),
    'col-md-'.($count >= 2 ? '3 ' : ($count >= 2 ? '3' : '12')),
];
$class = implode(' ', $classes);
$prefix = "<div class='dimmers-boxes'>";
$surfix = '</div>';
@endphp
@if ($dimmers->any())
<div class="container-fluid custom-dimmers d-flex flex-wrap">
    {!! $prefix.$dimmers->setSeparator($surfix.$prefix)->display().$surfix !!}
</div>
@endif
