@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'config'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Setting')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management pt-4">
        <div class="row">
            <div class="col-12">
                <div class="dashboard-status config-section">
                    <div class="row">
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('Clear Application Cache')}}</p>
                                            <small>{{__('From here you can clear your application cache . or also from the command line you can run the command "php artisan cache:clear"')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_CACHE)}}" class="theme-btn btn-success">{{__('Cache Clear')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('Clear Application Config')}}</p>
                                            <small>{{__('From here you can clear your application all configuration . or also from the command line you can run the command "php artisan config:clear"')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_CONFIG)}}" class="theme-btn  btn-success">{{__('Config Clear')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('Clear Application View / Route')}}</p>
                                            <small>{{__('From here you can clear your application view and route . or also from the command line you can run the command "php artisan view:clear", "php artisan route:clear"')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_VIEW)}}" class="theme-btn  btn-success">{{__('View Clear')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('Run Migration')}}</p>
                                            <small>{{__('For the new migration you can click the button to migrate or run the command "php artisan migrate"')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_MIGRATE)}}" class="theme-btn  btn-success">{{__('Migrate')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('Adjust Trade Fees Settings')}}</p>
                                            <small>{{__('No need to click this button, but if missed initial fees setting , you can adjust trade fess setting by clicking this button')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_TRADE_FEES)}}" class="theme-btn  btn-success">{{__('Trade Fees')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('Adjust Coin Pair Settings')}}</p>
                                            <small>{{__('No need to click this button, but if missed initial coin pair setting , you can adjust the coin pair setting by clicking this button')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_COIN_PAIR)}}" class="theme-btn  btn-success">{{__('Coin Pair Setup')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('ERC20 Token Deposit')}}</p>
                                            <small>{{__('This command should run in your system every five minutes. It helps to deposit custom token. So try to run it every five minutes through scheduler. Otherwise you will miss user deposit')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_ERC20_TOKEN_DEPOSIT)}}" class="theme-btn  btn-success">{{__('Run Command')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('BEP20 Token Deposit')}}</p>
                                            <small>{{__('This command should run in your system every five minutes. It helps to deposit custom token. So try to run it every five minutes through scheduler. Otherwise you will miss user deposit')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_TOKEN_DEPOSIT)}}" class="theme-btn  btn-success">{{__('Run Command')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-4 col-md-6 col-12 mb-4">
                            <div class="card status-card">
                                <div class="card-body py-0">
                                    <div class="status-card-inner">
                                        <div class="content">
                                            <p>{{__('Adjust Custom Token Deposit')}}</p>
                                            <small>{{__('This command should run in your system every ten minutes once. It also helps to deposit custom token. So try to run it every ten minutes through scheduler. Otherwise you will miss user deposit')}}</small>
                                            <a href="{{route('adminRunCommand',COMMAND_TYPE_ADJUST_TOKEN_DEPOSIT)}}" class="theme-btn  btn-success">{{__('Run Command')}}</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
@endsection
