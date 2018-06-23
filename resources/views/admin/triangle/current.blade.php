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
                            <th>Id</th>
                            <th>Биржа</th>
                            <th>Тройка</th>
                            <th>Профит</th>
                            <th>Время</th>
                        </tr>
                        </thead>

                        @foreach($triangles as $trio)
                            <tr>
                                <td>{{ $trio->id }}</td>
                                <td>{{ $trio->exchange->name }}</td>
                                <td>{{ $trio->symbol }}</td>
                                <td>{{ $trio->profit }} %</td>
                                <td>{{ $trio->created_at }}</td>
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