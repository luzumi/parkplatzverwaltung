@php use Illuminate\Support\Facades\Auth; @endphp
    <!doctype html>
<html lang="de">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous"/>
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet"/>
    <title>@yield('title', 'Parkplatzverwaltung')</title>
</head>
<body>
<!-- header -->
<topmenu>
    <nav class="navigation">
        <a class="h1 text-decoration-none text-white" href="{{ route('home.index') }}">&nbsp;&nbsp;Parkplatzverwaltung&nbsp;&nbsp;&nbsp;&nbsp;</a>
        <div class="container">
            {{--            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"--}}
            {{--                    aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">--}}
            {{--                <span class="navbar-toggler-icon"></span>--}}
            {{--            </button>--}}

            <ul>
                <li class="list active">
                    <a class="nav-link active" href="{{ route('home.index') }}">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="text">Home</span>
                    </a>
                </li>
                @if (auth()->check())
                    <li class="list">
                        <a class="nav-link active" href="{{ route('user.show', (Auth::check())? Auth::id() : 0) }}">
                            <span class="icon"><ion-icon name="person-circle-outline"></ion-icon></span>
                            <span class="text">Profil</span>
                        </a>
                    </li>
                    <li class="list">
                        <a class="nav-link active" href="{{ route('messages') }}">
                            <span class="icon"><ion-icon name="chatbubbles"></ion-icon></span>
                            <span class="text">Nachrichten</span>
                        </a>
                    </li>
                    <li class="list">
                        <a class="nav-link active" href="{{ route('user.addCar.index') }}">
                            <span class="icon"><ion-icon name="car-sport"></ion-icon></span>
                            <span class="text">AddCar</span>
                        </a>
                    </li>
                @endif
                <li class="list">
                    <a class="nav-link active" href="{{ route('parking_spot.index') }}">
                        <span class="icon"><ion-icon name="help-buoy-outline"></ion-icon></span>
                        <span class="text">Parkplatz-<br>Übersicht</span>
                    </a>
                </li>
                <li class="list">
                    <a class="nav-link active" href="{{ route('home.about') }}">
                        <span class="icon"><ion-icon name="information-circle"></ion-icon></span>
                        <span class="text">About</span>
                    </a>
                </li>
                @guest
                    <li class="list">
                        <a class="nav-link active" href="{{ route('login') }}">
                            <span class="icon"><ion-icon name="log-in"></ion-icon></span>
                            <span class="text">Login</span>
                        </a>
                    </li>
                    <li class="list">
                        <a class="nav-link active" href="{{ route('register') }}">
                            <span class="icon"><ion-icon name="person-add"></ion-icon></span>
                            <span class="text">Register</span>
                        </a>
                    </li>
                @else
                    <li class="list">
                        <form id="logout" action="{{ route('logout') }}" method="POST">
                            <a role="button" class="nav-link active"
                               onclick="document.getElementById('logout').submit();">
                                @csrf
                                <span class="icon"><ion-icon name="log-out"></ion-icon></span>
                                <span class="text">Logout</span>
                            </a>
                        </form>
                        <img class="img-profile rounded-circle  col-1"
                             src=" {{asset( '/storage/media/'. (Auth::user()->image ?? 'undraw_profile.svg')) }} "
                             alt="z">
                    </li>
                @endguest
                <div class="indicator"></div>
            </ul>
        </div>
    </nav>
</topmenu>
<header class="masthead bg-primary text-white text-center py-4">
    <div class="container d-flex align-items-center flex-column">
        <h2>@yield('subtitle', 'Laravel Parkplatzverwaltung')</h2>
    </div>
</header>
<!-- header -->

<div class="container my-4">
    @yield('content')
</div>

<!-- footer -->
<div class="copyright py-4 text-center text-white">
    <div class="container">
        <small>
            Copyright - <a class="text-reset fw-bold text-decoration-none" target="_blank"
                           href="https://twitter.com/danielgarax">
                Daniel
            </a> - <b>luzumi</b>
        </small>
    </div>
</div>

<!-- footer -->
<script>
    const list = document.querySelectorAll('.list');

    function activeLink() {
        list.forEach((item) =>
            item.classList.remove('active')
        );
        this.classList.add('active');
    }

    list.forEach((item) =>
        item.addEventListener('mouseover', activeLink)
    );
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        crossorigin="anonymous"></script>
<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>
