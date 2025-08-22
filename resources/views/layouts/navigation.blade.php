<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid align-items-center justify-content-between">

        <!-- Levo: Logo -->
        <a class="logo-prognoza navbar-brand mb-0 h1" href="/">Prognoza Sajt</a>

        <!-- Toggler (za mobilni meni) -->
        <button class="navbar-toggler order-1 mx-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMenu"
                aria-controls="navbarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Sredina: Meni -->
        <div class="collapse navbar-collapse justify-content-center order-2" id="navbarMenu">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/admin/cities">🛠 Admin</a></li>
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="/prognoza"><i class="fas fa-sun text-warning"></i>
                        Prognoza</a></li>
            </ul>
        </div>

        <!-- Desno: Login/Register + GitHub -->
        <div class="d-flex align-items-center gap-2 order-3">

            @auth
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-light">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-sm btn-outline-light">Login</a>
                <a href="{{ route('register') }}" class="btn btn-sm btn-light">Register</a>
            @endauth

            <a href="https://github.com/wp-newbiefriendly/projekat-dark" target="_blank"
               class="text-white text-decoration-underline">
                GITHUB <i class="bi bi-github"></i>
            </a>
        </div>

    </div>
</nav>
