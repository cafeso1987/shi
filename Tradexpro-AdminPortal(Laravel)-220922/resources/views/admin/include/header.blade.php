<div class="top-bar">
    <div class="container-fluid">
        <div class="row align-items-center justify-content-between">
            <div class="col-xl-1 col-md-2 col-3 top-bar-logo top-bar-logo-hide">
                <div class="logo">
                    <a href="{{route('adminDashboard')}}"><img src="{{show_image(Auth::user()->id,'logo')}}" class="img-fluid logo-large" alt=""></a>
                    <a href="{{route('adminDashboard')}}"><img src="{{show_image(Auth::user()->id,'logo')}}" class="img-fluid logo-small" alt=""></a>
                </div>
            </div>
            <div class="col-xl-1 col-md-2 col-3">
                <div class="menu-bars">
                    <img src="{{asset('assets/admin/images/sidebar-icons/menu.svg')}}" class="img-fluid" alt="">
                </div>
            </div>
            <div class="col-xl-10 col-md-8 col-6">
                <div class="top-bar-right">
                    <ul>
                        <li>
                            <div class="btn-group profile-dropdown">
                                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <span class="cp-user-avater">
                                        <span class="cp-user-img">
                                            <img src="{{show_image(Auth::user()->id,'user')}}" class="img-fluid" alt="">
                                        </span>
                                        <span class="name">{{Auth::user()->first_name.' '.Auth::user()->last_name}}</span>
                                    </span>
                                </button>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <span class="big-user-thumb">
                                        <img src="{{show_image(Auth::user()->id,'user')}}" class="img-fluid" alt="">
                                    </span>
                                    <div class="user-name">
                                        <p>{{Auth::user()->first_name.' '.Auth::user()->last_name}}</p>
                                    </div>
                                    <button class="dropdown-item" type="button"><a href="{{route('adminProfile')}}"><i class="fa fa-user-circle-o"></i> {{__('Profile')}}</a></button>
                                    <button class="dropdown-item" type="button"><a href="{{route('logOut')}}"><i class="fa fa-sign-out"></i> {{__('Logout')}}</a></button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
