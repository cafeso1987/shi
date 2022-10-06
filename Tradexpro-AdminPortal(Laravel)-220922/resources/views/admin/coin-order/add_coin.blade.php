@extends('admin.master',['menu'=>'coin', 'sub_menu' => 'coin_list'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-9">
                <ul>
                    <li>{{__('Coin')}}</li>
                    <li class="active-item">{{ $title }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management">
        <div class="row">
            <div class="col-12">
                <div class="profile-info-form">
                    <div>
                        {{Form::open(['route'=>'adminSaveCoin', 'files' => true])}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Coin Full Name')}}</div>
                                        <input type="text" class="form-control" name="name" value="{{old('name')}}">
                                        <pre class="text-danger">{{$errors->first('name')}}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Coin Type')}}</div>
                                        <input type="text" class="form-control" name="coin_type" value="{{ old('coin_type') }}">
                                        <small>{{__('Please make sure your coin type is right. Never input wrong coin type')}}</small>
                                        <pre class="text-danger">{{$errors->first('coin_type')}}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Coin Price (in USD)')}}</div>
                                        <div class="input-group mb-3 w-85 ">
                                            <input type="text" class="form-control" name="coin_price" value="{{ old('coin_price') }}">
                                            <div class="input-group-append">
                                                <span class="input-group-text px-4"><span class="currency text-warning">USD</span></span>
                                            </div>
                                        </div>
                                        <small>{{__('Coin price in USD. it will update by currency api regularly')}}</small>
                                        <pre class="text-danger">{{$errors->first('coin_price')}}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <div class="controls">
                                        <div class="form-label">{{__('Network')}}</div>
                                        <select name="network" id="" class="form-control">
                                            @foreach(api_settings() as $key => $value)
                                                <option value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <small>{{__('Please make sure your coin network is right.You never change this network. So be careful')}}</small>
                                        <pre class="text-danger">{{$errors->first('network')}}</pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <button type="submit" class="btn theme-btn">{{$button_title}}</button>
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /User Management -->
@endsection
@section('script')
@endsection
