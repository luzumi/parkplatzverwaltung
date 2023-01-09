@php use App\Models\User;use Illuminate\Support\Facades\Auth; @endphp
    <!doctype html>
<html lang="de">

<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet"
          crossorigin="anonymous"/>
    <link href="{{ asset('/css/admin.css') }}" rel="stylesheet"/>
    <title>@yield('title', 'Admin - Online Store')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('/css/app.css') }}" rel="stylesheet"/>
</head>

<body>
<topmenu>
    <div class="row g-0 w-75">
        {{-- sidebar --}}
        <div class="p-3 col fixed text-white bg-dark">
            <a href="{{ route('admin.home.index') }}" class="text-white text-decoration-none">
                <span class="fs-4">Admin Panel</span>
            </a>
            <hr/>
            <ul class="nav flex-column">
                <li class="list-active" id="li1">
                    <a href="{{ route('admin.home.index') }}" class="nav-link text-white">
                        <span class="icon"><ion-icon name="home-outline"></ion-icon></span>
                        <span class="text">&nbsp;Home</span>
                    </a>
                </li>
                <li class="list-active">
                    <a href="{{ route("admin.car.index") }}" class="nav-link text-white">
                        <span class="icon"><ion-icon name="car-sport"></ion-icon></span>
                        <span class="text">&nbsp;alle Fahrzeuge</span>
                    </a>
                </li>
                <li class="list-active">
                    <a href="{{ route("admin.messages") }}" class="nav-link text-white">
                        <span class="icon"><ion-icon name="chatbubbles"></ion-icon></span>
                        <span class="text">&nbsp;Nachrichten</span>
                    </a>
                </li>
                <li class="list-active">
                    <a href="{{ route("admin.user.index") }}" class="nav-link text-white">
                        <span class="icon"><ion-icon name="person-circle-outline"></ion-icon></span>
                        <span class="text">&nbsp;User</span>
                    </a>
                </li>
                <li class="list-active">
                    <a href="{{ route("admin.parking_spot.index") }}" class="nav-link text-white">
                        <span class="icon"><ion-icon name="help-buoy-outline"></ion-icon></span>
                        <span class="text">&nbsp;Parkplatz-Übersicht</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('home.index') }}" class="mt-2 btn bg-primary text-white">Go back to the home
                        page</a>
                </li>
            </ul>
        </div>
        <!-- sidebar -->
        <div class="col content-grey">
            <nav class="p-3 shadow text-end">
                <span class="profile-font">Admin</span>
                <form id="logout" action="{{ route('logout') }}" method="POST">
                    <a role="button" class="nav-link active"
                       onclick="document.getElementById('logout').submit();">Logout</a>
                    @csrf
                    <img class="img-profile rounded-circle"
                         src="{{ asset('/storage/media/' . User::findOrFail(Auth::id())->image) }}"
                         alt="Image not Found">
                </form>

            </nav>

            <div class="g-0 m-5 h-75">
                <div><h3>{{$viewData["subtitle"]??''}}</h3></div>
                @yield('content')
                @section('create')
                    @yield('create')
                @endsection
            </div>
        </div>
    </div>
</topmenu>
<!-- footer -->
<div class="copyright py-4 text-center text-white">
    <div class="container">
        <small>
            Copyright - <a class="text-reset fw-bold text-decoration-none" target="_blank"
                           href="https://twitter.com/danielgarax">
                Daniel Correa
            </a> - <b>Paola Vallejo</b>
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
