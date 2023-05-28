<x-layout>
    <section>
        @foreach($cartItems as $item)
            <div class="bg-white p-6 rounded-md shadow-sm mb-3">
                <div class="flex justify-between items-baseline mb-3">
                    <h2 class="font-bold text-lg">{{ $item->product->name }}</h2>
                    <p>{{ number_format($item->totalDiscounted ?? $item->total, 2) }} €</p>
                </div>
                <div class="flex gap-3 items-center">
                    <button
                        class="editCart w-5 h-5 flex items-center justify-center rounded-sm bg-slate-400 text-white border-slate-400 font-bold"
                        data-sku="{{ $item->product->sku }}"
                        data-quantity="1"
                    >
                        +
                    </button>
                    <p>{{ $item->quantity }}</p>
                    <button
                        class="editCart w-5 h-5 flex items-center justify-center rounded-sm bg-slate-400 text-white border-slate-400 font-bold"
                        data-sku="{{ $item->product->sku }}"
                        data-quantity="-1"
                    >
                        -
                    </button>
                </div>
            </div>
        @endforeach
    </section>

    <section class="mt-6">
        <div class="bg-white p-6 rounded-md shadow-sm">
            @error('coupon')
                <p class="text-red-500">{{ $message }}</p>
            @enderror
            @if($cart->coupon)
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-lg">Coupon</h2>
                    <div>
                        <p>{{ $cart->coupon->code }}</p>
                        <form action="{{ route('cart.remove-coupon') }}" method="POST">
                            @csrf
                            <button class="bg-slate-800 text-white px-4 py-2 rounded">Remove coupon</button>
                        </form>
                    </div>
                </div>
            @else
                <form action="{{ route('cart.coupon') }}" method="POST">
                    @csrf
                    <div class="flex justify-between md:items-center md:flex-row flex-col items-start">
                        <label for="coupon" class="font-bold text-lg">Add Coupon</label>
                        <div class="flex gap-3 items-center">
                            <input
                                id="coupon"
                                name="coupon"
                                placeholder="Coupon"
                                class="p-2 border border-gray-300 rounded-md"
                            />
                            <button class="bg-slate-800 text-white px-4 py-2 rounded">Apply</button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </section>

    <section class="mt-6">
        <div class="bg-white p-6 rounded-md shadow-sm">
            <div class="flex justify-between items-baseline mb-3">
                <h2 class="font-bold text-lg">Total</h2>
                <p>{{ number_format($total, 2) }} €</p>
            </div>
            <div class="flex justify-end">
                <a href="{{ route('checkout.index') }}" class="bg-slate-800 text-white px-4 py-2 rounded">Checkout</a>
            </div>
        </div>
    </section>
    <script>
        let addToCart = document.querySelectorAll('.editCart');
        const csrfToken = document.head.querySelector("meta[name~=csrf-token]").content;

        for (button of addToCart) {
            button.addEventListener('click', function() {
                const body = {
                    product_sku: this.dataset.sku,
                    quantity: parseInt(this.dataset.quantity)
                }

                fetch('/cart/product', {
                    method: 'POST',
                    body: JSON.stringify(body),
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data);
                    })
            })
        }
    </script>
</x-layout>