<!DOCTYPE html>
<html lang="fr" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Technicien - Atelier Pro</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden selection:bg-brand-500 selection:text-white">

    @include('technicien.partials.sidebar')

    <main class="flex-1 flex flex-col overflow-hidden relative">
        <div class="absolute top-0 inset-x-0 h-72 bg-gradient-to-b from-brand-500/10 to-transparent pointer-events-none"></div>
        <div class="absolute -top-44 -right-44 w-[28rem] h-[28rem] bg-teal-300/20 rounded-full blur-[110px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-1/4 w-80 h-80 bg-indigo-300/10 rounded-full blur-[100px] pointer-events-none"></div>

        @include('technicien.partials.header')

        <div class="flex-1 overflow-x-hidden overflow-y-auto p-6 lg:p-10 z-10 relative">
            @if(session('success'))
                <div class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm font-bold text-emerald-700 shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 px-5 py-4 text-sm font-bold text-red-700 shadow-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            @yield('content')
        </div>
    </main>
</body>
</html>
