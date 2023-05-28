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
        <header class="bg-slate-100 shadow-md mb-6 px-2">
            <div class="container mx-auto flex justify-between items-center h-10">
                <button class="md:hidden flex bg-slate-700 w-5 h-5 justify-center items-center" id="menuToggle">
                    <svg viewBox="0 0 100 80" width="40" height="40" class="bg-white">
                        <rect width="100" height="20"></rect>
                        <rect y="30" width="100" height="20"></rect>
                        <rect y="60" width="100" height="20"></rect>
                    </svg>
                </button>
                <nav class="md:flex hidden">
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
            <nav class="hidden" id="mobileMenu">
                <ul class="flex items-center flex-col">
                    <li class="mr-4"><a href="{{ route('home') }}" class="hover:underline">Home</a></li>
                    <li class="mr-4"><a href="{{ route('shop.index') }}" class="hover:underline">Shop</a></li>
                    <li class="mr-4"><a href="#" class="hover:underline">Privacy</a></li>
                    <li class="mr-4"><a href="#" class="hover:underline">Terms and Conditions</a></li>
                </ul>
            </nav>
        </header>
        <main class="container mx-auto">
            {{ $slot }}
        </main>
    </body>
    <script>
        const menuToggle = document.querySelector('#menuToggle');
        const mobileMenu = document.querySelector('#mobileMenu');
        menuToggle.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        // window.addEventListener('cart-updated', event => {
        //     document.querySelector('#cart-items').innerHTML = event.detail.itemsNumber;
        //     document.querySelector('#cart-total').innerHTML = event.detail.total;
        // });
    </script>
</html>
