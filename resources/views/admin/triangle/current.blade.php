@extends('admin.main')

@section('name', 'Последние вилки внутрибиржевого арбитража')
@section('desc', 'Вилка - арбитражная ситуация')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">
                    <table class="table table-bordered table-striped" id="logs-table">
                        <thead>
                        <tr>
                            <th width="30">№</th>
                            <th>Биржа</th>
                            <th>Тройка</th>
                            <th>Профит</th>
                            <th>Мин. ставка</th>
                            <th>Время</th>
                        </tr>
                        </thead>

                        @foreach($triangles as $trio)
                            <tr id="troi_{{ $trio->id }}" data-id="{{ $trio->id }}">
                                <td>{{ $trio->id }}</td>
                                <td>{{ link_to($trio->stock->www, $trio->stock->name, ['target' => '_blink']) }}</td>
                                <td>{{ $trio->symbol }}</td>
                                <td class="current_profit @if($trio->profit < 0) text-danger @endif " >{{ $trio->profit }} %</td>
                                <td class="current_min">$ {{ $trio->min }}</td>
                                <td class="current_time">
                                    <stan style="color: {{ $trio->color }}"> {{ $trio->minutes }}m <i class="fa fa-clock-o"></i></stan>
                                    {{ $trio->created_at }}
                                </td>

                                <td align="center">
                                    @if($trio->stock->key)
                                        <a href="#" class="teal-text make_order" data-toggle="tooltip" data-placement="top" title="" data-original-title="Make order">
                                            <i class="fa fa-money"></i>
                                        </a>
                                    @endif  &nbsp;&nbsp;&nbsp;
                                    <a href="#" class="teal-text get_status" data-toggle="tooltip" data-placement="top" title="" data-original-title="Get status">
                                        <i class="fa fa-refresh" aria-hidden="true"></i>
                                    </a>
                                    &nbsp;&nbsp;&nbsp;
                                    <a href="#" class="teal-text show_info @if($trio->error) text-danger @endif" style="display: @if($trio->comment || $trio->error) inline @else none @endif"
                                       data-toggle="tooltip" data-placement="top" title="" data-original-title="Get info">
                                        <i class="fa fa-info" aria-hidden="true"></i>
                                    </a>
                                </td>

                            </tr>
                        @endforeach

                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade bd-example-modal-lg" id="showInfo" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="showInfoTitle">Info</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Торговать</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>
        $(function () {
            $('.get_status').on('click', function (e) {
                e.preventDefault();
                // обновляем информацию

                var tr = $(this).parent().parent();
                var id = tr.data('id');

                var load_img = '<img src="/imgs/templ/loading_line.gif">';

                tr.find('td.current_profit').html(load_img);
                tr.find('td.current_min').html(load_img);

                $.get( "/triangle/get_data/"+id, function( data ) {
                    if(data.error) {
                        alert(data.error);
                        tr.find('.show_info').addClass('text-danger');
                    }
                    tr.find('td.current_profit').text(data.profit.toFixed(4) + ' %');
                    tr.find('td.current_min').text('$' + data.min.toFixed(4));
                    tr.find('.show_info').show();
                });
            });


            $('.show_info').on('click', function (e) {
                e.preventDefault();
                // получаем информацию
                var tr = $(this).parent().parent();
                var id = tr.data('id');
                var modal = $('#showInfo');

                $.get( "/triangle/show_data/"+id, function( data ) {
                    var content = '';
                    if(data.comment) {
                        content += '<p>' + data.comment + '</p>';
                    }
                    if(data.error) {
                        content += '<p class="text-danger">' + data.error + '</p>';
                    }
                    modal.find('.modal-body').html(content);
                    modal.find('#showInfoTitle').html(data.symbol);
                    modal.modal();
                });
            });

            $('.make_order').on('click', function (e) {
                if (!confirm('Почати торги?')) return;
                e.preventDefault();
                // делаем заказ
                alert('Ф-ция в разработке');
            });

        });
    </script>
@endsection