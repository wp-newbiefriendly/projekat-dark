@extends('layouts.adminlayout')

@section('title', 'Dodaj Grad')

@section('content')
    <h2 class="mb-4">➕ Dodaj Novi Grad</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/admin/add-cities" enctype="multipart/form-data" class="w-75">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Ime Grada</label>
            <input type="text" name="name" class="form-control" placeholder="Grad"
                   value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="temperature" class="form-label">Temperatura</label>
            <input type="number" name="temperature" id="temperature" class="form-control"
                   placeholder="Unesi temperaturu"
                   value="{{ old('temperature') }}" required>
        </div>


        <button type="submit" class="btn btn-success me-2">✅ Dodaj grad</button>
        <a href="/admin/cities" class="btn btn-secondary">Nazad na listu</a>
    </form>
@endsection
