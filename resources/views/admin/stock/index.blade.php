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
                            <th width="30">№</th>
                            <th>Лого</th>
                            <th>Название</th>
                            <th>Комиссия</th>
                            <th>Таймаут</th>
                            <th>Комментарий</th>
                            <th></th>
                        </tr>
                        </thead>

                        @foreach($stocks as $stock)
                            <tr @if($stock->active) style="background: lightgreen;" @endif>
                                <td>{{ $stock->id }}</td>
                                <td>{{ Html::image( '/imgs/stocks/' . $stock->logo) }}</td>
                                <td><i class="fa @if($stock->favorite) fa-star @else fa-star-o @endif " aria-hidden="true"></i>
                                    {{ link_to($stock->www, $stock->name, ['target' => '_blink']) }}</td>
                                <td>{{ $stock->fee or 'n/a' }} %</td>
                                <td>{{ $stock->timeout or 'n/a' }} мин.</td>
                                <td>
                                    @if($stock->has_order_vol) * @endif
                                    {{ $stock->comment }}</td>
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

@endsection