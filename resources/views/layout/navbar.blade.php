<nav class="navbar navbar-expand-lg nav">
    <div class="container">
      <a class="navbar-brand" href="{{ route('welcome') }}">
        <img src="{{ asset('public\logo\logo.svg') }}">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link" aria-current="page" href="{{ route('welcome') }}">главная</a>
          </li>
          @auth
             <li class="nav-item">
            <a class="nav-link" href="{{ route('my_beats') }}">мои биты</a>
            </li>
            @if (Auth::user()->status == 'артист')
            <li class="nav-item">
                <a class="nav-link" href="{{ route('new_track') }}">новый трек</a>
            </li>
            @endif

            <li class="nav-item">
                <a class="nav-link" href="{{ route('logout') }}">выход</a>
            </li>
          @endauth

        </ul>
        <form class="d-flex me-auto" role="search" method="post" action="{{ route('search') }}">
          @method('post')
          @csrf
          <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search">
          <button class="btn" type="submit"><img src="{{ asset('public\icons\black_search.svg') }}"></button>
        </form>
        <ul class="navbar-nav">
            <li class="nav-item">
                @guest
                   <a class="nav-link" aria-current="page" href="{{ route('login') }}">
                        <img src="{{ asset('public\icons\User.svg') }}">
                    </a>
                @endguest
                    @auth
                    @if (Auth::user()->status == 'артист')
                        <a class="nav-link" aria-current="page" href="{{ route('artist_profile') }}">
                            <img src="{{ asset('public\icons\User.svg') }}">
                        </a>
                    @elseif (Auth::user()->status == 'слушатель')
                        <a class="nav-link" aria-current="page" href="{{ route('user_profile') }}">
                            <img src="{{ asset('public\icons\User.svg') }}">
                        </a>
                    @else
                        <a class="nav-link" aria-current="page" href="{{ route('welcome') }}">
                            <img src="{{ asset('public\icons\User.svg') }}">
                        </a>
                    @endif

                    @endauth
            </li>

          </ul>
      </div>
    </div>
  </nav>
  <style>
    .nav{
        z-index:1;
        background-color: white;
        border-bottom-right-radius: 20px;
        border-bottom-left-radius: 20px;
    }
  </style>
