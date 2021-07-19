<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!--Alpine.js-->
    <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>

    {{-- Other JS Libraries --}}
    @yield('JSLibraries')

    <!-- Styles -->
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">

    <!--Font Awesome-->
    <script src="https://kit.fontawesome.com/e54000fd79.js" crossorigin="anonymous"></script>
</head>
<body class="bg-gray-100 h-screen antialiased leading-none font-sans">
    <div id="app">
        {{-- reference: https://www.themes.dev/blog/responsive-navigation-menu-tailwind-css/ --}}
        <header x-data="{mobileMenuOpen: false, invitationMenuOpen: false}" class="flex flex-wrap flex-row justify-between bg-green-800 py-6 px-6 md:items-center md:space-x-4 relative">
            <a href="#" class="text-lg font-semibold block text-gray-100">
                {{ config('app.name', 'Laravel') }}
            </a>
            <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="inline-block md:hidden w-8 h-8 text-gray-400 p-1">
                <svg fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
            </button>
            <nav
                class="absolute md:relative
                    top-16 left-0 md:top-0 z-20
                    md:flex flex-col md:flex-row
                    md:space-x-6 font-semibold
                    w-full md:w-auto
                    bg-green-800 md:bg-transparent
                    shadow-md md:shadow-none
                    rounded-lg md:rounded-none
                    p-6 pt-0 md:p-0"
                :class="{'flex':mobileMenuOpen, 'hidden':!mobileMenuOpen}" @click.away="mobileMenuOpen = false">
                @guest
                    <a href="{{ route('login') }}" class="text-gray-300 py-1 hover:text-white">
                        <i class="fas fa-sign-in-alt"></i>
                        {{ __('Login') }}
                    </a>
                    <a href="{{ route('register') }}" class="text-gray-300 py-1 hover:text-white">{{ __('Register') }}</a>
                @else
                    <a href="/" class="text-gray-300 py-1 hover:text-white">
                        <i class="far fa-list-alt"></i>
                        My Shopping Lists
                    </a>
                    @include('invitations.dropdown')
                    <a href="{{ route('logout') }}"
                        class="text-gray-300 py-1 hover:text-white"
                        onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt"></i>
                            {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        {{ csrf_field() }}
                    </form>
                @endguest
            </nav>
        </header>

        @yield('content')
    </div>
</body>
</html>
