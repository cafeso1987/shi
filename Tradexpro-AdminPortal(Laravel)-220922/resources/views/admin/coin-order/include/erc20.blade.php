<div class="row">
    <div class="col-lg-6 col-12  mt-20">
        <div class="form-group">
            <label for="#">{{__('Coin Price')}}</label>
            <input class="form-control" type="text" name="coin_price"
                   placeholder="{{__('Coin price in USD')}}"
                   value="{{$item->coin_price ?? 0}}">
        </div>
    </div>
    <div class="col-lg-6 col-12  mt-20">
        <div class="form-group">
            <label for="#">{{__('Contract coin name')}}</label>
            <input class="form-control" type="text" name="contract_coin_name"
                   placeholder="{{__('Base Coin Name For Token Ex. ETH/BNB')}}"
                   value="{{$item->contract_coin_name ?? 'ETH'}}">
        </div>
    </div>
    <div class="col-lg-6 col-12 mt-20">
        <div class="form-group">
            <label for="#">{{__('Chain link')}}</label>
            <input class="form-control" type="text" name="chain_link" required
                   placeholder="" value="{{$item->chain_link ?? ''}}">
        </div>
    </div>
    <div class="col-lg-6 col-12 mt-20">
        <div class="form-group">
            <label for="#">{{__('Chain ID')}}</label>
            <input class="form-control" type="text" name="chain_id" required
                   placeholder="" value="{{$item->chain_id ?? ''}}">
        </div>
    </div>
    <div class="col-lg-6 col-12 mt-20">
        <div class="form-group">
            <label for="#">{{__('Contract Address')}}</label>
            <input class="form-control" type="text" name="contract_address" required
                   placeholder="" value="{{$item->contract_address ?? ''}}">
        </div>
    </div>
    <div class="col-lg-6 col-12 mt-20">
        <div class="form-group">
            <label for="#">{{__('Wallet address')}}</label>
            <input class="form-control" type="text" required name="wallet_address"
                   placeholder="" value="{{$item->wallet_address ?? ''}}">
        </div>
    </div>
    <div class="col-lg-6 col-12 mt-20">
        <div class="form-group">
            <label for="#">{{__('Wallet key')}}</label>
            <input class="form-control" type="password" required name="wallet_key"
                   placeholder="" value="{{$item->wallet_key ?? ''}}">
        </div>
    </div>
    <div class="col-lg-6 col-12 mt-20">
        <div class="form-group">
            <label for="#">{{__('Decimal')}}</label>
            <select name="contract_decimal" id="" class="form-control">
                @foreach(contract_decimals() as $key => $val)
                    <option @if(isset($item->contract_decimal) && $item->contract_decimal == $key) selected @endif
                    value="{{$key}}">{{$key}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-lg-6 col-12 mt-20">
        <div class="form-group">
            <label for="#">{{__('Gas Limit')}}</label>
            <input type="text" name="gas_limit" class="form-control"
                   @if(isset($item->gas_limit)) value="{{$item->gas_limit}}" @else value="430000" @endif>
        </div>
    </div>
</div>
