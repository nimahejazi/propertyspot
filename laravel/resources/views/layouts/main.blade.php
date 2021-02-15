<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="{{mix('css/main.css')}}" />
      <link rel="preconnect" href="https://fonts.gstatic.com" />
      <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&amp;family=Lato&amp;display=swap" rel="stylesheet" />
      @yield('head')
      <title>@yield('title')</title>
  </head>
  <body>

  <div class="wrapper">
    <div class="content-wrapper">
        <header class="main-header">
            <div class="navbar" role="navigation" aria-label="main navigation">
                <div class="navbar-brand">
                    <a class="navbar-item" href="/"><img class="logo" src="/img/logo.svg" /></a>
                    <div class="navbar-burger" role="button" aria-label="menu" aria-expanded="false" data-target="navbarMenu"><span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span></div>
                </div>
                @yield('menu')
            </div>
        </header>
        @yield('main')
    </div>
    <footer class="main-footer">
        <div class="container">
            <p class="copyright">&copy; Copyright {{$copyright_year}} by <a href='https://robotkudos.com' target='_blank'>Robot Kudos</a>. All rights reserved.</p>
        </div>
    </footer>
  </div>
  </body>
  <script src="/js/bundle.js"></script>
  @yield('scripts')
</html>
