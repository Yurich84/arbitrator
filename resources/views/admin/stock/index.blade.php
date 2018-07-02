@extends('admin.main')

@section('name', 'Биржи')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">
                    <table class="table table-bordered table-striped" id="logs-table">
                        <thead>
                        <tr>
                            <th width="60">№</th>
                            <th>Online</th>
                            <th>Биржа</th>
                            <th>Комиссия</th>
                            <th>Таймаут</th>
                            <th>К-во пар</th>
                            <th>Комментарий</th>
                            <th>Обновлено</th>
                            <th></th>
                        </tr>
                        </thead>

                        @foreach($stocks as $stock)
                            <tr data-id="{{ $stock->id }}">
                                <td>
                                    {{ $stock->id }}
                                    <i class="fa @if($stock->favorite) fa-star @else fa-star-o @endif " aria-hidden="true"></i>
                                </td>
                                <td>
                                    <div class="toggle-flip">
                                        <label>
                                            <input class="active_stock" type="checkbox" value="1" @if($stock->active) checked @endif />
                                            <span class="flip-indecator" data-toggle-on="Online" data-toggle-off="Offline"></span>
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <a href="{{ $stock->www }}" target="_blank">
                                        {{ Html::image( '/imgs/stocks/' . $stock->logo) }}
                                    </a>
                                    <br/>
                                    {{ $stock->ccxt_id }}
                                </td>
                                <td>{{ $stock->fee or 'n/a' }} %</td>
                                <td>{{ $stock->timeout or 'n/a' }} мин.</td>
                                <td>{{ $stock->market_qty }}</td>
                                <td>
                                    @if($stock->has_order_vol) * @endif
                                    {{ $stock->comment }}
                                </td>
                                <td>
                                    {{ $stock->updated_at }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.stock.edit', ['id' => $stock->id]) }}" class="teal-text" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $( document ).ready(function() {
            $('.active_stock').click( function() {
                var id = $(this).parent().parent().parent().parent().data('id');
                $.get( "/stock/active/"+id);
            });
        });
    </script>
@endsection