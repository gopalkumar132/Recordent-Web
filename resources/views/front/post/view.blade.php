@extends('layouts_front_new.master')
@section('content')
<section class="section-padding angle-bg " id="Problem">
  <div class="container">    
    <div class="row">
      <div class="col-xs-12">
      	
      	<p>{{$post->title}}</p>
      	@if($post->image)
      		<img src="{{asset('storage/'.$post->image)}}">
      	@endif
      	<p>{{$post->excerpt}}</p>
		{!!$post->body!!}
      </div>
    </div>
  </div>
</section>
@endsection