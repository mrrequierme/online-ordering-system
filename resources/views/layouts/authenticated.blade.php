<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.header.head')

<body>
    @include('layouts.partials.navbar.navbar')

    <div class="container-fluid py-2">
        @yield('content')
    </div>
    @guest
        @include('layouts.partials.footer.footer')
    @endguest
    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
    <script src="{{asset('js/custom.js')}}"></script>
    
    @stack('scripts')
</body>

</html>
