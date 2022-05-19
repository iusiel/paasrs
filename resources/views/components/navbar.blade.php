<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ url('/') }}">PAASRS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link @if($activeLink === 'decks') active @endif" aria-current="page" href="#">Decks</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if($activeLink === 'cards') active @endif" href="#">Cards</a>
        </li>
        <li class="nav-item">
          <a class="nav-link @if($activeLink === 'import-export') active @endif" href="#">Import / Export</a>
        </li>
      </ul>
    </div>
  </div>
</nav>