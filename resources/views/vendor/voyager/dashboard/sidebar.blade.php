<div class="side-menu sidebar-inverse">

    <nav class="navbar navbar-default" role="navigation">

        <div class="side-menu-container">

            <div class="navbar-header" style="display:none">

                <a class="navbar-brand" href="{{ route('voyager.dashboard') }}">

                    <div class="logo-icon-container">

                        <?php 

                            $admin_logo_img = Voyager::setting('admin.icon_image', '');

                            $admin_small_img = Voyager::setting('admin.small_logo', '');

                            ?>

                        

                            <img src="{{ Voyager::image($admin_logo_img) }}" alt="Logo Icon">

                        

                    </div>

                    

                </a>

            </div><!-- .navbar-header -->



            <div class="panel widget center bgimage"

                 style="background-image:url({{ Voyager::image( Voyager::setting('admin.bg_image'), voyager_asset('images/bg.jpg') ) }}); background-size: cover; background-position: 0px;">

                <div class="dimmer"></div>

                <div class="panel-content">

                    <img src="{{ $user_avatar }}" class="avatar" alt="{{ app('VoyagerAuth')->user()->name }} avatar">

                    <h4>{{ ucwords(app('VoyagerAuth')->user()->name) }}</h4>

                    <p>{{ app('VoyagerAuth')->user()->email }}</p>



                    <a href="{{ route('voyager.profile') }}" class="btn btn-primary">{{ __('voyager::generic.profile') }}</a>

                    <div style="clear:both"></div>

                </div>

            </div>



        </div>

        <div id="adminmenu">

            <admin-menu :items="{{ menu('admin', '_json') }}"></admin-menu>

        </div>

    </nav>

</div>

<style>
  .ps>.ps__scrollbar-y-rail>.ps__scrollbar-y {
    width: 13px !important;}
</style>

<!-- <script>
    $(document).ready(function(){
        $('.app-container').addClass('expanded');
   });
</script> -->



