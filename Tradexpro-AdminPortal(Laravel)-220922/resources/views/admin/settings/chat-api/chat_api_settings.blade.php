@extends('admin.master',['menu'=>'setting', 'sub_menu'=>'chat'])
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
            <div class="col-md-12 col-xl-12">
                <div class="header-bar">
                    <div class="table-title">
                        <h3>{{__('Update chat api Settings')}}</h3>
                    </div>
                </div>
                <div class="profile-info-form">
                    <form action="{{route('adminCookieSettingsSave')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-lg-6 col-12 mt-20">
                                <div class="form-group">
                                    <label>{{__('Enable live chat')}}</label>
                                    <div class="cp-select-area">
                                        <select name="live_chat_status" class="form-control">
                                            <option @if(isset($settings['live_chat_status']) && $settings['live_chat_status'] == STATUS_REJECTED) selected @endif value="{{STATUS_REJECTED}}">{{__("No")}}</option>
                                            <option @if(isset($settings['live_chat_status']) && $settings['live_chat_status'] == STATUS_ACTIVE) selected @endif value="{{STATUS_ACTIVE}}">{{__("Yes")}}</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12 mt-20">
                                <div class="form-group">
                                    <label
                                        for="#">{{__('Chat api key')}} </label>
                                    <input class="form-control " type="text"
                                           name="live_chat_key" placeholder=""
                                           value="{{isset($settings['live_chat_key']) ? $settings['live_chat_key'] : ''}}">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2 col-12 mt-20">
                                <button class="button-primary theme-btn">{{__('Update')}}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->

@endsection

@section('script')
@endsection
