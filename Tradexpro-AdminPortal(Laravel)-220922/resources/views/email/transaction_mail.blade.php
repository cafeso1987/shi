@include('email.header_new')
<h3>{{__('Hello')}}, {{isset($name) ? $name : ''}}</h3>
<p>{{$email_message}}</p>

{!! $email_message_table !!}

<p>
    {{__('Thanks a lot for being with us.')}} <br/>
    {{allSetting()['app_title']}}
</p>
@include('email.footer_new')
