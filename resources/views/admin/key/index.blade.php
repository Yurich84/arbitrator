@extends('admin.main')

@section('name', 'Мои биржи')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">
                    <table class="table table-bordered table-striped" id="logs-table">
                        <thead>
                        <tr>
                            <th width="30">№</th>
                            <th>Название</th>
                        </tr>
                        </thead>

                        @foreach($configs as $config)
                            <tr>
                                <td>{{ $config->id }}</td>
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