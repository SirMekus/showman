<nav class="navbar navbar-expand-lg bg-dark">
  <button class="navbar-toggler bg-light" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon bg-dark"></span>
  </button>
  <div class="collapse navbar-collapse " id="navbarTogglerDemo01">
    <a class="navbar-brand" href="{{route('home')}}">
      <img class='img-fluid d-block w-100' style='height:50px;' src="{{asset('storage/uploads/for_site/logo.png')}}" >
    </a><a class='ml-0 text-light text-decoration-none d-none d-sm-block' href='{{route('home')}}'><h4 class='font-weight-bold'>{{config('app.name')}}</h4></a>
    <ul class="navbar-nav mr-auto mt-2 mt-lg-0 ml-3">
      @if(!request()->user())
      <li class="nav-item active">
        <a class="nav-link text-light font-weight-bold" href="{{route('login')}}">Login</a>
      </li>
      <li class="nav-item active">
        <a class="nav-link text-light font-weight-bold" href="{{route('register')}}">Sign Up</a>
      </li>
      @else
      <li class="nav-item active">
        <a class="nav-link text-light font-weight-bold" href="{{route('dashboard.branch')}}">Dashboard</a>
      </li>
      @endif 

      
    </ul>
    <form action="{{route('home')}}" class="d-flex d-flex justify-content-end float-end">
      <input class="form-control me-2" type="search" placeholder="Search" name="search" aria-label="Search">
      <button class="btn btn-outline-success" type="submit">Search</button>
    </form>
  </div>
</nav>