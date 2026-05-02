<!DOCTYPE html>
<html lang="fr" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Gestion Atelier Pro</title>
    
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/tailwind-config.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/admin-custom.css') }}">
</head>
<body class="bg-slate-50 text-slate-800 flex h-screen overflow-hidden selection:bg-brand-500 selection:text-white">

    @include('admin.partials.sidebar')

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col overflow-hidden relative">
        <!-- Decorative Background Gradients -->
        <div class="absolute top-0 inset-x-0 h-64 bg-gradient-to-b from-brand-500/10 to-transparent pointer-events-none"></div>
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-brand-400/20 rounded-full blur-[100px] pointer-events-none"></div>
        
        @include('admin.partials.header')

        <!-- Content -->
        <div class="flex-1 overflow-x-hidden overflow-y-auto p-10 z-10 relative">
            @yield('content')
        </div>
    </main>

</body>
</html>
