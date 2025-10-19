@extends('layouts.layout')

@section('sadrzajstranice')
    <div class="container">
        @if(isset($message))
            <div class="alert alert-warning">{{ $message }}</div>
            <a href="{{ url('/') }}">← Nazad na početnu</a>
        @elseif($city)
            <h2>Prognoza danas: {{ $city->name }}</h2>

            @if(!$forecast)
                <p>Nema današnjih podataka u bazi.</p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>Datum</th>
                        <th>Temp (°C)</th>
                        <th>Vreme</th>
                        <th>Šansa za kišu (%)</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ $forecast->forecast_date }}</td>
                        <td class="{{ $forecast->temp_class }}">{{ $forecast->temperature }}</td>
                        <td>{{ $forecast->weather_type }}</td>
                        <td>{{ $forecast->chance_of_rain }}</td>
                    </tr>
                    </tbody>
                </table>
            @endif
        @endif
    </div>
@endsection
