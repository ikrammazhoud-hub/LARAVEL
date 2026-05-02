<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maintenance Platform</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900 text-white">

<div class="flex">

    <!-- Sidebar -->
    <div class="w-64 min-h-screen p-5 bg-gradient-to-b from-gray-900 to-gray-800 shadow-xl">

        <h2 class="text-xl font-bold mb-8 flex items-center gap-2">
            ⚙️ Maintenance
        </h2>

        <ul class="space-y-3">

            <li>
                <a href="/dashboard"
                   class="flex items-center gap-3 px-4 py-2 rounded-lg 
                   {{ request()->is('dashboard') ? 'bg-green-500 text-white' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}
                   transition duration-300">
                    📊 Dashboard
                </a>
            </li>

            <li>
             <a href="/machines"
   class="flex items-center gap-3 px-4 py-2 rounded-lg
   {{ request()->is('machines') ? 'bg-blue-500 text-white' : 'text-gray-300 hover:bg-blue-500 hover:text-white' }}
   transition duration-300">
   🛠 Machines
</a>
            </li>

            <li>
                <a href="/alertes"
   class="flex items-center gap-3 px-4 py-2 rounded-lg
   {{ request()->is('alertes') ? 'bg-red-500 text-white' : 'text-gray-300 hover:bg-red-500 hover:text-white' }}
   transition duration-300">
   ⚠️ Alertes
</a>
            </li>

        </ul>
    </div>

    <!-- Content -->
    <div class="flex-1 p-6">
        @yield('content')
    </div>

</div>

</body>
</html>