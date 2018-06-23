@extends('admin.main')

@section('name', 'Вилки внутрибиржевого арбитража')
@section('desc', 'Вилка - арбитражная ситуация')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <div data-column="1">
                        <select type="text" class="column_filter form-control" id="col2_filter">
                            <option value="">Выберите биржу</option>
                            <option value="24">Bittrex</option>
                            <option value="62">EXMO</option>
                            <option value="85">Kucoin</option>
                        </select>
                    </div>
                    <br/>


                    <table class="table table-bordered table-striped" id="logs-table">
                        <thead>
                        <tr>
                            <th>№</th>
                            <th>ex_id</th>
                            <th>Биржа</th>
                            <th>Тройка</th>
                            <th>Профит</th>
                            <th>Время</th>
                        </tr>
                        </thead>
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

            function filterColumn ( i, value ) {
                $('#logs-table').DataTable().column( i ).search(value).draw();
            }

            $('#logs-table').DataTable({
                "language": {
                    search: "",
                    searchPlaceholder: "Поиск по названию...",
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
                        "targets": 0,
                        "visible": true,
                        "searchable": false,
                    },
                    {
                        "targets": 1,
                        "visible": false,
                    },
                    {
                        "targets": [4,5],
                        "searchable": false
                    }
                ],
                "order": [[ 0, "desc" ]],
                processing: true,
                serverSide: true,
//                pageLength: 50,
                ajax: '{!! route('admin.triangle.logs_data') !!}',
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'exchange_id', name: 'exchange_id' },
                    { data: 'exchange.name', name: 'exchange_name' },
                    { data: 'symbol', name: 'symbol' },
                    { data: 'profit', name: 'profit' },
                    { data: 'created_at', name: 'created_at' },
                ]
            });

            $('select.column_filter').on( 'change', function () {
                filterColumn( $(this).parents('div').attr('data-column'), $(this).val() );
            } );
        });


    </script>

@endsection