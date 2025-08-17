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
    <form method="POST" action="{{ route('updateCities', ['city' => $city->id]) }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="city" class="form-label">Naziv Grada</label>
            <input type="text" name="city" class="form-control"
                   value="{{ old('city', $city->city ?? '') }}"
                   placeholder="Naziv" required>
        </div>

        <div class="mb-3">
            <label for="temperatures" class="form-label">Temperatura</label>
            <input type="number" name="temperatures" class="form-control"
                   value="{{ old('temperatures', $city->temperatures ?? '') }}"
                   placeholder="Temperatura" required>
        </div>

        <button type="submit" class="btn btn-primary me-2">Sačuvaj izmene</button>
        <a href="/admin/cities" class="btn btn-secondary">Nazad na listu</a>
    </form>
@endsection
