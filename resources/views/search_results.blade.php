@extends('layouts.layout')

@section('sadrzajstranice') {{-- ili 'content' --}}
<div class="container py-5">
    <h3 class="text-center mb-4">Rezultati  ({{ $cities->count() }})</h3>

    <div class="city-grid">
        @foreach($cities as $city)
            @php
                $fc = $city->todaysForecast;
                $icon = $fc ? \App\Http\ForecastHelper::getWeatherData($fc->weather_type, $fc->temperature)['icon'] : '';
            @endphp
            <div class="d-flex align-items-center gap-2">
                <button class="fav-btn" href="{{ route('city_favorite', ['city' => $city->id]) }}">
                    <i class="fa-regular fa-heart"></i>
                </button>
                <span class="btn btn-primary rounded-pill px-3 py-2">
                <i class="{{ $icon }}"></i> {{ $city->name }}
            </span>
            </div>
        @endforeach
    </div>

    {{-- Ako koristiš paginate() umesto get(): --}}
    @if(method_exists($cities, 'links'))
        <div class="mt-4 d-flex justify-content-center">
            {{ $cities->links() }}
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('home') }}" class="text-decoration-none">← Nazad na početnu</a>
    </div>
</div>
@endsection
