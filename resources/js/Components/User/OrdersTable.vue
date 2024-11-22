<template>
    <div class="py-5 px-4">
        <div class="text-h4 mb-4">I miei ordini</div>
        <div class="d-flex justify-between">
            <!-- Cicliamo su searchFields per generare i campi di input -->
            <v-text-field
                v-for="(field, index) in searchFields"
                :key="index"
                variant="solo-filled"
                :prepend-inner-icon="field.icon"
                v-model="field.value"
                :label="field.label"
                clearable
                @input="debouncedFetchOrders"
                class="px-2"
            ></v-text-field>
        </div>
        <v-data-table-server
            :expand-on-click="false"
            :items-per-page="options.itemsPerPage"
            :headers="headers"
            :items="items"
            :items-length="totalItems"
            :loading="loading"
            loading-text="Caricamento in corso..."
            item-value="name"
            @update:options="updateOptions"
        >
            <template #item.payment="{ item }">
                <v-chip color="success" text="white">{{ item.payment }}</v-chip>
            </template>
            <template #item.status="{ item }">
                <v-chip v-if="item.status === 'confirmed'" color="success"  text="white">{{ item.status }}</v-chip>
                <v-chip v-else-if="item.status === 'delivered'" color="warning" text="white">{{ item.status }}</v-chip>
                <v-chip v-else-if="item.status === 'on_delivery'" color="warning" text="white">{{ item.status }}</v-chip>
                <v-chip v-else-if="item.status === 'in_progress'" color="info" text="white">{{ item.status }}</v-chip>
                <v-chip v-else-if="item.status === 'returned'" color="danger" text="white">{{ item.status }}</v-chip>
            </template>
            <template #item.tracking="{ item }">
                <Link href="/" class="text-decoration-none">{{ item.tracking }}</Link>
            </template>
            <template #item.details="{ item }">
                <Link  href="/" class="text-decoration-none">{{ item.details }}</Link>
            </template>
            <template #item.total_amount="{ item }">
                {{ $formatPrice(item.total_amount) }}
            </template>
            <template #item.fattura="{ item }">
                <v-btn icon v-if="item.fattura" size="small">
                    <v-icon icon="mdi mdi-download"></v-icon>
                </v-btn>
            </template>
        </v-data-table-server>
    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

const headers = [
    { title: "Ordine", value: "order_number", align: "start" },
    { title: "Data", value: "order_date" },
    { title: "Spedito in", value: "shipped_in" },
    { title: "Pagamento", value: "payment" },
    { title: "Stato Ordine", value: "status" },
    { title: "Spedizione", value: "shipping_number", sortable: false },
    { title: "Dettagli", value: "details", sortable: false },
    { title: "Totale", value: "total_amount" },
    { title: "Fattura", value: "fattura", sortable: false },
];

const searchFields = ref([
    { key: 'orderNumber', value: '', label: 'Ordine', icon: 'mdi-magnify' },
    { key: 'shippingNumber', value: '', label: 'Spedizione', icon: 'mdi-magnify' },
]);

const items = ref([]);
const totalItems = ref(0);
const loading = ref(false);
const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    sortDesc: [],
    groupBy: [],
    groupDesc: [],
    search: '',
});

function fetchOrders() {
    loading.value = true;

     // Costruiamo l'oggetto di ricerca dall'array searchFields
    const searchQuery = searchFields.value.reduce((acc, field) => {
        acc[field.key] = field.value;
        return acc;
    }, {});

    // API request for server-side data
    axios
        .get('/api/user-orders', {
            params: {
                page: options.value.page,
                per_page: options.value.itemsPerPage,
                sortBy: options.value.sortBy,
                sortDesc: options.value.sortDesc,
                search: options.value.search,
                search_shipping_number: searchQuery.shippingNumber,
                search_order_number: searchQuery.orderNumber,
            },
        })
        .then((res) => {
            items.value = res.data.data;
            totalItems.value = res.data.total;
        })
        .finally(() => {
            loading.value = false;
        }).catch((e) => {
            loading.value = false;
            console.error(e)
        })
}

function updateOptions(newOptions) {
    options.value = { ...options.value, ...newOptions };
    fetchOrders(); // Chiamare la funzione per aggiornare i dati
}

const debouncedFetchOrders = debounce(fetchOrders, 500);

// Aggiungi un watcher per osservare i cambiamenti nei campi di ricerca
watch(
    () => searchFields.value.map(field => field.value), // Osserva i valori dei campi di ricerca
    () => {
        debouncedFetchOrders(); // Chiamata debounce quando un valore cambia
    }
);
</script>
