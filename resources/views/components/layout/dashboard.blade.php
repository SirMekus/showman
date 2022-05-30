<!DOCTYPE html>
<html>

<head>
    <title>{{ $title ?? config('app.name') }}</title>
    <x-head />

    @stack('styles')
</head>

<body>
    <div class='{{$parent_class ?? ' container'}}'>
        <div class='row justify-content-center'>

            <div class="col-lg-10 col-sm-10 col-md-10">

                <div class="bg-dark offcanvas offcanvas-start" tabindex="-1" id="offcanvasStart"
                    aria-labelledby="offcanvasTopLabel">

                    <div class="offcanvas-header">
                        <h5 id="offcanvasTopLabel" class="'text-light">{{ config("app.name") }}</h5>
                        <button type="button" class="btn-close text-reset bg-light" data-bs-dismiss="offcanvas"
                            aria-label="Close"></button>
                    </div>

                    <div class="offcanvas-body">
                        <nav class="nav flex-column">
                            <a class="nav-link font-weight-bold text-light" href="{{route("home")}}">
                                <i class="fas fa-warehouse"></i>Homepage
                            </a>

                            <div style="border-bottom: medium solid white; height:8px;"></div>


                            <a class="nav-link font-weight-bold text-light anchor-tags" href="{{route("dashboard.branch")}}"><i
                                    class="fas fa-home"></i>Dashboard</a>

                            @if(request()->user())
                            <a class="nav-link font-weight-bold text-light anchor-tags pre-run"
                                data-caption='Are you sure you want to log out?' data-classname="logout"
                                href="{{ route('logout') }}"><i class="fas fa-sign-out-alt"></i></span>Log Out</a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST">
                                @csrf
                            </form>
                            @endif
                        </nav>
                    </div>

                </div>




                <!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - Dashboard Menu ends here - - - - - - - -  - - - - - - - - - - - - - - - - - - - - - - - -->







                <nav class="nav navbar navbar-expand nav-fill justify-content-center home-color sticky-top"
                    role="navigation">
                    <div class="bg-light" style="height:26px; width:35px; z-index:1000; position:absolute; left:8px;">
                        <a id="dashboard-menu-toggle" href="#" data-bs-toggle="offcanvas"
                            data-bs-target="#offcanvasStart" aria-controls="offcanvasStart">
                            <div style="border-bottom: medium solid black; height:8px;"></div>
                            <div style="border-bottom: medium solid black; height:8px;"></div>
                            <div style="border-bottom: medium solid black; height:8px;"></div>
                        </a>
                    </div>

                    <div class="dropdown">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                            data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img style="width:45px; height:45px;" src='#' class='rounded-circle' />
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-camera fa-sm"></i>Set Profile Picture
                            </a>

                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user-edit fa-sm"></i>Update Profile
                            </a>

                            <a class="dropdown-item" href="#">
                                <i class="fas fa-envelope"></i>Change Email
                            </a>

                            <a class="dropdown-item" href="#"><i class="fas fa-eye-slash fa-sm"></i>Change Password</a>
                        </div>
                    </div>

                    <div class="homepage-top-menu non-standalone d-none">
                        <a style="color:black;" class='nav-item nav-link setup_button' role="menuitem" tabindex="-1"
                            href="#"><i class="top-menu-font fas fa-download fa-2x"></i></a>
                    </div>
                </nav>

                {{ $slot }}


            </div>
        </div>

    </div>

    <x-js />

    @stack('scripts')

</body>

</html>