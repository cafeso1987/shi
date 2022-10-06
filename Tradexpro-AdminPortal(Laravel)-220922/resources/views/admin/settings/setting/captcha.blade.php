<div class="header-bar">
    <div class="table-title">
        <h3>{{__('Google Re-Capcha Settings')}}</h3>
    </div>
</div>
<div class="profile-info-form">
    <form action="{{route('adminCapchaSettings')}}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">

            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label>{{__('Enable Google Re captcha')}}</label>
                    <div class="cp-select-area">
                        <select name="google_recapcha" class="form-control">
                            <option @if(isset($settings['google_recapcha']) && $settings['google_recapcha'] == STATUS_REJECTED) selected @endif value="{{STATUS_REJECTED}}">{{__("No")}}</option>
                            <option @if(isset($settings['google_recapcha']) && $settings['google_recapcha'] == STATUS_ACTIVE) selected @endif value="{{STATUS_ACTIVE}}">{{__("Yes")}}</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label
                        for="#">{{__('Captcha Secret')}} </label>
                    <input class="form-control " type="text"
                           name="NOCAPTCHA_SECRET" placeholder=""
                           value="{{isset($settings['NOCAPTCHA_SECRET']) ? $settings['NOCAPTCHA_SECRET'] : ''}}">
                </div>
            </div>
            <div class="col-lg-6 col-12 mt-20">
                <div class="form-group">
                    <label
                        for="#">{{__('Captcha Site key')}} </label>
                    <input class="form-control " type="text"
                           name="NOCAPTCHA_SITEKEY" placeholder=""
                           value="{{isset($settings['NOCAPTCHA_SITEKEY']) ? $settings['NOCAPTCHA_SITEKEY'] : ''}}">
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
