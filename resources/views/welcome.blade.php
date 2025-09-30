@section("title")
    Home
@endsection

@extends('layouts.layout')

@section('sadrzajstranice') {{-- ili 'content' ako tako zoveš u layoutu --}}
<div class="container py-5">

    @if(session('error'))
        <div class="alert alert-warning text-center mb-4">
            {{ session('error') }}
        </div>
    @endif

    <div class="text-center mb-4">
        <h1 class="display-6 fw-bold mb-1">Pretraga</h1>
        <p class="text-muted mb-0">Ukucaj slovo ili ime grada pa klikni Pronađi</p>
    </div>

    <form method="GET" action="{{ route('search.city') }}" class="d-flex justify-content-center">
        <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden" style="max-width: 720px;">
            <input type="text" name="city" class="form-control border-0 px-4"
                   placeholder="Unesite slovo ili ime grada" value="{{ old('city') }}">
            <button class="btn btn-primary d-flex align-items-center gap-2 px-4" type="submit">
                <i class="bi bi-search"></i><span>Pronađi</span>
            </button>
        </div>
    </form>
        @auth
            @if($favoriteCities->isNotEmpty())
        <div class="text-center mb-1 mt-5">
            <h1 class="display-6 fw-bold mb-1">
                <i class="fa-regular fa-heart text-danger fa-xs"></i> Favorites</h1>

            <div class="container mt-2 shadow-lg">
                <ul class="list-group list-group-horizontal justify-content-center flex-wrap p-4">
                    @foreach($favoriteCities as $userFav)
                        @php
                            $fc = $userFav->todaysForecast;
                            $icon = $fc ? \App\Http\ForecastHelper::getWeatherData($fc->weather_type, $fc->temperature)['icon'] : '';
                        @endphp
                        <li class="list-group-item m-2">
                            <div class="city-info">
                                <h5>{{ $userFav->name }}</h5>
                                <h2><i class="{{ $icon }}"></i> {{ $userFav->todaysForecast->temperature }}°C</h2>
                                <p><small><i class="fa-solid fa-droplet"></i> {{ $userFav->todaysForecast->probability ?? 0 }}%</small></p>
                                <p>{{ $userFav->todaysForecast->forecast_date }}</p>
                                <a href="{{ route('city_favorite', $userFav->id) }}" class="text-decoration-none" title="Ukloni iz omiljenih">
                                    <i class="fa-solid fa-heart text-danger icon-hover"></i>
                                </a>
                            </div>
                        </li>
                    @endforeach
                  </ul>
               </div>
            </div>
         </div>
     @endif
    @endauth
@endsection

