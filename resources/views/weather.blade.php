@extends('layout')

@section('title', 'Prognoza')

@section('sadrzajstranice')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Prognoza</h1>
        <p class="text-center">Sve informacije o dnevnoj prognozi</p>
        <hr class="solid">

        <div class="container mt-5">
            <ul class="list-group list-group-horizontal justify-content-center flex-wrap">
                @foreach($prognoza->take(100) as $weather)
                    <li class="list-group-item d-flex align-items-center">
                        <i class="fas fa-sun text-warning me-2"></i>
                        {{ $weather->city->name }} {{ $weather->temperature }}°C
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
