<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Technicien</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-slate-100 text-slate-900 font-sans antialiased min-h-screen">
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="font-extrabold text-2xl tracking-tight text-blue-600 flex items-center gap-2">
                <span class="text-3xl">🧰</span> TechniPortal
            </div>
            <div class="text-sm font-semibold text-slate-600 bg-slate-100 px-4 py-2 rounded-full shadow-inner">
                👤 {{ auth()->user()->name ?? 'Mon Profil' }}
            </div>
        </div>
    </nav>
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        @if(session('success'))
        <div class="mb-6 bg-emerald-50 text-emerald-700 px-4 py-4 rounded-xl shadow-sm border border-emerald-100 font-medium" role="alert">
            <span class="flex items-center gap-2">✅ {{ session('success') }}</span>
        </div>
        @endif
        @yield('content')
    </main>
</body>
</html>
