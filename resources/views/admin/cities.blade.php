@extends('layouts.adminlayout')

@if (session('success'))
    <div class="alert alert-success text-center mx-auto">
        {{ session('success') }}
    </div>
@endif

@section('title', 'Svi Gradovi')

@section('content')

    <form method="GET" action="{{ url()->current() }}" class="d-flex align-items-center gap-3 mb-3">

        <div class="d-flex align-items-center gap-2">
            <label for="per_page" class="fw-bold mb-0">Prikaži:</label>
            <select name="per_page" id="per_page" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
            </select>
        </div>

        <div class="d-flex align-items-center gap-2">
            <label for="sort" class="fw-bold mb-0">Sortiraj po:</label>
            <select name="sort" id="sort" class="form-select form-select-sm w-auto" onchange="this.form.submit()">
                <option value="asc" {{ $sort == 'asc' ? 'selected' : '' }}>Stari → Novi</option>
                <option value="desc" {{ $sort == 'desc' ? 'selected' : '' }}>Novi → Stari</option>
            </select>
        </div>

    </form>



    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Lista Gradova ({{ $totalCities }})</h2>

        <form method="POST" action="{{ url('/admin/add-cities') }}"
              class="d-flex align-items-center gap-2 p-2 rounded shadow-sm quick-add-form">
            @csrf

            <label class="fw-bold quick-label me-2 mb-0 h5">➕ Brzo dodavanje grada:</label>

            <input type="text" name="name" class="form-control w-auto" placeholder="Ime grada" required>
            <input type="number" name="temperature" class="form-control w-auto" placeholder="Temperatura" required>
            <button type="submit" class="btn btn-success">Dodaj</button>
        </form>

    </div>

    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>ID</th>
            <th>Grad</th>
            <th>Temperatura</th>
            <th>Akcija</th>
        </tr>
        </thead>
        <tbody>
        @foreach($cities as $city)
            <tr>
                <td>{{ $city->id }}</td>
                <td>{{ $city->name }}</td>
                <td>{{ $city->weather->temperature ?? 'N/A' }}</td>
                <td>
                    <a href="{{ route('editCities', $city->id) }}" class="btn btn-sm btn-primary">Izmeni</a>
                    <a href="{{ route('deleteCities', $city->id) }}" class="btn btn-sm btn-danger">Obriši</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    {{ $cities->appends(['per_page' => request('per_page')])->links('pagination::bootstrap-5') }}

    {{-- Obrisani gradovi --}}
    <h3 class="mt-5">🗑️ Obrisani Gradovi ({{ $trashedWeather->count() }})</h3>
    @if($trashedWeather->count())
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Naziv</th>
                <th>Temperatura</th>
                <th>Akcija</th>
            </tr>
            </thead>
            <tbody>
            @foreach($trashedWeather as $trashed)
                <tr>
                    <td>{{ $trashed->city->name ?? '-' }}</td>
                    <td>{{ $trashed->temperature }}</td>
                    <td>
                        <a href="{{ url('/admin/cities/undo/'.$trashed->id) }}" class="btn btn-success btn-sm">Vrati</a>
                        <a href="{{ route('forceDeleteCity', $trashed->id) }}"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Da li ste sigurni da zelite trajno da obrisete grad?')">
                            Obriši zauvek
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif

@endsection
