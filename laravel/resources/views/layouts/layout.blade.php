<!DOCTYPE html>
<html lang="en">

@include('fixed.head')

<body class="@yield('body_class')">

    <div class="hero_area">
        @include('fixed.navigation')
        @yield('hero_content')
    </div>


    <!-- Page Content -->
    @yield('content')

    <!-- Footer -->
    @include('fixed.footer')

    <!-- Scripts -->
    @include('fixed.scripts')

</body>

</html>
