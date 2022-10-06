<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="controls">
                <div class="form-label">{{__('Bitgo Wallet ID')}}</div>
                <input type="text" class="form-control" name="bitgo_wallet_id"
                       @if(isset($item))value="{{$item->bitgo_wallet_id}}" @else value="" @endif>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="controls">
                <div class="form-label">{{__('Bitgo Wallet Password')}}</div>
                <input type="password" class="form-control" name="bitgo_wallet"
                       @if(isset($item))value="{{$item->bitgo_wallet}}" @else value="" @endif>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <div class="controls">
                <div class="form-label">{{__('Bitgo Wallet Chain')}}</div>
                <input type="text" class="form-control" name="chain"
                       @if(isset($item))value="{{$item->chain}}" @else value="1" @endif>
            </div>
        </div>
    </div>
</div>
