@extends('layouts.adminlayout')

@if (session('success'))
    <div class="alert alert-success text-center mx-auto">
        {{ session('success') }}
    </div>
@endif

@section('title', 'Svi Gradovi')

@section('content')

    <form method="GET" action="{{ url()->current() }}">
        <label for="per_page">Prikaži:</label>
        <select name="per_page" id="per_page" onchange="this.form.submit()">
            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
        </select>
    </form>

    <h2 class="mb-4">Lista Gradova ({{ $totalCities }})</h2>


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
                <td>{{ $city->temperature }}</td>
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
