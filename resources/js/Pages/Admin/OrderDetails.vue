<template>
    <Head
        title="Dettaglio Ordine"
    />
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
            <v-card class="pa-4" elevation="0" rounded="xl" height="270">
                <h2 class="text-h5">Indirizzo di Spedizione</h2>
                <p><strong>Destinatario:</strong> {{ order.shipping_address.recipient_name }}</p>
                <p><strong>Indirizzo:</strong> {{ order.shipping_address.address }}, {{ order.shipping_address.house_number }}</p>
                <p><strong>CAP:</strong> {{ order.shipping_address.postal_code }}</p>
                <p><strong>Città:</strong> {{ order.shipping_address.city }}</p>
                <p><strong>Telefono:</strong> {{ order.shipping_address.phone_number }}</p>
            </v-card>
        </v-col>
        <v-col cols="6">
            <v-card class="pa-4" elevation="0" rounded="xl" height="270">
                <h2 class="text-h5">Indirizzo di Fatturazione ({{ order.billing_address.type }})</h2>
                <p><strong>Nome/Denominazione:</strong> {{ order.billing_address.type === 'company' ? order.billing_address.company_name : order.billing_address.first_name + ' ' + order.billing_address.last_name }}</p>
                <p v-if="order.billing_address.type === 'company'"><strong>Partita IVA:</strong> {{ order.billing_address.vat_number }}</p>
                <p v-if="order.billing_address.type === 'company'"><strong>Codice SDI:</strong> {{ order.billing_address.sdi_code }}</p>
                <p><strong>Codice Fiscale:</strong> {{ order.billing_address.tax_code }}</p>
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
                <v-data-table
                    :headers="headers"
                    :items="order.products"
                    item-value="id"
                    class="elevation-0"
                    dense
                >
                    <template v-slot:top>
                        <v-toolbar flat>
                            <v-toolbar-title><strong>Prodotti nell'Ordine</strong></v-toolbar-title>
                            <v-spacer></v-spacer>
                        </v-toolbar>
                    </template>
                    <template v-slot:[`item.name`]="{ item }">
                        <Link :href="route('admin.product.crud', item)" class="text-info">
                            {{ item.name }}
                        </Link>
                    </template>
                    <template v-slot:[`item.pivot.price_at_order`]="{ item }">
                        {{ $formatPrice(item.pivot.price_at_order) }}
                    </template>
                </v-data-table>
            </v-card>
        </v-col>
    </v-row>
    </v-container>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue';
import { usePage, Head, Link } from '@inertiajs/vue3';

const headers = ref([
    { title: 'Nome', value: 'name' },
    { title: 'Quantità', value: 'pivot.order_quantity' },
    { title: 'Prezzo', value: 'pivot.price_at_order' },
]);


const { props } = usePage();
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
