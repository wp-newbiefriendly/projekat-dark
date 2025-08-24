@extends('layouts.adminlayout')

@if (session('success'))
    <div class="alert alert-success text-center mx-auto">
        {{ session('success') }}
    </div>
@endif

@section('title', 'Prognoza')

@section('content')
    <div class="container-fluid">

        {{-- Toolbar: Prikaži / Sortiraj --}}
        <form method="GET" action="{{ url()->current() }}" class="d-flex flex-wrap align-items-center gap-3 mb-4">
            <div class="d-flex align-items-center gap-2">
                <label for="per_page" class="fw-bold mb-0">Prikaži:</label>
                <select name="per_page" id="per_page" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="12"  {{ request('per_page') == 12  ? 'selected' : '' }}>10</option>
                    <option value="50"  {{ request('per_page') == 50  ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            <div class="d-flex align-items-center gap-2">
                <label for="sort" class="fw-bold mb-0">Sortiraj po:</label>
                <select name="sort" id="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                    <option value="asc"  {{ $sort == 'asc'  ? 'selected' : '' }}>Stari → Novi</option>
                    <option value="desc" {{ $sort == 'desc' ? 'selected' : '' }}>Novi → Stari</option>
                </select>
            </div>
        </form>

        {{-- Naslov + Brzo editovanje --}}
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-3">
            <h2 class="mb-0">Lista prognoza</h2>

            <form action="{{ route('forecasts.update') }}" method="POST"
                  class="quick-add-form d-flex flex-wrap align-items-center gap-2 p-2 rounded shadow-sm border">
                @csrf

                <span class="fw-bold me-1">➕ Brzo editovanje:</span>

                <select name="city_id" class="form-select form-select-sm w-auto">
                    @foreach ($allCities as $city)
                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                    @endforeach
                </select>

                <input type="number" name="temperature" class="form-control form-control-sm w-auto" placeholder="Temperatura" required>

                <select name="weather_type" class="form-select form-select-sm w-auto" required>
                    @foreach (\App\Models\ForecastModel::WEATHERS as $weathertype)
                        <option value="{{ $weathertype }}">{{ ucfirst($weathertype) }}</option>
                    @endforeach
                </select>

                <input type="number" name="probability" class="form-control form-control-sm w-auto" placeholder="Šansa padavina" required>

                <input type="date" name="forecast_date" class="form-control form-control-sm w-auto" required>

                <button type="submit" class="btn btn-success btn-sm">Snimi</button>
            </form>
        </div>

        {{-- Grid kartica po gradovima --}}
        <div class="row g-3">
            @foreach ($allCities as $city)
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="card forecast-card h-100">
                        <div class="card-header py-2 d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">{{ $city->name }}</span>
                            <span class="count-badge">{{ ($city->forecasts ?? collect())->count() }}</span>
                        </div>
                        <div class="card-body py-2">
                            <ul class="forecast-list mb-0">
                                @forelse ($city->forecasts ?? collect() as $forecast)
                                    <li class="d-flex justify-content-between">
                                        <span>{{ \Illuminate\Support\Carbon::parse($forecast->forecast_date)->toDateString() }}</span>
                                        <span class="temp-badge">{{ $forecast->temperature }}°</span>
                                    </li>
                                @empty
                                    <li class="text-muted">Nema prognoza.</li>
                                @endforelse

                            </ul>
                        </div>
                    </div>
                </div>
            @endforeach
                {{ $allCities->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endsection
