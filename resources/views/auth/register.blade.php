<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Create Account | Industrial Maintenance Platform</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Outfit', sans-serif;
            background-image: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.8)), url('/images/bg.jpg');
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
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="glass p-8 rounded-2xl shadow-2xl w-full max-w-lg">
        
        <div class="text-center mb-6">
            <h2 class="text-3xl font-bold text-white tracking-tight">Create Account</h2>
            <p class="text-slate-400 mt-2">Join the industrial management revolution</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Full Name</label>
                <input type="text" name="name" 
                    value="{{ old('name') }}"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                    placeholder="John Doe" required autofocus>
                @if($errors->has('name'))
                    <p class="text-red-400 text-xs mt-1">{{ $errors->first('name') }}</p>
                @endif
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-slate-300 mb-1">Email Address</label>
                <input type="email" name="email" 
                    value="{{ old('email') }}"
                    class="w-full px-4 py-2.5 rounded-xl bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                    placeholder="john@company.com" required>
                @if($errors->has('email'))
                    <p class="text-red-400 text-xs mt-1">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Password</label>
                    <input type="password" name="password"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                        placeholder="••••••••" required>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-1">Confirm Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full px-4 py-2.5 rounded-xl bg-slate-800/50 border border-slate-700 text-white focus:outline-none focus:ring-2 focus:ring-teal-500 transition-all"
                        placeholder="••••••••" required>
                </div>
            </div>
            
            @if($errors->has('password'))
                <p class="text-red-400 text-xs mt-1">{{ $errors->first('password') }}</p>
            @endif

            <button type="submit"
                class="w-full bg-teal-600 hover:bg-teal-500 text-white font-semibold py-3 rounded-xl shadow-lg shadow-teal-700/30 transform active:scale-[0.98] transition-all duration-200 mt-2">
                Create Account
            </button>

            <div class="text-center pt-4">
                <p class="text-slate-500 text-sm">
                    Already have an account? 
                    <a href="{{ route('login') }}" class="text-teal-400 hover:text-teal-300 font-medium transition-colors">Sign in here</a>
                </p>
            </div>
        </form>

    </div>

</body>
</html>
