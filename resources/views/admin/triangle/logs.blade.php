@extends('admin.main')

@section('name', 'Вилки внутрибиржевого арбитража')
@section('desc', 'Вилка - арбитражная ситуация')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <div class="row">
                        <div class="col-4">
                            <div data-column="1">
                                <select type="text" class="column_filter form-control">
                                    <option value="">Выберите биржу</option>
                                    @foreach($stocks as $stock)
                                        <option value="{{ $stock->stock_id }}">{{ $stock->stock->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <input placeholder="Поиск по тройке" type="text" name="trio" class="form-control" id="search_trio"/>
                        </div>
                        <div class="col-4 text-right">
                            <button class="btn btn-outline-primary" onclick="clearFilters()">Сбросить</button>
                        </div>
                    </div>

                    <br/>



                    <table class="table table-bordered table-striped" id="logs-table">
                        <thead>
                        <tr>
                            <th width="30">№</th>
                            <th>ex_id</th>
                            <th>Биржа</th>
                            <th>Тройка</th>
                            <th>Профит</th>
                            <th>Мин</th>
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

        function searchTrio ( value ) {
            $('input#search_trio').val(value);
            $('#logs-table').DataTable().draw();
            return false;
        }

        function clearFilters () {
            $('input#search_trio').val('');
            $('select.column_filter').val('');

            var table = $('#logs-table').DataTable();
            table
                .search( '' )
                .columns().search( '' )
                .draw();
        }


        $(function() {

            function filterColumn ( i, value ) {
                $('#logs-table').DataTable().column( i ).search(value, false, false, true).draw();
            }


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
                        "targets": 0,
                        "searchable": false
                    },
                    {
                        "targets": 1,
                        "visible": false
                    },
                    { // Биржа
                        "targets": 2,
                        "searchable": false,
                        "render": function ( data, type, row ) {
                            return '<a href="' + data.www + '" target="_blink" >' + data.name + '</a>'
                        }
                    },
                    { // Тройка
                        "targets": 3,
                        "searchable": false,
                        "render": function ( data, type, row ) {
                            return '<span onclick="searchTrio(\'' + data + '\')" >' + data + '</span>'
                        }
                    },
                    { // Профит
                        "targets": 4,
                        "render": function ( data, type, row ) {
                            return data + ' %'
                        }
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
                ajax: {
                    url: '{{ route('admin.triangle.logs_data') }}',
                    data: function (d) {
                        d.trio = $('input#search_trio').val();
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'stock_id', name: 'stock_id' },
                    { data: 'stock', name: 'stock' },
                    { data: 'symbol', name: 'symbol' },
                    { data: 'profit', name: 'profit' },
                    { data: 'min', name: 'min' },
                    { data: 'created_at', name: 'created_at' }
                ]
            });

            $('select.column_filter').on( 'change', function () {
                filterColumn( $(this).parents('div').attr('data-column'), $(this).val() );
            } );

            $('input#search_trio').on( 'keyup', function () {
                $('#logs-table').DataTable().draw();
            } );
        });


    </script>

@endsection