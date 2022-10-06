<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="controls">
                <div class="form-label">{{__('Username')}}</div>
                <input type="text" class="form-control" name="coin_api_user" @if(isset($item))value="{{$item->coin_api_user}}" @else value="{{old('coin_api_user')}}" @endif>
                <pre class="text-danger">{{$errors->first('coin_api_user')}}</pre>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="controls">
                <div class="form-label">{{__('Password')}}</div>
                <input type="password" class="form-control" name="coin_api_pass"
                       @if(isset($item))value="{{$item->coin_api_pass}}" @else value="{{old('coin_api_pass')}}" @endif >
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="controls">
                <div class="form-label">{{__('Host')}}</div>
                <input type="text" class="form-control" name="coin_api_host"
                       @if(isset($item))value="{{$item->coin_api_host}}" @else value="{{old('coin_api_host')}}" @endif>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="controls">
                <div class="form-label">{{__('Port')}}</div>
                <input type="text" class="form-control" name="coin_api_port"
                       @if(isset($item))value="{{$item->coin_api_port}}" @else value="{{old('coin_api_port')}}" @endif >
            </div>
        </div>
    </div>
</div>

