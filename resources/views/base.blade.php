<!DOCTYPE html>
<html lang="en">

<head>
    <title>Event Organizer</title>
    <link href="{{ asset('public/css/app.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js"
        integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous">
    </script>
    <!-- Tempus Dominus JavaScript -->
    <script src="https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/js/tempus-dominus.js"
        crossorigin="anonymous"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <!-- Tempus Dominus Styles -->
    <link href="https://cdn.jsdelivr.net/gh/Eonasdan/tempus-dominus@master/dist/css/tempus-dominus.css" rel="stylesheet"
        crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-growl/1.0.0/jquery.bootstrap-growl.min.js"></script>

    <link href="{{ asset('resources/css/app.css') }}" rel="stylesheet">

</head>

    @if (session('success'))
        <?php
        echo '<script type="text/javascript">', "$.bootstrapGrowl('" . session('success') . "',  { type: 'success', offset: {from: 'top', amount: 100} });", '</script>';
        ?>
    @endif

    @if (session('error'))
        <?php
        echo '<script type="text/javascript">', "$.bootstrapGrowl('" . session('error') . "',  { type: 'danger', offset: {from: 'top', amount: 100} });", '</script>';
        ?>
    @endif

<body style="background-image: url('{{ asset("public/images/background.webp") }}')">
    <div class="full-height d-flex flex-column">
        <div>
            <nav class="navbar sticky-top navbar-expand-lg navbar-light" style="background-color: white">
                <div class="container-fluid mx-5 py-2">
                    <a class="navbar-brand" href="#">Event Organizer</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav mb-2 mb-lg-0 mx-auto">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('home') }}">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="page" href="{{ route('event.viewAll') }}">Events</a>
                            </li>
                            @role('admin')
                                <li class="nav-item dropdown">
                                    <a class="nav-link active dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Admin
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                        <li><a class="dropdown-item" href="{{ route('users') }}">Manage User Roles</a>
                                        </li>
                                    </ul>
                                </li>
                            @endrole
                        </ul>
                        <div>
                            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                                <li class="nav-item dropdown me-5">
                                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink"
                                        role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fa-solid fa-circle-user fa-xl"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end"
                                        aria-labelledby="navbarDropdownMenuLink">
                                        @auth
                                            @role('admin|moderator|event organiser')
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('event') }}">Create Event</a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('event.viewCreated') }}">View
                                                        Created
                                                        Events</a>
                                                </li>
                                            @endrole
                                            @role('admin|moderator')
                                                <li>
                                                    <a class="dropdown-item"
                                                        href="{{ route('event.moderate.showAll') }}">Moderate
                                                        Events</a>
                                                </li>
                                            @endrole
                                            <li>
                                                <a class="dropdown-item"
                                                    href="{{ route('event.viewParticipating') }}">View
                                                    Participating Events</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('user.signout') }}">Logout</a>
                                            </li>
                                        @else
                                            <li>
                                                <a class="dropdown-item" href="{{ route('login') }}">Log in</a>
                                            </li>
                                        @endauth
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
        <div id="content" class="content w-90 mx-auto my-5 h-100 flex-fill d-flex">
            @yield('content')
        </div>
        <div class="content">
            <footer class="d-flex flex-wrap justify-content-between align-items-center py-1 my-4 w-100">
                <div class="col-md-4 d-flex align-items-center">
                    <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
                        <svg class="bi" width="30" height="24">
                            <use xlink:href="#bootstrap"></use>
                        </svg>
                    </a>
                    <span class="text-muted">2022, Irina Marciuc</span>
                </div>

                <ul class="nav col-md-4 justify-content-end me-4 list-unstyled d-flex">
                    <li class="ms-3"><a class="text-muted" href="#"><i
                                class="fa-brands fa-lg fa-twitter"></i></a></li>
                    <li class="ms-3"><a class="text-muted" href="#"><i
                                class="fa-brands fa-lg fa-instagram"></i></a></li>
                    <li class="ms-3"><a class="text-muted" href="#"><i
                                class="fa-brands fa-lg fa-facebook"></i></a></li>
                </ul>
            </footer>
        </div>
    </div>
</body>

</html>
