@extends('front.main')

@section('content')

    <h1>Вилки внешнебиржевого арбитража</h1>

    <div class="row">
        <div class="col-12">
            <div class="w-100 text-center">
                <button class="btn btn-link filter_show_btn">Filter</button>
            </div>
            <div class="tile filter_show" style="display: none;">
                <div class="tile-body">
                    @include('front.inter._form')
                </div>
            </div>

            <div class="tile">
                <div class="tile-body">

                    <div class="float-left">
                        <h2>
                            Last update {{ $last_up->time }}
                            <span class="small text-danger">
                                ({{ \Carbon\Carbon::parse($last_up->time)->diff(\Carbon\Carbon::now())->format('%H:%I ago') }})
                            </span>
                        </h2>
                        <span class="small text-danger">
                            Профит посчитан без учета комисий
                        </span>
                    </div>

                    <br clear="all"/><br/>

                    @foreach($current_stocks as $stock)
                        <img class="border float-left m-1" src="/imgs/stocks/{{ $stock->logo }}"
                             alt="{{ $stock->name }}"
                             data-toggle="tooltip"
                             data-html="true"
                             data-placement="top"
                             title='<img src="/imgs/flags/{{ $stock->flag }}" />  {{ $stock->name }} <br/> Cap: {{ number_format($stock->cap) }}'
                             data-content='123'
                        />
                    @endforeach

                    <br clear="all"/><br/>

                    <table class="table table-striped inter-table" id="logs-table">
                        <tbody>
                        @foreach($res as $item)
                            <tr>
                                <td>
                                    <p class="p-2 m-0">
                                        {{ $item->symbol }} &nbsp;
                                        <a href="{{ route('inter.history', ['pair' => $item->symbol]) }}" class="history_link fa fa-line-chart"></a> &nbsp;
                                        <a href="{{ route('inter.table', ['up_id' => $last_up->id, 'pair' => $item->symbol]) }}" class="table_link fa fa-table"></a>
                                    </p></td>
                                <td>
                                    <div class="float-left p-2">Buy:</div>
                                    <div class="float-left p-2">
                                        <a href="{{ $item->stock_min_url }}" target="_blank">
                                            <img class="border stock_logo" src="/imgs/stocks/{{ $item->stock_min->logo }}"
                                                 alt="{{ $item->stock_min->name }}"
                                                 data-original-title="{{ $item->stock_min->name }}"
                                                 data-toggle="tooltip"
                                                 data-placement="top"
                                            />
                                        </a>
                                    </div>
                                    <div class="float-left p-2" style="line-height: 13px;">
                                        Ask: {{ rtrim(number_format($item->stock_min_ask, 10), 0) }}
                                        <br/><span class="small">Vol: {{ $item->stock_min_volume }} {{ explode('/', $item->symbol)[1] }}</span>
                                    </div>
                                    <div class="float-right text-center w-25 p-1">
                                        <i class="fa fa-arrow-right text-success" style="font-size: 30px;"></i>
                                    </div>

                                </td>
                                <td>
                                    <div class="float-left p-2">Sell:</div>
                                    <div class="float-left p-2">
                                        <a href="{{ $item->stock_max_url }}" target="_blank">
                                            <img class="border stock_logo" src="/imgs/stocks/{{ $item->stock_max->logo }}"
                                                 alt="{{ $item->stock_max->name }}"
                                                 data-original-title="{{ $item->stock_max->name }}"
                                                 data-toggle="tooltip"
                                                 data-placement="top"
                                            />
                                        </a>

                                    </div>
                                    <div class="float-left p-2" style="line-height: 13px;">
                                        Bid: {{ rtrim(number_format($item->stock_max_bid, 10), 0) }}
                                        <br/><span class="small">Vol: {{ $item->stock_max_volume }} {{ explode('/', $item->symbol)[1] }}</span>
                                    </div>
                                </td>
                                <td>
                                    {{ $item->percent }} %
                                    <br/><a class="comparision_link" href="#">Comparision</a>
                                </td>
                            </tr>
                            <tr class="comparision" style="display: none;">
                                <td></td>
                                <td>
                                    <table class="table table-sm">
                                        @foreach($item->comparision->sortBy('last') as $item_gr)
                                            <tr>
                                                <td><a href="{{ $item_gr->stock_url }}" target="_blank">{{ $item_gr->stock->name }}</a></td>
                                                <td>Ask: {{ rtrim(number_format($item_gr->ask, 10), 0) }}</td>
                                                <td>Bid: {{ rtrim(number_format($item_gr->bid, 10), 0) }}</td>
                                                <td><span class="small">Vol: {{ $item_gr->volume }} {{ explode('/', $item_gr->symbol)[1] }}</span></td>
                                            </tr>
                                        @endforeach
                                    </table>

                                </td>
                                <td></td>
                                <td></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>

        $( document ).ready(function() {

            $('.filter_show_btn').click(function (e) {
                e.preventDefault();
                var filter = $('.filter_show');
                if(filter.is(":visible")){
                    filter.hide();
                } else {
                    filter.show();
                }
            });

            $('.comparision_link').click(function (e) {
                e.preventDefault();
                var comparision = $(this).parent().parent().next();
                if(comparision.is(":visible")){
                    comparision.hide();
                } else {
                    comparision.show();
                }
            });


            $('.history_link').click(function (e) {
                @if( ! Auth::check() )
                e.preventDefault();
                if (!confirm('Для простомра надо зарегестрироваться')) return;
                location.href = "{{ route('register') }}";
                @endif
            });

            $('.table_link').click(function (e) {
                @if( ! Auth::check() )
                e.preventDefault();
                if (!confirm('Для простомра надо зарегестрироваться')) return;
                location.href = "{{ route('register') }}";
                @endif
            });

        });

        $(function () {
            $('[data-toggle="popover"]').popover();
        })

    </script>
@endsection