<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login | Industrial Maintenance Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-image: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.7)), url('/images/bg.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }
        .glass {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .animate-in {
            animation: fadeInScale 0.6s ease-out forwards;
        }
        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="glass p-10 rounded-2xl shadow-2xl w-full max-w-md animate-in">
        
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center p-3 bg-teal-500 rounded-xl mb-4 shadow-lg shadow-teal-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-3xl font-bold text-white tracking-tight">Welcome Back</h2>
            <p class="text-slate-400 mt-2">Access your maintenance dashboard</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-2">Work Email Address</label>
                <div class="relative">
                    <input type="email" name="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                        placeholder="name@company.com"
                        required autofocus>
                    @error('email')
                        <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-2">
                    <label class="block text-sm font-medium text-slate-300">Password</label>
                    <a href="#" class="text-xs text-teal-400 hover:text-teal-300 transition-colors">Forgot password?</a>
                </div>
                <input type="password" name="password"
                    class="w-full px-4 py-3 rounded-xl bg-slate-800/50 border border-slate-700 text-white placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent transition-all"
                    placeholder="••••••••"
                    required>
                @error('password')
                    <p class="text-red-400 text-xs mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Remember -->
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember"
                    class="h-4 w-4 rounded border-slate-700 bg-slate-800 text-teal-600 focus:ring-teal-500 focus:ring-offset-slate-900">
                <label for="remember" class="ml-2 block text-sm text-slate-400 cursor-pointer">
                    Remember this session
                </label>
            </div>

            <!-- Button -->
            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-3 rounded-xl shadow-lg shadow-teal-700/30 transform active:scale-[0.98] transition-all duration-200">
                Sign In
            </button>

            <!-- Register Link -->
            <div class="text-center mt-6">
                <p class="text-slate-500 text-sm">
                    New to the platform? 
                    <a href="{{ route('register') }}" class="text-teal-400 hover:text-teal-300 font-medium transition-colors">Join now</a>
                </p>
            </div>
        </form>

    </div>

</body>
</html>