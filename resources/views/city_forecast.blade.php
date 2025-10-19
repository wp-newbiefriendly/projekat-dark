@extends('layouts.layout')

@section('sadrzajstranice')
    <div class="container">
            <h1 class="text-center mb-4 mt-5"> Prognoza danas: {{ $city->name }}</h1>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Temp (°C)</th>
                        <th>Vreme</th>
                        <th>Šansa za kišu (%)</th>
                        <th>Vreme izlaska sunca</th>
                        <th>Vreme zalaska sunca</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($city->forecasts as $forecast)
                    <tr>
                        <td>{{ $forecast->forecast_date }}</td>
                        <td class="{{ $forecast->temp_class }}">{{ $forecast->temperature }}</td>
                        <td>{{ $forecast->weather_type }}</td>
                        <td>{{ $forecast->chance_of_rain }}</td>
                        <td> {{ $sunrise }}</td>
                        <td> {{ $sunset }}</td>

                    </tr>
                    @endforeach
                    </tbody>
                </table>
    </div>
@endsection
