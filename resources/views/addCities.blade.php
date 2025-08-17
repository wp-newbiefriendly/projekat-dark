@extends('layouts.adminlayout')

@section('title', 'Dodaj Grad')

@section('content')
    <h2 class="mb-4">➕ Dodaj Novi Grad</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>ayo
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/admin/add-cities" enctype="multipart/form-data" class="w-75">
        @csrf

        <div class="mb-3">
            <label for="city" class="form-label">Ime Grada</label>
            <input type="text" name="city" class="form-control" placeholder="Grad" value="{{ old('city') }}" required>
        </div>

        <div class="mb-3">
            <label for="temperatures" class="form-label">Temperatura</label>
            <input type="number" name="temperatures" id="temperatures" class="form-control" placeholder="Unesi temperaturu"
                   value="{{ old('temperatures') }}" required>
        </div>


        <button type="submit" class="btn btn-success me-2">✅ Dodaj grad</button>
        <a href="/admin/cities" class="btn btn-secondary">Nazad na listu</a>
    </form>
@endsection
