<nav class="navbar navbar-expand-md navbar-light bg-light border-bottom shadow-sm fixed-top">  
  <div class="container">
    <a class="navbar-brand" href="/">
      <img src="/assets/images/logo.png" height="35" class="d-inline-block align-top" alt="" loading="lazy">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarsExampleDefault">
      <ul class="navbar-nav me-auto mb-2 mb-md-0">
      <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-bs-toggle="dropdown" aria-expanded="false">Dashboard</a>
        <ul class="dropdown-menu" aria-labelledby="dropdown01">
          <li><a class="dropdown-item" href="/dashboard/">Dashboard</a></li>
          <li><a class="dropdown-item" href="/demo/">Demo</a></li>
        </ul>
      </li>
      <li class="nav-item dropdown">      
        <a class="nav-link dropdown-toggle" href="#" id="dropdown02" data-bs-toggle="dropdown" aria-expanded="false">Test</a>
        <ul class="dropdown-menu" aria-labelledby="dropdown02">
          <li><a class="dropdown-item" href="/dashboard/">Dashboard</a></li>
          <li><a class="dropdown-item" href="/demo/">Demo</a></li>
          <li><a class="dropdown-item" href="/file/">File</a></li>
          <li><a class="dropdown-item" href="/guest/">Guest</a></li>
          <li><a class="dropdown-item" href="/profile/">Profile</a></li>                              
          <li><a class="dropdown-item" href="/share/">Share</a></li>
          <li><a class="dropdown-item" href="/ufile/">Ufile</a></li>
          <li><a class="dropdown-item" href="/test/">Test</a></li>                              
        </ul>        
      </li>

      <li class="nav-item">
        <a class="nav-link" href="/login/?register=1">Sign Up</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/login/">Login</a>
      </li>

      </ul>
      <form class="d-flex" method="GET" action="search.php">
        <input class="form-control me-2" type="search" name="query" placeholder="Search" aria-label="Search" minlength="5" required="">
        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
      </form>
    </div>
  </div>
</nav>