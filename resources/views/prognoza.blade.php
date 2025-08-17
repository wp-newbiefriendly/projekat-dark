@section('title')
    Prognoza
@endsection

@extends('layout')

@section('sadrzajstranice')
    <div class="container mt-5">
        <h1 class="text-center mb-4">Prognoza</h1>
        <p class="text-center">Sve informacije o dnevnoj prognozi</p>
        <hr class="solid">
        <div class="container mt-5">
            <ul class="list-group list-group-horizontal justify-content-center">
                <li class="list-group-item">
                    <i class="fas fa-sun text-warning"></i> Beograd
                </li>
                <li class="list-group-item">
                    <i class="fas fa-sun text-warning"></i> Novi Sad
                </li>
                <li class="list-group-item">
                    <i class="fas fa-sun text-warning"></i> Zagreb
                </li>
                <li class="list-group-item">
                    <i class="fas fa-sun text-warning"></i> Sarajevo
                </li>
                <li class="list-group-item">
                    <i class="fas fa-sun text-warning"></i> Podgorica
                </li>
            </ul>
        </div>



@endsection
