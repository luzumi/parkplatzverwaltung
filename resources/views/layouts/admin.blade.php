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
</head>

<body>
<div class="row g-0">
    <!-- sidebar -->
    <div class="p-3 col fixed text-white bg-dark">
        <a href="{{ route('admin.home.index') }}" class="text-white text-decoration-none">
            <span class="fs-4">Admin Panel</span>
        </a>
        <hr/>
        <ul class="nav flex-column">
            <li><a href="{{ route('admin.home.index') }}" class="nav-link text-white">- Admin - Home</a></li>
{{--            <li><a href="{{ route("admin.car.index") }}" class="nav-link text-white">- Admin - alle Fahrzeuge</a></li>--}}
            <li><a href="{{ route("admin.car.index") }}" class="nav-link text-white">- Admin - Fahrzeuge</a></li>
            <li><a href="{{ route("user.index") }}" class="nav-link text-white">- Admin - alle Users</a></li>
            <li><a href="{{ route("admin.user.index") }}" class="nav-link text-white">- Admin - User</a></li>
            <li><a href="{{ route("admin.parking_spot.index") }}" class="nav-link text-white">- Admin - Parkplätze</a>
            </li>

            <li>
                <a href="{{ route('home.index') }}" class="mt-2 btn bg-primary text-white">Go back to the home page</a>
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
                {{--            <span class="profile-font">--}}{{--<a href="{{ route('logout.perform') }}" > Logout </a>--}}{{--</span>--}}
                <img class="img-profile rounded-circle"
                     src="{{ asset('/storage/media/' . User::findOrFail(Auth::id())->image) }}"
                     alt="Image not Found">
            </form>

        </nav>

        <div class="g-0 m-5">
            @yield('content')
        </div>
    </div>
</div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
</script>
</body>

</html>
