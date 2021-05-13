 <!-- BEGIN HEADER -->

@php
if(!empty(session()->get('individual_client_report_type'))){
  $url = config('app.url').'business/';
}else{
  $url = config('app.url').'individual/';
}
@endphp
<nav class="navbar navbar-default navbar-fixed-top navbar-top">
      <div class="container-fluid">
        <div class="navbar-header">
            <div class="d-flex align-items-center">
                <button class="hamburger btn-link">
                    <span class="hamburger-inner"></span>
                </button>
                <div class="admin-logo-for-s">
                    <?php 
                        $admin_logo_img = Voyager::setting('admin.icon_image', '');
                        $admin_small_img = Voyager::setting('admin.small_logo', '');
                    ?>
                    <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">
                    <a class="ful-link" href="{{ route('voyager.dashboard') }}"></a>
                </div>
            </div>
          <!--<button class="hamburger btn-link"> <span class="hamburger-inner"></span> </button>-->
        </div>
        @if(!empty(session()->get('individual_client_id')))
        <ul class="nav navbar-nav  navbar-right ">
          <li class="dropdown profile">
            <a href="javascript:void(0)" class="dropdown-toggle text-right" data-toggle="dropdown" role="button" aria-expanded="false"><img src="{{config('app.url')}}front-ib/images/default.png" class="profile-img"> <span class="caret"></span></a>
            <ul class="dropdown-menu dropdown-menu-animated">
              <li class="profile-img"> <img src="{{config('app.url')}}front-ib/images/default.png" class="profile-img">
                <div class="profile-body">
                  <h5>
                    @if(!empty(session()->get('individual_client_email')))
                      {{Session::get('individual_client_email')}}
                    @else
                      {{Session::get('individual_client_mobile_number')}}
                    @endif
                  </h5>
                </div>
              </li>

              <li class="divider"></li>
              <li class="class-full-of-rum">
                <a href="{{$url.'profile'}}">
                  <i class="voyager-person"></i>
                   Profile
                </a>
              </li>

              <li>
                <form action="{{route('individual.logout')}}" method="POST">
                 {{ csrf_field() }}
                  <input type="hidden" name="" value="">
                  <button type="submit" class="btn btn-danger btn-block"> <i class="voyager-power"></i> Logout </button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
        @endif
      </div>
    </nav>
<!-- END HEADER --> 