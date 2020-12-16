<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8" />
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link rel="stylesheet" href="{{mix('css/main.css')}}" />
      <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700&amp;family=Oswald:wght@400;700&amp;display=swap" />
      <title>@yield('title')</title>
  </head>
  <body>
    <header class="main-header">
        <div class="logo-container">
            <a href="/"><img class="logo" src="/img/logo.svg" /></a>
        </div>
        @yield('menu')
    </header>
    @yield('main')
    <footer class="main-footer">
        <div class="section container"><p class="copyright">&copy; Copyright {{$copyright_year}} by <a href='https://robotkudos.com' target='_blank'>Robot Kudos</a>. All rights reserved.</p></div>
    </footer>
  </body>
  <script src="/js/bundle.js"></script>
  @yield('scripts')
</html>
