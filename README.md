# Checkout experience

## Descrizione

Il progetto consiste nella realizzazione di un sistema di checkout per un negozio di vendita al dettaglio. Il sistema deve essere in grado di calcolare il prezzo totale di una serie di prodotti, tenendo conto di eventuali sconti applicati.

## Technology stack

- Laravel 10
- PHP 8.1
- SQLite

Ho scelto di utilizzare Laravel perché è un framework molto flessibile, facile da leggere ma molto completo e permette di
scrivere codice pulito ed esplicito, in modo che sia leggibile sia a junior dev che a senior. Non di meno è in grado di gestire
strutture di media complessità, come nel caso di questo progetto. 

Nonostante sia un framework che tende al monolite come soluzione,
è possibile utilizzare costrutti tipici del Domain Driven Design, come in questo caso, come Repository e Service.

Inoltre gestisce in maniera abbastanza esplicita e facile le sessioni, la connessione a database e la gestione delle rotte.

In frontend ho optato per l'utilizzo di TailwindCSS, estremamente comodo per stilare l'HTML senza mai abbandonare il codice, estendibile facilmente.



## Miglioramenti e criticità

- L'integrazione di pagamento è molto basilare, si può decidere di integrare il proprio ecommerce in maniera più
profonda con Stripe, caricando i prodotti e le categorie, ma questo potrebbe restringere il campo di operatività degli sconti.
- Non ho implementato l'aggiornamento dinamico dell'header del carrello, ma è possibile farlo utilizzando un ViewComposer offerto da Laravel,
renderizzare il componente via server e sostituire il contenuto del carrello con quello nuovo.
- Medesima cosa per l'aggiunta al carrello e la rimozione, sarebbe opportuno usare una libreria js come Toast per mostrare un messaggio di successo o fallimento
- Si potrebbe meglio definire come vogliamo funzionino gli sconti. Al momento sono solo sconti percentuali sui prodotti, e non è ben specificato price_min e price_max a cosa si riferiscano. Un possibile miglioramento
sarebbe quello di permettere anche sconti fissi direttamente sul prodotto o sul carrello, cumulabili o meno.
- Non mi convince la gestione dei prezzi con i numeri decimali, ho trovato successo utilizzando l'approccio stripe di utilizzare interi centesimali, diventa anche facile castarli con uno specifico **Value Object**.
- Salverei più dati nel campo items del database del carrello e dell'ordine, in modo da mantenere lo storico dei prezzi e sconti applicati anche nel momento in cui il prezzo del prodotto cambi, mantenendo così la coerenza dei dati.
- Lato frontend nulla è stato bundlizzato. Un'evoluzione sarebbe quella di utilizzare Vite per gestire bundling e compilazione di eventuali asset JS e CSS (TailwindCSS in questo caso).

## Deploy

Il deploy di questa soluzione può essere eseguito tranquillamente su infrastruttura docker. Infatti è stato sviluppato in un ambiente dockerizzato, con
un container nginx che gestiva il webserver e un container php-fpm che gestiva l'applicazione. Per il database ho utilizzato sqlite, ma è possibile collegarsi 
ad un'istanza mysql o postgresql sia dockerizzata che esterna. Così facendo abbiamo una soluzione replicabile ed eventualmente gestibile via load balancer.

Laravel ci permette di utilizzare Redis per gestire le sessioni, per gestire code e per gestire la cache. Può essere usato un contianer dedicato o un'istanza esterna. Ha integrazione nativa anche con SQS di AWS e con Kafka.

Un'eventuale semplcie pipeline di deploy potrebbe comprendere in ordine:

- Creazioen di istanza php
- Controllo dello standard del codice via PHPStan (con integrazione LaraStan)
- installazione di dipendenze composer e di dipendenze npm
- esecuzione di test unitari e di integrazione
- refresh del database ed eventuale seeding con i dati di produzione base

## Installazione

Una volta clonato il repository (che comprende anche il file di database sqlite, non necessario), basterà copiare il file .env.example in .env, modificare le variabili di ambiente necessarie e lanciare i seguenti comandi:

```bash
composer install
php artisan key:generate
php artisan migrate:fresh --seed
```

Sarà disponibile un database riempito di dati di test, ma utilizzabile ai fini della soluzione