<template>
    <Head title="Ordini" />
    <v-container fluid>
        <v-row>
            <v-col cols="6" class="pb-0 mt-5">
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
            </v-col>
            <v-col cols="12">
                <TableServer
                    :totalItems="totalItems"
                    :items="orders"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                    :type="'order'"
                    :page="page"
                    :search-fields="searchFields"
                    :crud="['update', 'delete']"
                    @updateItems="fetchOrders"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import TableServer from '@/Components/Tables/TableServer.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { debounce } from 'lodash';

// Stato per gli ordini e altri parametri
const orders = ref([]);
const totalItems = ref(0);
const itemsPerPage = ref(10);
const page = ref(1);
const loading = ref(true);

// Array di campi di ricerca (nome, numero ordine, numero spedizione)
const searchFields = ref([
    { key: 'name', value: '', label: 'Nome', icon: 'mdi-magnify' },
    { key: 'orderNumber', value: '', label: 'Ordine', icon: 'mdi-magnify' },
    { key: 'shippingNumber', value: '', label: 'Spedizione', icon: 'mdi-magnify' },
]);

const headers = ref([
    {
        title: 'Utente',
        align: 'start',
        sortable: false,
        key: 'user_name',
        type: 'text'
    },
    {
        title: 'Numero Ordine',
        align: 'start',
        sortable: false,
        key: 'order_number',
        type: 'text'
    },
    {
        title: 'Data Ordine',
        align: 'start',
        sortable: false,
        key: 'order_date',
        type: 'date',
        isEditable: false
    },
    {
        title: 'Data Spedizione',
        align: 'start',
        sortable: false,
        key: 'data',
        type: 'date',
        isEditable: true
    },
    {
        title: 'Stato',
        align: 'start',
        sortable: false,
        key: 'status',
        model: 'status',
        type: 'select',
        items: ['confirmed', 'in_progress ', 'on_delivery', 'delivered ', 'returned'],
        isEditable: true
    },
    {
        title: 'Numero spedizione',
        align: 'start',
        sortable: false,
        key: 'shipping_number',
        type: 'text',
        isEditable: true
    },
    {
        title: 'Tipo di Pagamento',
        align: 'start',
        sortable: false,
        key: 'payment',
        type: 'text'
    },
    {
        title: 'Totale Ordine',
        align: 'start',
        sortable: false,
        key: 'total_amount',
        type: 'number',
        isEditable: false
    },
    {
        title: 'Fattura',
        align: 'start',
        sortable: false,
        key: 'fattura',
        model: 'fattura',
        type: 'input',
        isEditable: true
    },
    {
        title: "Azioni",
        key: "actions",
        align: 'end',
        sortable: false
    },
]);

// Funzione per recuperare i dati degli ordini
function fetchOrders(options = {}) {
    loading.value = true;

    const currentPage = options.page || page.value;
    const currentItemsPerPage = options.itemsPerPage || itemsPerPage.value;
    const sortBy = options.sortBy || 'id';
    const sortDirection = options.sortDirection || 'asc';

    // Costruiamo l'oggetto di ricerca dall'array searchFields
    const searchQuery = searchFields.value.reduce((acc, field) => {
        acc[field.key] = field.value;
        return acc;
    }, {});

    axios
        .get(`/api/orders`, {
            params: {
                page: currentPage,
                per_page: currentItemsPerPage,
                sort_by: sortBy,
                sort_direction: sortDirection,
                search_name: searchQuery.name,
                search_shipping_number: searchQuery.shippingNumber,
                search_order_number: searchQuery.orderNumber,
            },
        })
        .then((res) => {
            orders.value = res.data.data;
            totalItems.value = res.data.total;
            page.value = res.data.current_page;
            loading.value = false;
        })
        .catch((e) => {
            console.log(e);
            loading.value = false;
        });
}

// Utilizza lodash debounce per ritardare la chiamata alla funzione di ricerca
const debouncedFetchOrders = debounce(fetchOrders, 500);
</script>
