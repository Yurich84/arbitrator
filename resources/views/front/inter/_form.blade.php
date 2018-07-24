{{ Form::open(['route' => 'inter.current_post', 'method' => 'post', 'class' => 'form-horizontal']) }}
<div class="row form-group">
    <div class="col-md-2 lead pt-4">
        Profit:
    </div>
    <div class="col-md-10">
        <div id="slider"></div>
    </div>
</div>

<div class="row form-group mr-0 ml-0">
    <div class="col-md-4 border pt-3">
        <div class="row">
            <label class="control-label col-md-7 p-2">Minimum volume:</label>
            <div class="col-md-5">
                <input name="min_volume" value="{{ $min_volume }}" type="text" class="form-control"/>
            </div>
        </div>
    </div>
    <div class="col-md-4 border pt-3">
        <div class="row">
            <label class="control-label col-md-6 p-2">Cryptocurrency only:</label>
            <div class="col-md-6">
                <div class="toggle lg p-2">
                    <label>
                        <input name="crypto_curr_only" type="hidden" value="0">
                        <input name="crypto_curr_only" value="1" @if($crypto_curr_only) checked @endif type="checkbox"><span class="button-indecator"></span>
                    </label>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4 border pt-3">
        <div class="row">
            <label class="control-label col-md-4  p-2">Exchanges:</label>
            <div class="col-md-7">

                {!! Form::select(null, $stocks->pluck('stock.name', 'stock_id'), $stock_ids,
                    ['id' => 'exchanges', 'multiple' => 'multiple', 'class' => 'form-control']
                ) !!}

            </div>
        </div>
    </div>
</div>

{{--<div class="form-group">--}}
    {{--Black list:--}}
{{--</div>--}}

<div class="row">
    <div class="col-sm-6">
        <label class="control-label float-left p-2">Save filter preferences:
            @if(!Auth::check())
                * <span class="small alert-light">Only for registered users</span>
            @endif
        </label>
        <div class="float-left">
            <div class="toggle lg p-2">
                <label>
                    <input name="save_filter" type="hidden" value="0">
                    <input name="save_filter" @if(!Auth::check()) disabled="disabled" @endif @if($save_filter) checked @endif value="1" type="checkbox"><span class="button-indecator"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        {{ Form::button('Apply', ['type' => 'submit', 'class' => 'btn btn-primary float-right']) }}
    </div>

</div>

{{ Form::close() }}

@push('script')
    <script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
    <script src="/js/jQEditRangeSlider-withRuler-min.js"></script>

    <script src="{{asset("/js/bootstrap-multiselect.js")}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#exchanges').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonClass: 'btn btn-primary',
                maxHeight: 300,
                checkboxName: 'stock_ids[]',
                nonSelectedText: 'Exchanges',
                nSelectedText: ' selected',
                buttonWidth: '220px',
                selectedClass: 'myselected_op'
            });
        });
    </script>

    <script>
        $("#slider").editRangeSlider({
            bounds: {min: 0, max: 150},
            defaultValues:{min: {{ $min_profit }}, max: {{ $max_profit }} },
            step: 0.5,
            arrows: false,
            formatter:function(val){
                var value = Math.round(val * 2) / 2,
                    decimal = value - Math.round(val);
                return decimal == 0 ? value.toString() + " %" : value.toString() + " %";
            },
            scales: [
                // Primary scale
                {
                    first: function(val){ return val; },
                    next: function(val){ return val + 10; },
                    stop: function(val){ return false; },
                    label: function(val){ return val; },
                    format: function(tickContainer, tickStart, tickEnd){
                        tickContainer.addClass("myCustomClass");
                    }
                },
                // Secondary scale
                {
                    first: function(val){ return val; },
                    next: function(val){
                        if (val % 10 === 9){
                            return val + 2;
                        }
                        return val + 1;
                    },
                    stop: function(val){ return false; },
                    label: function(){ return null; }
                }]
        });
    </script>
@endpush
@push('style')
    <link href="{{ asset('/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.10.4/themes/flick/jquery-ui.css">
    <link rel="stylesheet" href="/css/iThing-min.css">
@endpush