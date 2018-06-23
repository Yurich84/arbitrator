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
                            <th>№</th>
                            <th>Название</th>
                        </tr>
                        </thead>

                        @foreach($exchanges as $exchange)
                            <tr>
                                <td>{{ $exchange->id }}</td>
                                <td>{{ $exchange->name }}</td>
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