@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'general'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Settings')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management pt-4">
        <div class="row no-gutters">
            <div class="col-12 col-lg-3 col-xl-2">
                <ul class="nav user-management-nav mb-3" id="pills-tab" role="tablist">
                    @if(isset($tab) && ($tab=='api_config'))
                    @else
                        <li class="nav-item">
                            <a class="@if(isset($tab) && $tab=='general') active @endif nav-link " id="pills-user-tab"
                               data-toggle="pill" data-controls="general" href="#general" role="tab"
                               aria-controls="pills-user" aria-selected="true">
                                <span>{{__('General Settings')}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if(isset($tab) && $tab=='capcha') active @endif nav-link " id="pills-user-tab"
                               data-toggle="pill" data-controls="capcha" href="#capcha" role="tab"
                               aria-controls="pills-user" aria-selected="true">
                                <span>{{__('Re-Capcha Settings')}}</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if(isset($tab) && $tab=='email') active @endif nav-link " id="pills-add-user-tab"
                               data-toggle="pill" data-controls="email" href="#email" role="tab"
                               aria-controls="pills-add-user" aria-selected="true">
                                <span>{{__('Email Settings')}} </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if(isset($tab) && $tab=='sms') active @endif nav-link " id="pills-sms-tab"
                               data-toggle="pill" data-controls="sms" href="#sms" role="tab" aria-controls="pills-sms"
                               aria-selected="true">
                                <span>{{__('Twillo Settings')}} </span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="@if(isset($tab) && $tab=='referral') active @endif nav-link "
                               id="pills-suspended-user-tab" data-toggle="pill" data-controls="referral"
                               href="#referral"
                               role="tab" aria-controls="pills-suspended-user" aria-selected="true">
                                <span>{{__('Referral Settings')}}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
            <div class="col-12 col-lg-9 col-xl-10">
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane show @if(isset($tab) && $tab=='api_config')  active @endif" id="api_config"
                         role="tabpanel" aria-labelledby="pills-user-tab">
                        @include('admin.settings.setting.api_config')
                    </div>
                    <div class="tab-pane show @if(isset($tab) && $tab=='general')  active @endif" id="general"
                         role="tabpanel" aria-labelledby="pills-user-tab">
                        @include('admin.settings.setting.general')
                    </div>
                    <div class="tab-pane show @if(isset($tab) && $tab=='capcha')  active @endif" id="capcha"
                         role="tabpanel" aria-labelledby="pills-user-tab">
                        @include('admin.settings.setting.captcha')
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='email') show active @endif" id="email"
                         role="tabpanel" aria-labelledby="pills-add-user-tab">
                        @include('admin.settings.setting.email')
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='sms') show active @endif" id="sms" role="tabpanel"
                         aria-labelledby="pills-sms-tab">
                        @include('admin.settings.setting.twillo')
                    </div>
                    <div class="tab-pane @if(isset($tab) && $tab=='referral') show active @endif" id="referral"
                         role="tabpanel" aria-labelledby="pills-suspended-user-tab">
                        @include('admin.settings.setting.referral')
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
    <script>
        (function ($) {
            "use strict";

            $('.nav-link').on('click', function () {
                $('.nav-link').removeClass('active');
                $(this).addClass('active');
                var str = '#' + $(this).data('controls');
                $('.tab-pane').removeClass('show active');
                $(str).addClass('show active');
            });
        })(jQuery)
    </script>
@endsection
