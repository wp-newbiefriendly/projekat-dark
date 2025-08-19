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
            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
        </select>
    </form>

    <h2 class="mb-4">Lista Gradova ({{ $weather->total() }})</h2>

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
        @foreach($weather as $weather)
            <tr>
                <td>{{ $weather->id }}</td>
                <td>{{ $weather->city }}</td>
                <td>{{ $weather->temperatures }}</td>
                <td>
                    <a href="{{ route('editCities', ['weather' => $weather->id]) }}" class="btn btn-sm btn-primary">Izmeni</a>
                    <a href="{{ route('deleteCities', ['weather' => $weather->id]) }}" class="btn btn-sm btn-danger">Obriši</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
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
                    <td>{{ $trashed->city }}</td>
                    <td>{{ $trashed->temperatures }}</td>
                    <td>
                        <a href="{{ url('/admin/cities/undo/'.$trashed->id) }}" class="btn btn-success btn-sm">Vrati</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif



@endsection
