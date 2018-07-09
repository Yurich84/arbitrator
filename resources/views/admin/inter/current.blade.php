@extends('admin.main')

@section('name', 'Вилки внешнебиржевого арбитража')
@section('desc', 'Вилка - арбитражная ситуация')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <table class="table table-bordered table-striped" id="logs-table">
                        <thead>
                        <tr>
                            <th>Пара</th>
                            <th>Биржа покупки</th>
                            <th>Покупаем</th>
                            <th>Биржа продажи</th>
                            <th>Продаем</th>
                            <th>Профит</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($res as $item)
                            <tr>
                                <td>{{ $item->symbol }}</td>
                                <td>{{ $item->stock_min }}</td>
                                <td>
                                    {{ link_to($item->stock_min_url, $item->stock_min) }}
                                    <br/>
                                    Price: {{ rtrim(number_format($item->stock_min_price, 10), 0) }}
                                    <br/><span class="small">Vol: {{ $item->stock_min_volume }} {{ explode('/', $item->symbol)[1] }}</span>
                                </td>
                                <td>{{ $item->stock_max }}</td>
                                <td>
                                    {{ link_to($item->stock_max_url, $item->stock_max) }}
                                    <br/>
                                    Price: {{ rtrim(number_format($item->stock_max_price, 10), 0) }}
                                    <br/><span class="small">Vol: {{ $item->stock_max_volume }} {{ explode('/', $item->symbol)[1] }}</span>
                                </td>
                                <td>{{ $item->percent }} %</td>
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

    <!-- Data table plugin-->
    <script src="{{ asset("js/vali/plugins/jquery.dataTables.min.js") }}"></script>
    <script src="{{ asset("js/vali/plugins/dataTables.bootstrap.min.js") }}"></script>
    <script>

        $(function() {
            $('#logs-table').DataTable({
                "language": {
                    search: "",
                    searchPlaceholder: "Поиск...",
                    processing:     "Загрузка...",
                    info:           "Показано _START_ - _END_ из _TOTAL_ ",
                    infoFiltered:   "(всего _MAX_ )",
                    infoPostFix:    "",
                    loadingRecords: "Загрузка...",
                    zeroRecords:    "Ничего не найдено",
                    emptyTable:     "Ничего не найдено",
                    paginate: {
                        first:      "Первая",
                        previous:   "Назад",
                        next:       "Вперед",
                        last:       "Последняя"
                    }
                },
                "columnDefs": [
                    {
                        // биржа покупки
                        "targets": 1,
                        "visible": false
                    },
                    {
                        // биржа продажи
                        "targets": 3,
                        "visible": false
                    }
                ],
                "order": [[ 5, "desc" ]],
                processing: true,
                pageLength: 20
            });
        });


    </script>

@endsection