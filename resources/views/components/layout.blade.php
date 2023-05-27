<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <meta name="csrf-token" content="{{ csrf_token() }}">
    </head>
    <body class="antialiased bg-slate-300 h-max">
        <header class="bg-slate-100 shadow-md mb-6">
            <div class="container mx-auto flex justify-between items-center h-10">
                <nav>
                    <ul class="flex items-center">
                        <li class="mr-4"><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                        <li class="mr-4"><a href="{{ route('shop.index') }}" class="hover:underline">Shop</a></li>
                        <li class="mr-4"><a href="#" class="hover:underline">Privacy</a></li>
                        <li class="mr-4"><a href="#" class="hover:underline">Terms and Conditions</a></li>
                    </ul>
                </nav>
                <div>
                    <a href="{{ route('cart.index') }}" class="hover:underline">
                        {{ \App\Repositories\CartRepository::getCurrentCart()->itemsNumber() }} Items -
                        {{ \App\Repositories\CartRepository::getCurrentCart()->total }} €
                    </a>
                </div>
            </div>
        </header>
        <main class="container mx-auto">
            {{ $slot }}
        </main>
    </body>
</html>
