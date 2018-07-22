@extends('admin.main')

@section('name', 'Мои биржи')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <a class="btn btn-primary" href="{{ route('admin.key.create') }}">Добавить ключ</a>

                    <br/><br/>
                    
                    <table class="table table-bordered table-striped" id="logs-table">
                        <thead>
                        <tr>
                            <th width="30">№</th>
                            <th></th>
                            <th>Биржа</th>
                            <th>Key</th>
                            <th>Secret</th>
                        </tr>
                        </thead>

                        @foreach($keys as $key)
                            <tr>
                                <td>{{ $key->id }}</td>
                                <td>{{ Html::image( '/imgs/stocks/' . $key->stock->logo) }}</td>
                                <td>{{ link_to($key->stock->www, $key->stock->name, ['target' => '_blink']) }}</td>
                                <td>{{ $key->key }}</td>
                                <td>{{ $key->secret }}</td>
                                <td>
                                    {!! link_to_route('admin.key.edit', '', ['id' => $key->id],
                                    ['class' => 'fa fa-pencil']) !!}
                                </td>
                                <td>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['admin.key.destroy', $key->id]]) !!}
                                    <a href="#" class="data-delete">
                                        <i class="fa fa-trash text-danger"></i>
                                    </a>
                                    {{ Form::close() }}
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
        $(function () {
            $('.data-delete').on('click', function (e) {
                if (!confirm('Are you sure you want to delete?')) return;
                e.preventDefault();
                $(this).parent().submit();
            });
        });
    </script>
@endsection