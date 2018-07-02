@extends('admin.main')

@section('name', 'Тройка' . $data->symbol)

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="tile">
                <div class="tile-body">

                    <p>Time: {{ $data->created_at }}</p>
                    <p>Биржа: {{ $data->stock->name }}</p>
                    <p>Min: $ {{ $data->min }}</p>
                    <p>Profit: {{ $data->profit }} %</p>
                    <div>{!! $data->comment !!}</div>

                    <br/>

                    <div class="row">
                        @foreach(json_decode($data->pairs) as $pair)
                            <div class="col-4">
                                <table class="table table-sm table-striped">
                                    <tr><td> </td> <td>{{ $pair->base_curr }} / {{ $pair->quote_curr }} </td></tr>
                                    <tr><td>bid: </td> <td>{{ $pair->bid }} </td></tr>
                                    <tr><td>ask: </td> <td>{{ $pair->ask }} </td></tr>
                                    <tr><td>min_bid: </td> <td>{{ $pair->min_bid }} </td></tr>
                                    <tr><td>min_ask: </td> <td>{{ $pair->min_ask }} </td></tr>
                                    <tr><td>min_bid $: </td>

                                        @php
                                            $price = \App\Models\Rate::where('symbol', $pair->base_curr)->first()->price
                                        @endphp
                                        <td>
                                            {{ $pair->min_bid }} x {{ $price }} <br/>
                                            {{ $pair->min_bid * $price }}
                                        </td></tr>
                                    <tr><td>min_ask $: </td>
                                        <td>
                                            {{ $pair->min_ask }} x {{ $price }} <br/>
                                            {{ $pair->min_ask * $price }}
                                        </td></tr>
                                </table>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection