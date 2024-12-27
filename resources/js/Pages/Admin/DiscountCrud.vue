<template>
    <Head title="Modifica Sconto" />

    <!-- QUA DOVRAI GESTIRE LA MODIFICA DELLO SCONTO, USANDO DELLE CHIPS PER LA SELZIONE DELLE CATEGORIE E DEI PRODOTTI, PRENDENDO I LORO ID E SALVANDOLI IN DUE ARRAY! ( PRENDI I NOMIO E GLI ID COSI SARà PIU FACILE PER TE GESTIRE IL TUTTO ) PUOI USARE DELLE CHIPS ? -->
</template>

<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
const { props } = usePage();
const discount = ref(props.discount);
console.log(discount.value);
</script>
