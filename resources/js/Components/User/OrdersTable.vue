<template>
    <div class="py-5 px-4">
        <div class="text-h4 mb-4">I miei ordini</div>
        <v-data-table-server
            :expand-on-click="false"
            :items-per-page="options.itemsPerPage"
            :headers="headers"
            :items="items"
            :items-length="totalItems"
            :loading="loading"
            loading-text="Caricamento in corso..."
            @update:options="fetchOrders"
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

    // API request for server-side data
    axios
        .get('/api/user-orders', options.value)
        .then((response) => {
            const { data, total } = response.data;
            items.value = data;
            totalItems.value = total;
        })
        .finally(() => {
            loading.value = false;
        });
}

// Watch options to refetch data when changes occur
watch(options, fetchOrders, { deep: true });
</script>
