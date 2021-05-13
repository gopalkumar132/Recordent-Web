<style type="text/css">
    .profile-body
    {
         position: relative;
        margin-left: 70px;
        padding-top: -20px;
    }
</style>

<nav class="navbar navbar-default navbar-fixed-top navbar-top">

    <div class="container-fluid">

        <div class="navbar-header">

            <div class="d-flex align-items-center">

                <button id="clickhamber" class="hamburger btn-link ">

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

            {{--@section('breadcrumbs')

            <ol class="breadcrumb hidden-xs">

                @php

                $segments = array_filter(explode('/', str_replace(route('voyager.dashboard'), '', Request::url())));

                $url = route('voyager.dashboard');

                @endphp

                @if(count($segments) == 0)

                    <li class="active"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</li>

                @else

                    <li class="active">

                        <a href="{{ route('voyager.dashboard')}}"><i class="voyager-boat"></i> {{ __('voyager::generic.dashboard') }}</a>

                    </li>

                    @foreach ($segments as $segment)

                        @php

                        $url .= '/'.$segment;

                        @endphp

                        @if ($loop->last)

                            <li>{{ ucfirst($segment) }}</li>

                        @else

                            <li>

                                <a href="{{ $url }}">{{ ucfirst($segment) }}</a>

                            </li>

                        @endif

                    @endforeach

                @endif

            </ol>--}}

            @show

        </div>

        <ul class="nav navbar-nav @if (config('voyager.multilingual.rtl')) navbar-left @else navbar-right @endif">

            @if(Auth::user()->role->name!='admin' && Auth::user()->role->name!='Sub Admin')
            <li class="user_membership" style="min-width: 100px;">{{Auth::guest() ? '': (Auth::user()->user_pricing_plan == NULL ? '' : HomeHelper::getUserMembershipPlanName())}}@if(HomeHelper::isPlanExpired())<span style="font-size: 12px; color: red; position: absolute; display: inline-block; margin-left: -52px; margin-top: 18px;">Expired</small>@endif</li>
            @endif

             <li class="notify-count">

                @if(Auth::user()->role->name=='admin' || Auth::user()->role->name=='Sub Admin')

                    <a href="{{route('admin.notification-list')}}">

                        <i class="fa fa-bell" aria-hidden="true"></i>

                        @php

                            $notificationCount=General::getAdminNotificationCount();

                        @endphp

                        @if($notificationCount>0)<span class="count-number">{{$notificationCount}}</span>@endif

                    </a>

                @endif

             </li>

            <li class="dropdown profile">

                <a href="#" class="dropdown-toggle text-right" data-toggle="dropdown" role="button"

                   aria-expanded="false"><img src="{{ $user_avatar }}" class="profile-img"> <span

                            class="caret"></span></a>

                <ul class="dropdown-menu dropdown-menu-animated">

                    <li class="profile-img">

                        <img src="{{ $user_avatar }}" class="profile-img">

                        <div class="profile-body">

                            <h5 style="text-align: left;">{{ app('VoyagerAuth')->user()->business_name }}</h5>

                            <h6 style="text-align: left;">{{ app('VoyagerAuth')->user()->email }}</h6>

                        </div>

                    </li>

                    <li class="divider"></li>

                    <?php $nav_items = config('voyager.dashboard.navbar_items'); ?>

                    @if(is_array($nav_items) && !empty($nav_items))

                    @foreach($nav_items as $name => $item)

                    <li {!! isset($item['classes']) && !empty($item['classes']) ? 'class="'.$item['classes'].'"' : '' !!}>

                        @if(isset($item['route']) && $item['route'] == 'voyager.logout')

                        <form action="{{ route('voyager.logout') }}" method="POST">

                            {{ csrf_field() }}

                            <button type="submit" class="btn btn-danger btn-block">

                                @if(isset($item['icon_class']) && !empty($item['icon_class']))

                                <i class="{!! $item['icon_class'] !!}"></i>

                                @endif

                                {{__($name)}}

                            </button>

                        </form>

                        @else

                        <a href="{{ isset($item['route']) && Route::has($item['route']) ? route($item['route']) : (isset($item['route']) ? $item['route'] : '#') }}" {!! isset($item['target_blank']) && $item['target_blank'] ? 'target="_blank"' : '' !!}>

                            @if(isset($item['icon_class']) && !empty($item['icon_class']))

                            <i class="{!! $item['icon_class'] !!}"></i>

                            @endif

                            @if($name=="Change password")

                            @if(empty(Auth::user()->password))

                            Update Password

                            @else

                            {{__($name)}}

                            @endif

                            @else

                            {{__($name)}}

                            @endif

                        </a>

                        @endif

                    </li>

                    @endforeach

                    @endif

                </ul>

            </li>

        </ul>

    </div>

</nav>

