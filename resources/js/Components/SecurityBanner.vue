<template>
<v-container fluid class="banner-container">
    <v-row class="fill-height">
    <v-col class="d-flex flex-column justify-center align-center text-center text-white">
        <h1 class="display-1 font-weight-bold text-shadow">La tua sicurezza è la nostra priorità</h1>
        <p class="text-h5 text-shadow">
        Siamo impegnati a fornire abbigliamento da lavoro di alta qualità, progettato per proteggerti in qualsiasi ambiente lavorativo.
        Con i nostri prodotti, puoi concentrarti sul tuo lavoro sapendo di essere al sicuro.
        </p>
    </v-col>
    </v-row>
</v-container>
</template>

<script setup>
</script>

<style scoped>
.banner-container {
background-image: url('/images/banner/banner.webp'); /* Percorso dell'immagine del banner */
background-size: cover;
background-position: center;
height: 500px; /* Altezza del banner */
position: relative;
}

.text-white {
color: white;
}

.fill-height {
height: 100%;
}

.text-center {
text-align: center;
}

.font-weight-bold {
font-weight: bold;
}

.display-1 {
font-size: 3rem; /* Adatta la dimensione del titolo se necessario */
}

.text-h5 {
font-size: 1.25rem;
max-width: 800px;
margin: 0 auto;
}

/* Aggiunta di text-shadow per una migliore leggibilità */
.text-shadow {
text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7); /* Leggera ombra scura */
}
</style>
