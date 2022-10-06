@extends('admin.master',['menu'=>'wallet'])
@section('title', isset($title) ? $title : '')
@section('style')
@endsection
@section('content')
    <!-- breadcrumb -->
    <div class="custom-breadcrumb">
        <div class="row">
            <div class="col-12">
                <ul>
                    <li>{{__('Wallet Management')}}</li>
                    <li class="active-item">{{__('Send coin to user')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- /breadcrumb -->

    <!-- User Management -->
    <div class="user-management pt-4">
        <div class="row">
            <div class="col-12">
                <div class="profile-info-form">
                    <form action="{{route('adminSendBalanceProcess')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-md-12 mt-20">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <label>{{ __('Amount') }}</label>
                                        <input type="text" name="amount" class="form-control-new" value="{{old('amount')}}">
                                    </div>
                                    <div class="col-6">
                                        <label>{{ __('Select User Wallet') }}</label>
                                        <select name="wallet_id[]" id="user_select" class="selectpicker" title="{{ __('User Wallet') }}" data-live-search="true" data-width="100%" data-style="btn-info" data-actions-box="true" data-selected-text-format="count > 4" multiple>
                                            @if(isset($wallets[0]))
                                                @foreach($wallets as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name. ' ('. $user->first_name. ' '. $user->last_name.')' }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-6">
                                        <button type="submit" class="button-primary theme-btn">{{ __('Send') }}</button>
                                    </div>
                                </div>
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
    <script>
        (function($) {
            "use strict";

        })(jQuery)
    </script>
@endsection
