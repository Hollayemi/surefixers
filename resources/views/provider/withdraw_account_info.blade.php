<div class="alert alert-primary" role="alert">
    <h5>{{__('user.Withdraw Limit')}} : {{ $currency_icon->icon }}{{ $method->min_amount }} - {{ $currency_icon->icon }}{{ $method->max_amount }}</h5>
    <h5>{{__('user.Withdraw charge')}} : {{ $method->withdraw_charge }}%</h5>
    {!! clean($method->description) !!}
</div>
