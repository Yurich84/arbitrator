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
                            <th>Время</th>
                            <th></th>
                        </tr>
                        </thead>

                        @foreach($triangles as $trio)
                            <tr>
                                <td>{{ $trio->id }}</td>
                                <td>{{ link_to($trio->stock->www, $trio->stock->name, ['target' => '_blink']) }}</td>
                                <td>{{ $trio->symbol }}</td>
                                <td>{{ $trio->profit }} %</td>
                                <td>
                                    <stan style="color: {{ $trio->color }}"> {{ $trio->minutes }}m <i class="fa fa-clock-o"></i></stan>
                                    {{ $trio->created_at }}
                                </td>
                                <td>
                                    <a href="#" class="teal-text" data-toggle="tooltip" data-placement="top" title="" data-original-title="Make order">
                                        <i class="fa fa-money"></i>
                                    </a>
                                    &nbsp;&nbsp;
                                    <a href="#" class="teal-text" data-toggle="tooltip" data-placement="top" title="" data-original-title="Get status">
                                        <i class="fa fa-check-square-o" aria-hidden="true"></i>
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