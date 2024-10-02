<template>
    <Head title="Modifica Prodotto" />
    <v-container fluid>
        <v-row>
            <v-col>
                <h1>Product CRUD Page</h1>
                <p>Contenuto della pagina di modifica del prodotto</p>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { onMounted } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';

// Recuperiamo i props di Inertia
const { props } = usePage();

function fetchProductData(){
    axios.get('/api/product/' + props.productId)
    .then((res) => {
        console.log(res.data)
    }).catch((e) => {
        console.error(e)
    })
}

fetchProductData();
</script>

<style scoped>
/* Aggiungi il tuo stile qui se necessario */
</style>
