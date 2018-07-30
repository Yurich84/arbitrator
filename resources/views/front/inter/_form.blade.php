{{ Form::open(['route' => 'inter.current_post', 'method' => 'post', 'class' => 'form-horizontal']) }}
<div class="row form-group p-3">
    <div class="col-md-2 lead pt-4">
        Profit (%):
    </div>
    <div class="col-md-10">
        <input type="text" id="profit_slider" name="profit_slider" />
    </div>
</div>

<div class="row form-group mr-0 ml-0">
    <div class="col-md-12 col-lg-6 col-xl-3 pt-3">
        <label class="control-label float-left p-2">Minimum volume:</label>
        <div class="float-left">
            <input name="min_volume" size="2" value="{{ $min_volume }}" type="text" class="form-control"/>
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-3 pt-3">
        <label class="control-label float-left  p-2">Exchanges:</label>
        <div class="float-left">
            {!! Form::select(null, $stocks->pluck('stock.name', 'stock_id'), $stock_ids,
                ['id' => 'exchanges', 'multiple' => 'multiple', 'class' => 'form-control']
            ) !!}
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-3 pt-3">
        <label class="control-label float-left p-2">Cryptocurrency only:</label>
        <div class="float-left">
            <div class="toggle lg p-2">
                <label>
                    <input name="crypto_curr_only" type="hidden" value="0">
                    <input name="crypto_curr_only" value="1" @if($crypto_curr_only) checked @endif type="checkbox"><span class="button-indecator"></span>
                </label>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-lg-6 col-xl-3 pt-3">
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
</div>

{{--<div class="form-group">--}}
    {{--Black list:--}}
{{--</div>--}}

<div class="row">
    <div class="col-sm-6">
        {{ Form::button('Apply', ['type' => 'submit', 'class' => 'btn btn-primary float-right']) }}
    </div>

</div>

{{ Form::close() }}

@push('script')
    {{--<script src="https://code.jquery.com/ui/1.11.1/jquery-ui.js"></script>--}}
    {{--<script src="/js/jQEditRangeSlider-withRuler-min.js"></script>--}}

    <script src="{{asset("/js/ion.rangeSlider.min.js")}}"></script>
    <script src="{{asset("/js/bootstrap-multiselect.js")}}"></script>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#exchanges').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonClass: 'btn btn-outline-secondary',
                maxHeight: 300,
                checkboxName: 'stock_ids[]',
                nonSelectedText: 'Exchanges',
                nSelectedText: ' selected',
                buttonWidth: '200px',
                selectedClass: 'myselected_op'
            });

            $("#profit_slider").ionRangeSlider({
                type: "double",
                step: 0.5,
                min: 0,
                max: 100,
                from: {{ $min_profit }},
                to: {{ $max_profit }},
                grid: true,
                grid_num: 10
            });
        });
    </script>

@endpush
@push('style')
    <link href="{{ asset('/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/ion.rangeSlider.css') }}" rel="stylesheet" />
    <link href="{{ asset('/css/ion.rangeSlider.skinModern.css') }}" rel="stylesheet" />
@endpush