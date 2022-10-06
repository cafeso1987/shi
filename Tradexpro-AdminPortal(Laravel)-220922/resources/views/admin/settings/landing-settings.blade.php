@extends('admin.master',['menu'=>'setting','sub_menu'=>'landing'])
@section('title', 'Landing Setting')
@section('style')
@endsection
@section('content')
    <!-- coin-area start -->
    <div class="landing-page-area user-management">
        <div class="page-wraper section-padding">
            <div class="row no-gutters">
                <div class="col-12 col-lg-3 col-xl-2">
                    <ul class="nav nav-pills nav-pill-three landing-tab user-management-nav" id="tab" role="tablist">
                        <li>
                            <a class="nav-link active" @if(isset($tab) && $tab=='hero')class="active" @endif data-toggle="tab"
                            href="#hero">{{__('Header Setting')}}</a>
                        </li>
                        <li>
                            <a class="nav-link" @if(isset($tab) && $tab=='features')class="active" @endif data-toggle="tab"
                            href="#features">{{__('Landing Trade')}}</a>
                        </li>
                        <li>
                            <a class="nav-link" @if(isset($tab) && $tab=='contact')class="active" @endif data-toggle="tab"
                                href="#contact">{{__('Customization')}}</a>
                        </li>
                        <li>
                            <a class="nav-link" @if(isset($tab) && $tab=='links')class="active" @endif data-toggle="tab"
                                href="#links">{{__('Download Link')}}</a>
                        </li>
                    </ul>
                </div>
                <div class="col-12 col-lg-9 col-xl-10">
                    <div class="single-tab section-height">
                        <div class="section-body ">
                            <div class="tab-content">
                                <!-- genarel-setting start-->
                                <div class="tab-pane fade  @if(isset($tab) && $tab=='hero')show active @endif " id="hero" role="tabpanel" aria-labelledby="header-setting-tab">
                                    @include('admin.settings.landing.header')
                                </div>
                                <div class="tab-pane fade  @if(isset($tab) && $tab=='contact')show active @endif "
                                        id="contact" role="tabpanel" aria-labelledby="header-setting-tab">
                                    @include('admin.settings.landing.customization')
                                </div>
                                <div class="tab-pane fade  @if(isset($tab) && $tab=='features')show active @endif "
                                        id="features" role="tabpanel" aria-labelledby="header-setting-tab">
                                    @include('admin.settings.landing.trade')
                                </div>
                                <div class="tab-pane fade  @if(isset($tab) && $tab=='links')show active @endif "
                                        id="links" role="tabpanel" aria-labelledby="header-setting-tab">
                                    @include('admin.settings.landing.links')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

@endsection
@section('script')
@endsection
