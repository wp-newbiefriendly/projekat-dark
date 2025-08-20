@extends('layouts.adminlayout')

@section('title', 'Izmeni Grad')

@section('content')
    <h2 class="mb-4">✏️ Izmeni Grad</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Forma za editovanje, koristimo metodu PUT -->
    <form action="{{ route('updateCities', $cities->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="city_name" class="form-label">Naziv Grada</label>
            <input type="text" name="city_name" class="form-control"
                   value="{{ old('city_name', $cities->city->name ?? '') }}"
                   placeholder="Naziv" required>
        </div>

        <div class="mb-3">
            <label for="temperature" class="form-label">Temperatura</label>
            <input type="number" name="temperature" class="form-control"
                   value="{{ old('temperature', $cities->temperature ?? '') }}"
                   placeholder="Temperatura" required>
        </div>

        <button type="submit" class="btn btn-primary me-2">Sačuvaj izmene</button>
        <a href="/admin/cities" class="btn btn-secondary">Nazad na listu</a>
    </form>
@endsection
