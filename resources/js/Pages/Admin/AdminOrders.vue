<template>
    <Head title="Ordini" />
    <v-container>
        <v-row>
            <v-col cols="6" class="pb-0 mt-5">
                <div class="d-flex justify-between">
                    <v-text-field
                        variant="solo-filled"
                        prepend-inner-icon="mdi-magnify"
                        v-model="searchName"
                        label="Nome"
                        clearable
                        @input="debouncedFetchOrders"
                    ></v-text-field>
                    <v-text-field
                        variant="solo-filled"
                        prepend-inner-icon="mdi-magnify"
                        v-model="searchOrderNumber"
                        label="Ordine"
                        clearable
                        @input="debouncedFetchOrders"
                        class="px-2"
                    ></v-text-field>
                    <v-text-field
                        variant="solo-filled"
                        prepend-inner-icon="mdi-magnify"
                        v-model="searchShippingNumber"
                        label="Spedizione"
                        clearable
                        @input="debouncedFetchOrders"
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
                    :search-name="searchName"
                    :search-order-number="searchOrderNumber"
                    :search-shipping-number="searchShippingNumber"
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

// Aggiungi variabili per i campi di ricerca
const searchName = ref('');
const searchOrderNumber = ref('');
const searchShippingNumber = ref('');

const headers = ref([
    {
        title: 'Utente',
        align: 'start',
        sortable: false,
        key: 'user.name',
    },
    {
        title: 'Numero Ordine',
        align: 'start',
        sortable: false,
        key: 'order_number',
    },
    {
        title: 'Data',
        align: 'start',
        sortable: false,
        key: 'order_date',
    },
    {
        title: 'Stato',
        align: 'start',
        sortable: false,
        key: 'status',
    },
    {
        title: 'Numero spedizione',
        align: 'start',
        sortable: false,
        key: 'shipping_number',
    },
    {
        title: "Azioni",
        key: "actions",
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
    const searchQuery = options.search || { name: searchName.value, shippingNumber: searchShippingNumber.value, orderNumber: searchOrderNumber.value };

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
