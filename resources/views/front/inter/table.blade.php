@extends('front.main')

@section('content')

    <a class="btn btn-primary" href="{{ route('inter.history', ['pair' => $pair]) }}">Показать динамику цен</a>
    <br/><br/>

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <div class="legend">
                        <div class="float-left m-1 p-1 ">Legend of Profit: </div>
                        <div class="float-left border m-1 p-1 bg_1">-&infin; &mdash; 1%</div>
                        <div class="float-left border m-1 p-1 bg_1_1">-1% &mdash; 1%</div>
                        <div class="float-left border m-1 p-1 bg_1_5">1% &mdash; 5%</div>
                        <div class="float-left border m-1 p-1 bg_5_10">5% &mdash; 10%</div>
                        <div class="float-left border m-1 p-1 bg_10_20">10% &mdash; 20%</div>
                        <div class="float-left border m-1 p-1 bg_20">20% &mdash; &infin;</div>
                    </div>

                    <br clear="all"/><br/>

                    <table class="table table-bordered table-sm arb_table">
                        <colgroup></colgroup>
                        @foreach($stocks as $stock_head1)
                            <colgroup></colgroup>
                        @endforeach
                        <thead>
                        <tr>
                            <th class="table_info_diag">
                                <div class="buy_diag">Buy</div>
                                <div class="sell_diag">Sell</div>
                            </th>

                            @foreach($stocks as $stock_head1)
                                <th bgcolor="#f4edda" class="text-center table_exchange">
                                    <a href="{{ $stock_head1->stock_url }}" target="_blank">
                                        <img style="max-width: 100%" src="/imgs/stocks/{{ $stock_head1->stock->logo }}"
                                             alt="{{ $stock_head1->stock->name }}"
                                             data-original-title="{{ $stock_head1->stock->name }}"
                                             data-toggle="tooltip"
                                             data-placement="top"
                                        />
                                    </a>

                                    <div class="alert-info m-1">
                                        Bid: {{ rtrim(number_format($stock_head1->bid, 10), 0) }}
                                        Last: {{ rtrim(number_format($stock_head1->last, 10), 0) }}
                                    </div>
                                    <div class="alert-info m-1">Vol: {{ $stock_head1->volume or 0 }}</div>
                                </th>
                            @endforeach
                        </tr>
                        </thead>

                        <tbody>
                        @foreach($stocks as $stock_buy)
                            <tr>
                                <td bgcolor="#f4edda" class="text-center table_exchange">
                                    <a href="{{ $stock_buy->stock_url }}" target="_blank">
                                        <img src="/imgs/stocks/{{ $stock_buy->stock->logo }}"
                                             alt="{{ $stock_buy->stock->name }}"
                                             data-original-title="{{ $stock_buy->stock->name }}"
                                             data-toggle="tooltip"
                                             data-placement="top"
                                        />
                                    </a>
                                    <br/>
                                    <div class="alert-info m-1">{{ rtrim(number_format($stock_buy->ask, 10), 0) }}</div>
                                    <div class="alert-info m-1">Vol: {{ $stock_buy->volume or 0 }}</div>
                                </td>
                                @php
                                    if($stock_buy->ask == 0 && $stock_buy->last > 0) {
                                        $stock_buy_ask = $stock_buy->last;
                                    } else $stock_buy_ask = $stock_buy->ask;
                                @endphp
                                @foreach($stocks as $stock_sell)
                                    @php
                                        if($stock_sell->bid == 0 && $stock_sell->last > 0) {
                                            $stock_sell_bid = $stock_sell->last;
                                        } else $stock_sell_bid = $stock_sell->bid;
                                    @endphp
                                    @if($stock_buy->stock_id == $stock_sell->stock_id)
                                        <td bgcolor="#f4edda" class="text-center">-</td>
                                    @elseif($stock_sell_bid > 0 && $stock_buy_ask > 0)
                                        @php $price = round((($stock_sell_bid - $stock_buy_ask)/$stock_sell_bid * 100), 2) @endphp
                                        <td class="text-center
                                            @if($price < -1) bg_1
                                            @elseif($price < 1) bg_1_1
                                            @elseif($price < 5) bg_1_5
                                            @elseif($price < 10) bg_5_10
                                            @elseif($price < 20) bg_10_20
                                            @elseif($price > 20) bg_20
                                            @endif
                                            ">{{ $price }}%
                                            @php $min_vol = min($stock_buy->volume, $stock_sell->volume) @endphp
                                            <div class="
                                            @if($min_vol < 1) text-danger @endif
                                             m-1">vol: {{ $min_vol }}</div>
                                        </td>
                                    @else
                                        <td class="text-center"> - </td>
                                    @endif
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>

@endsection

@section('styles')
    <style>
        .arb_table {
            text-shadow: 1px 1px 1px #fff;
            font-size:
                    @if($stocks->count() < 5) 1rem
                    @elseif($stocks->count() < 9) .8rem
                    @else .7rem
                    @endif
;
        }
        .hover td:first-child {
            background-color: #ffa !important;
        }
    </style>
@endsection
@section('scripts')
    <script>
        $("table").delegate('td','mouseover mouseleave', function(e) {
            if (e.type === 'mouseover') {
                $(this).parent().addClass("hover");
                $("colgroup").eq($(this).index()).addClass("hover");
            }
            else {
                $(this).parent().removeClass("hover");
                $("colgroup").eq($(this).index()).removeClass("hover");
            }
        });
    </script>
@endsection