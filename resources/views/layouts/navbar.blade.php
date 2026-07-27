<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('dashboard')}}">POS</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link {{ Request::is('admin/users') ? 'active' : ''}}" aria-current="page" href="{{ route('dashboard')}}">Dashboard</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('admin/users') ? 'active' : ''}}" aria-current="page" href="{{ url('users')}}">users</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('produk') ? 'active' : ''}}" aria-current="page" href="{{ route('produk.index')}}">Produk</a>
        </li>
        <li class="nav-item">
          <a class="nav-link {{ Request::is('penjualan') ? 'active' : ''}}" aria-current="page" href="{{ route('penjualan.index')}}">Penjualan</a>
        </li>
      </ul>
      <form class="position-absolute top-50 start-100 translate-middle" action="{{ route('logout')}}" method="post">
        @csrf
        <button class="btn btn-danger" type="submit">Logout</button>
      </form>
    </div>
  </div>
</nav>