<x-layout>
<h1>Payment</h1>
    <div class="bg-white p-5 rounded shadow-sm">
        @foreach($cart->items() as $item)
            <div class="bg-white p-2 mb-3">
                <div class="flex justify-between items-baseline mb-3">
                    <h2 class="font-bold text-lg">{{ $item->product->name }}</h2>
                    <p>{{ number_format($item->totalDiscounted ?? $item->total, 2) }} €</p>
                </div>
                <p>x {{ $item->quantity }}</p>
            </div>
        @endforeach
    </div>
    <div class="bg-white p-5 rounded shadow-sm">
        <form action="{{ route('checkout.manage-payment') }}" method="POST">
            @csrf
            <section class="payment">
                <div class="field">
                    <input id="paypal" type="radio" name="payment_type" value="paypal">
                    <label for="paypal">Paga con Paypal</label><br/>
                    <input id="stripe" type="radio" name="payment_type" value="stripe">
                    <label for="stripe">Paga con Stripe</label>
                </div>
                <div class="field field-checkbox">
                    <label for="terms_and_conditions">Ho letto e accetto le condizioni generali di vendita</label>
                    <input id="terms_and_conditions" type="checkbox" name="terms_and_conditions" />
                </div>
            </section>

            <section class="flex justify-between items-center">
                <a class="flex w-1/2 items-center justify-center rounded-md border-slate-700 border text-slate-700 font-bold p-2" href="{{ route('cart.index') }}">Indietro</a>
                <button type="submit" class="flex w-1/2 items-center justify-center rounded-md bg-slate-700 text-white font-bold p-2">Procedi</button>
            </section>
        </form>
    </div>
</x-layout>