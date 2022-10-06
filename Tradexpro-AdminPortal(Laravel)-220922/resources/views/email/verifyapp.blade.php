@include('email.header_new')
<h3>{{__('Hello')}}, {{ $data->first_name.' '.$data->last_name  }}</h3>
<p>
    {{__('We need to verify your email address. ')}}
</p>
<p>   Your {{allSetting()['app_title']}} email verification code is : </p>
<h3>{{$key}}</h3>
<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{allSetting()['app_title']}}
</p>
@include('email.footer_new')
