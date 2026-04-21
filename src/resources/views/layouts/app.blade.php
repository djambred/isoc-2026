<!DOCTYPE html>
<html class="scroll-smooth" lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8"/>
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <title>@yield('title', 'ISOC Indonesia Jakarta Chapter')</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        navy: { DEFAULT: '#002D56', light: '#003d75', dark: '#001833' },
                        blue: { DEFAULT: '#0060AC', light: '#0097DC', dark: '#004883' },
                        teal: { DEFAULT: '#00B4A0', light: '#00D4BC' },
                        grey: { 50: '#F7F8F9', 100: '#F0F1F3', 200: '#E1E3E6', 300: '#C4C8CE', 400: '#8B919A', 500: '#6B7280', 600: '#4B5563', 700: '#374151', 800: '#1F2937', 900: '#111827' },
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            display: inline-block;
            vertical-align: middle;
            line-height: 1;
        }
        [x-cloak] { display: none !important; }
    </style>
    @stack('styles')
</head>
<body class="bg-white font-sans text-grey-800 antialiased">

    @include('partials.navbar')

    <main class="pt-[72px]">
        @yield('content')
    </main>

    @include('partials.footer')

    @stack('scripts')
</body>
</html>
