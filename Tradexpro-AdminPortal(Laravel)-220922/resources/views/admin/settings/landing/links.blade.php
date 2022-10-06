<div class="page-title">
    <div class="row">
        <div class="col-sm-12">
            <div class="page-title-inner">
                <div class="table-title mb-4">
                    <h3>{{__('Landing Page Api Link Settings')}}</h3>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-area plr-65 profile-info-form">
    <form enctype="multipart/form-data" method="POST"
          action="{{route('adminLandingSettingSave')}}">
        @csrf
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="#">{{__('Apple store link')}}</label>
                            <input type="text" class="form-control" name="apple_store_link" @if(isset($adm_setting['apple_store_link'])) value="{{$adm_setting['apple_store_link']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Android store link')}}</label>
                            <input type="text" class="form-control" name="android_store_link" @if(isset($adm_setting['android_store_link'])) value="{{$adm_setting['android_store_link']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Google store link')}}</label>
                            <input type="text" class="form-control" name="google_store_link" @if(isset($adm_setting['google_store_link'])) value="{{$adm_setting['google_store_link']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Macos store link')}}</label>
                            <input type="text" class="form-control" name="macos_store_link" @if(isset($adm_setting['macos_store_link'])) value="{{$adm_setting['macos_store_link']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Windows store link')}}</label>
                            <input type="text" class="form-control" name="windows_store_link" @if(isset($adm_setting['windows_store_link'])) value="{{$adm_setting['windows_store_link']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Linux store link')}}</label>
                            <input type="text" class="form-control" name="linux_store_link" @if(isset($adm_setting['linux_store_link'])) value="{{$adm_setting['linux_store_link']}}" @endif>
                        </div>
                        <div class="form-group">
                            <label for="#">{{__('Api link')}}</label>
                            <input type="text" class="form-control" name="api_link" @if(isset($adm_setting['api_link'])) value="{{$adm_setting['api_link']}}" @endif>
                        </div>
                        <button class="button-primary theme-btn">{{__('Update')}}</button>
                    </div>
                </div>
            </div>
        </div>

    </form>
</div>
