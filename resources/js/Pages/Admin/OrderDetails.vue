<template>
    <v-container class="py-5">
    <!-- Titolo -->
    <v-row>
        <v-col>
            <div class="text-h4">Dettagli Ordine #{{ order.order_number }}</div>
        </v-col>
    </v-row>

    <!-- Dati Utente -->
    <v-row class="mt-4">
        <v-col>
            <v-card class="pa-4" elevation="0" rounded="xl">
                <h2 class="text-h5">Informazioni Utente</h2>
                <p><strong>Nome:</strong> {{ order.user.name }}</p>
                <p><strong>Email:</strong> {{ order.user.email }}</p>
            </v-card>
        </v-col>
    </v-row>

    <!-- Indirizzo di Spedizione -->
    <v-row class="mt-4">
        <v-col cols="6">
            <v-card class="pa-4" elevation="0" rounded="xl" height="220">
                <h2 class="text-h5">Indirizzo di Spedizione</h2>
                <p><strong>Destinatario:</strong> {{ order.shipping_address.recipient_name }}</p>
                <p><strong>Indirizzo:</strong> {{ order.shipping_address.address }}, {{ order.shipping_address.house_number }}</p>
                <p><strong>CAP:</strong> {{ order.shipping_address.postal_code }}</p>
                <p><strong>Città:</strong> {{ order.shipping_address.city }}</p>
                <p><strong>Telefono:</strong> {{ order.shipping_address.phone_number }}</p>
            </v-card>
        </v-col>
        <v-col cols="6">
            <v-card class="pa-4" elevation="0" rounded="xl">
                <h2 class="text-h5">Indirizzo di Fatturazione</h2>
                <p><strong>Nome/Denominazione:</strong> {{ order.billing_address.name }}</p>
                <p><strong>Partita IVA/C.F.:</strong> {{ order.billing_address.tax_id }}</p>
                <p><strong>Indirizzo:</strong> {{ order.billing_address.address }}, {{ order.billing_address.house_number }}</p>
                <p><strong>CAP:</strong> {{ order.billing_address.postal_code }}</p>
                <p><strong>Città:</strong> {{ order.billing_address.city }}</p>
                <p><strong>Telefono:</strong> {{ order.billing_address.phone_number }}</p>
            </v-card>
        </v-col>
    </v-row>

    <!-- Dettagli Ordine -->
    <v-row class="mt-4">
        <v-col>
            <v-card class="pa-4" elevation="0" rounded="xl">
                <h2 class="text-h5">Dettagli Ordine</h2>
                <p><strong>Stato:</strong> {{ order.status }}</p>
                <p><strong>Data Ordine:</strong> {{ order.order_date }}</p>
                <p><strong>Data Spedizione:</strong> {{ order.shipped_in }}</p>
                <p><strong>Metodo di Pagamento:</strong> {{ order.payment }}</p>
                <p><strong>Totale:</strong> €{{ (order.total_amount / 100).toFixed(2) }}</p>
                <p><strong>Numero di Spedizione:</strong> {{ order.shipping_number }}</p>
            </v-card>
        </v-col>
    </v-row>

    <!-- Prodotti nell'Ordine -->
    <v-row class="mt-4">
        <v-col>
        <v-card class="pa-4" elevation="0" rounded="xl">
            <h2 class="text-h5">Prodotti nell'Ordine</h2>
            <v-row>
            <v-col cols="4"  v-for="product in order.products" :key="product.id">
                <v-card class="pa-3 mb-3" elevation="0" rounded="xl" height="180">
                    <p><strong>Nome:</strong> {{ product.name }}</p>
                    <p><strong>Quantità:</strong> {{ product.pivot.order_quantity }}</p>
                    <p><strong>Prezzo:</strong> {{ $formatPrice(product.pivot.price_at_order) }}</p>
                </v-card>
            </v-col>
            </v-row>
        </v-card>
        </v-col>
    </v-row>
    </v-container>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { usePage } from '@inertiajs/vue3';

const { props } = usePage();
console.log(props.order)
const order = reactive(props.order);
</script>

<style scoped>
.text-h4 {
    font-weight: bold;
}
.text-h5 {
    font-weight: bold;
    margin-bottom: 10px;
}
</style>
