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

        <div class="city-favorites">
            <div class="container mt-5">
                <ul class="list-group list-group-horizontal justify-content-center flex-wrap">
                    {{--                // uzimamo 100 prognoza iz (WeatherModel::all();) * optimizovano sa with()--}}
                    @foreach($prognoza->take(100) as $weather)
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-sun text-warning me-2"></i>
                            {{ $weather->city->name }} {{  }} {{ $weather->temperature }}°C
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
</div>
@endsection

