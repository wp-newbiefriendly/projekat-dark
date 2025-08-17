@extends('layouts.adminlayout')

@if (session('success'))
    <div class="alert alert-success text-center mx-auto">
        {{ session('success') }}
    </div>
@endif

@section('title', 'Svi Gradovi')

@section('content')

    <h2 class="mb-4">Lista Gradova</h2>

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
                <td>{{ $city->city }}</td>
                <td>{{ $city->temperatures }}</td>
                <td>
                    <a href="{{ route('editCities', ['city' => $city->id]) }}" class="btn btn-sm btn-primary">Izmeni</a>
                    <a href="{{ route('deleteCities', ['city' => $city->id]) }}" class="btn btn-sm btn-danger">Obriši</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Obrisani gradovi --}}
    <h3 class="mt-5">🗑️ Obrisani Gradovi ({{ $trashedCities->count() }})</h3>
    @if($trashedCities->count())
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>Naziv</th>
                <th>Temperatura</th>
                <th>Akcija</th>
            </tr>
            </thead>
            <tbody>
            @foreach($trashedCities as $trashed)
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
