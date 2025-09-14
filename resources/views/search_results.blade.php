@extends('layouts.layout')

@section('sadrzajstranice') {{-- ili 'content' --}}

@if(session('error'))
    <div class="alert alert-danger alert-top d-flex justify-content-center align-items-center">
        <span class="me-3">{{ session('error') }}</span>
        <a href="{{ route('login') }}" class="btn btn-sm btn-light">Login</a>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="container py-5">
    <h3 class="text-center mb-4">Rezultati  ({{ $cities->count() }})</h3>

    <div class="city-grid">
        @foreach($cities as $city)
            @php
                $fc = $city->todaysForecast;
                $icon = $fc ? \App\Http\ForecastHelper::getWeatherData($fc->weather_type, $fc->temperature)['icon'] : '';
            @endphp

            @php $isFav = in_array($city->id, $favoriteCityIds); @endphp

            <div class="d-flex align-items-center gap-2">
                <a class="fav-btn {{ $isFav ? 'is-fav' : '' }}"
                   href="{{ route('city_favorite', ['city_id' => $city->id]) }}"
                   aria-pressed="{{ $isFav ? 'true' : 'false' }}"
                   title="{{ $isFav ? 'Ukloni iz favorita' : 'Dodaj u favorite' }}">
                    <i class="{{ $isFav ? 'fa-solid' : 'fa-regular' }} fa-heart"></i>
                </a>
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
