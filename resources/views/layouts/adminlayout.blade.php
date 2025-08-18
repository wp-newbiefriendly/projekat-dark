<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/adminlayout.css') }}">
</head>
<body>
<!-- Topbar -->
<nav class="navbar navbar-dark bg-dark justify-content-between px-3">
    <span class="navbar-brand mb-0 h4">🛠 Admin Panel</span>

    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm">
            Logout
        </button>
    </form>
</nav>

<div class="d-flex">
    <!-- Sidebar -->
    <div class="bg-light border-end" style="width: 220px; min-height: 100vh;">
        <div class="p-3">
            <h4>Dashboard</h4>
        </div>
        <label class="theme-toggle">
            <input type="checkbox" id="darkModeSwitch">
            <span class="slider">
    <span class="icon sun">☀️</span>
    <span class="icon moon">🌙</span>
  </span>
        </label>
        <div class="list-group list-group-flush">
            <a href="/" class="list-group-item list-group-item-action">🏠 Pocetna stranica</a>
            <a href="/prognoza" class="list-group-item list-group-item-action">☀️ Prognoza</a>
            <hr style="margin: 15px 0px 15px;">
            <a href="/admin/cities" class="list-group-item list-group-item-action">Svi Gradovi</a>
            <a href="/admin/add-cities" class="list-group-item list-group-item-action">➕ Dodaj Grad</a>
            <hr style="margin: 15px 0px 15px;">
        </div>
    </div>

    <!-- Glavni deo stranice -->
    <div class="flex-grow-1 p-4">
        @yield('content')
    </div>
</div>
<script>
    const toggle = document.getElementById('darkModeSwitch');

    toggle.addEventListener('change', function () {
        document.body.classList.toggle('dark-mode');

        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('darkMode', 'enabled');
        } else {
            localStorage.setItem('darkMode', 'disabled');
        }
    });

    window.onload = function () {
        if (localStorage.getItem('darkMode') === 'enabled') {
            document.body.classList.add('dark-mode');
            toggle.checked = true;
        }
    }
</script>

