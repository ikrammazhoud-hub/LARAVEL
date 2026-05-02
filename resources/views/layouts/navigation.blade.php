<div class="w-64 min-h-screen bg-gradient-to-b from-gray-900 via-gray-800 to-gray-900 text-white p-5 shadow-2xl">

    <!-- Logo / Title -->
    <h2 class="text-2xl font-bold mb-8 flex items-center gap-2">
        ⚙️ Maintenance
    </h2>

    <!-- Menu -->
    <ul class="space-y-3">

        <!-- Dashboard -->
        <li>
            <a href="/dashboard"
               class="flex items-center gap-3 px-4 py-2 rounded-lg
               {{ request()->is('dashboard') ? 'bg-green-500 text-white shadow-lg' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}
               transition duration-300 transform hover:scale-105">
               📊 Dashboard
            </a>
        </li>

        <!-- Machines -->
        <li>
            <a href="/machines">
               class="flex items-center gap-3 px-4 py-2 rounded-lg
               {{ request()->is('machines') ? 'bg-blue-500 text-white shadow-lg' : 'text-gray-300 hover:bg-blue-500 hover:text-white' }}
               transition duration-300 transform hover:scale-105">
               🛠 Machines
            </a>
        </li>

        <!-- Alertes -->
        <li>
            <a href="/alertes"
               class="flex items-center gap-3 px-4 py-2 rounded-lg
               {{ request()->is('alertes') ? 'bg-red-500 text-white shadow-lg' : 'text-gray-300 hover:bg-red-500 hover:text-white' }}
               transition duration-300 transform hover:scale-105">
               ⚠️ Alertes
            </a>
        </li>

    </ul>

    <!-- Divider -->
    <div class="border-t border-gray-700 my-6"></div>

    <!-- Logout -->
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
            class="w-full flex items-center gap-3 px-4 py-2 rounded-lg bg-gradient-to-r from-red-500 to-red-700 text-white shadow-lg hover:scale-105 hover:shadow-red-500/50 transition duration-300">
            🚪 Logout
        </button>
    </form>

</div>