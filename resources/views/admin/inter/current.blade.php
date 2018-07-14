@extends('admin.main')

@section('name', 'Вилки внешнебиржевого арбитража')
@section('desc', 'Вилка - арбитражная ситуация')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">
                    @include('admin.inter._form')
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
                    </div>

                    <br clear="all"/><br/>

                    @foreach($current_stocks as $stock)
                        <img class="border float-left m-1" src="/imgs/stocks/{{ $stock->logo }}" alt="{{ $stock->name }}" />
                    @endforeach

                    <br clear="all"/><br/>

                    @if($res->count() == 0)
                        <h3>Updating... Try in a minute</h3>
                    @endif

                    <table class="table table-striped" id="logs-table">
                        <tbody>
                        @foreach($res as $item)
                            <tr>
                                <td>
                                    <p class="p-2 m-0">
                                        {{ $item->symbol }} &nbsp;
                                        <a href="#" class="fa fa-line-chart"></a> &nbsp;
                                        <a href="{{ route('admin.inter.show', ['up_id' => $last_up->id, 'pair' => $item->symbol]) }}" class="fa fa-table"></a>
                                    </p></td>
                                <td>
                                    <div class="float-left p-2">Buy:</div>
                                    <div class="float-left p-2">
                                        <img class="border stock_logo" src="/imgs/stocks/{{ $item->stock_min->logo }}" alt="{{ $item->stock_min->name }}"
                                             data-toggle="popover"
                                             data-html="true"
                                             data-placement="top"
                                             title='<img src="/imgs/flags/{{ $item->stock_min->country['flag'] }}" />  <a href="{{ $item->stock_min_url }}" target="_blank">{{ $item->stock_min->name }}</a>'
                                             data-content=' '
                                        />
                                    </div>
                                    <div class="float-left p-2" style="line-height: 13px;">
                                        Price: {{ rtrim(number_format($item->stock_min_price, 10), 0) }}
                                        <br/><span class="small">Vol: {{ $item->stock_min_volume }} {{ explode('/', $item->symbol)[1] }}</span>
                                    </div>
                                    <div class="float-right text-center w-25 p-1">
                                        <i class="fa fa-arrow-right text-success" style="font-size: 30px;"></i>
                                    </div>

                                </td>
                                <td>
                                    <div class="float-left p-2">Sell:</div>
                                    <div class="float-left p-2">
                                        <img class="border stock_logo" src="/imgs/stocks/{{ $item->stock_max->logo }}" alt="{{ $item->stock_max->name }}"
                                             data-toggle="popover"
                                             data-html="true"
                                             data-placement="top"
                                             title='<img src="/imgs/flags/{{ $item->stock_max->country['flag'] }}" />  <a href="{{ $item->stock_max_url }}" target="_blank">{{ $item->stock_max->name }}</a>'
                                             data-content=' '
                                        />
                                    </div>
                                    <div class="float-left p-2" style="line-height: 13px;">
                                        Price: {{ rtrim(number_format($item->stock_max_price, 10), 0) }}
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
                                                <td>Price: {{ rtrim(number_format($item_gr->last, 10), 0) }}</td>
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
            $('.comparision_link').click(function (e) {
                e.preventDefault();
                var comparision = $(this).parent().parent().next();
                if(comparision.is(":visible")){
                    comparision.hide();
                } else {
                    comparision.show();
                }
            })
        });

        $(function () {
            $('[data-toggle="popover"]').popover();
        })

    </script>
@endsection