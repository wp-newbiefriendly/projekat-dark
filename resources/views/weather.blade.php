@extends('layout')

@section('title', 'Prognoza')

    @section('sadrzajstranice')
        <div class="container mt-5">
            <h1 class="text-center mb-4">Prognoza</h1>
            <p class="text-center">Sve informacije o dnevnoj prognozi</p>
            <hr class="solid">

            <div class="container mt-5">
                <ul class="list-group list-group-horizontal justify-content-center">
                    @foreach($cities as $city)
                        <li class="list-group-item">
                            <i class="fas fa-sun text-warning"></i>
                            {{ $city->city }} {{ $city->temperatures }}°C
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endsection
