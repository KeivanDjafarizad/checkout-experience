<x-layout>
    <h3>Dati utente</h3>
    <section class="flex gap-1">
        <form action="{{ route('checkout.details') }}" method="POST" class="bg-white p-5 rounded-md shadow-sm">
            @csrf
            <section class="flex flex-col mb-5">
                <div class="flex flex-col mb-5">
                    @if($errors->any())
                        <div class="bg-red-500 text-white p-2 rounded-md mb-2">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <input class="p-1 border border-gray-400 rounded-md w-100 mb-2" type="text" name="first-name" value="{{ old('first-name', $cart->first_name) }}" placeholder="Nome" />
                    <input class="p-1 border border-gray-400 rounded-md w-100 mb-2" type="text" name="last-name" value="{{ old('last-name', $cart->last_name) }}" placeholder="Cognome" />
                    <input class="p-1 border border-gray-400 rounded-md w-100 mb-2" type="email" name="email" value="{{ old('email', $cart->email) }}" placeholder="Email" />
                    <select name="nation" class="p-1 border border-gray-400 rounded-md w-100 mb-2 bg-white">
                        <option value="">Nazione</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Italy">Italy</option>
                        <option value="USA">USA</option>
                    </select>
                    <div class="field field-checkbox">
                        <label>Richiedi fattura</label>
                        <input type="checkbox" name="invoice" id="invoice"/>
                    </div>
                    <div id="invoiceGroup" class="hidden flex flex-col">
                        <input type="text" class="p-1 border border-gray-400 rounded-md w-100 mb-2" name="fiscal-tax-number" placeholder="Partita IVA" />
                        <input type="text" class="p-1 border border-gray-400 rounded-md w-100 mb-2" name="fiscal-code-number" placeholder="Codice Fiscale" />
                    </div>
                </div>
                <div class="flex flex-col">
                    <div class="field field-checkbox">
                        <label>Iscriviti alla newsletter</label>
                        <input type="checkbox" name="newsletter" />
                    </div>
                    <div class="field field-checkbox">
                        <label>Ho letto e accetto l'informativa sulla privacy</label>
                        <input type="checkbox" name="privacy" />
                    </div>
                </div>
            </section>

            <section class="flex justify-between items-center">
                <a class="flex w-1/2 items-center justify-center rounded-md border-slate-700 border text-slate-700 font-bold p-2" href="{{ route('cart.index') }}">Indietro</a>
                <button type="submit" class="flex w-1/2 items-center justify-center rounded-md bg-slate-700 text-white font-bold p-2">Procedi</button>
            </section>
        </form>
    </section>
    <script>
        const invoice = document.querySelector('#invoice');
        const invoiceGroup = document.querySelector('#invoiceGroup');
        if(invoice.checked) {
            invoiceGroup.classList.remove('hidden');
        }
        invoice.addEventListener('change', function() {
            if(this.checked) {
                invoiceGroup.classList.remove('hidden');
            } else {
                invoiceGroup.classList.add('hidden');
            }
        })

    </script>
</x-layout>