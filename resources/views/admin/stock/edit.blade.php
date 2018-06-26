@extends('admin.main')

@section('name', 'Редактировать ' . $item->name)

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    {!! Form::model($item, ['route' => ['admin.stock.update', $item->id], 'method' => 'PUT' ] ) !!}

                    @include('admin.stock._form')

                    <div class="form-group">
                        {!! Form::submit('Сохранить', ['class' => 'btn btn-primary']) !!}
                    </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection