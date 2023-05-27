<x-layout>
    <section class="flex gap-1 flex-wrap justify-center">
        @foreach($products as $product)
            <div class="bg-white p-6 rounded-md shadow-sm w-1/4">
                <h3 class="text-lg font-bold">
                    {{ $product->name }}
                </h3>
                <p class="text-sm text-slate-500 mb-3">{{ $product->sku }}</p>
                <p class="text-md font-bold">{{ $product->price }} €</p>
                <p class="border inline-flex justify-center items-center p-1 rounded-md mb-2">{{ $product->stock_qty > 0 ? 'Available' : 'Out of stock' }}</p>
                @if($product->stock_qty > 0)
                    <button
                            class="editCart flex items-center justify-center rounded-md bg-slate-700 text-white font-bold p-2"
                            data-sku="{{ $product->sku }}"
                            data-quantity="1"
                    >Add to cart</button>
                @endif
            </div>
        @endforeach
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