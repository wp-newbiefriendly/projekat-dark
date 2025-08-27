@extends('layouts.layout')

@section('sadrzajstranice') {{-- ili 'content' --}}
<div class="container py-5">
    <h3 class="text-center mb-4">Rezultati  ({{ $cities->count() }})</h3>

    <div class="d-flex flex-wrap gap-3 justify-content-center">
        @foreach($cities as $city)
            @php
                $fc = $city->todaysForecast;
                $icon = $fc ? \App\Http\ForecastHelper::getWeatherData($fc->weather_type, $fc->temperature)['icon'] : '';
            @endphp
            <span class="btn btn-primary rounded-pill px-3 py-2"><i class="{{ $icon }}"></i> {{ $city->name }}</span>
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
