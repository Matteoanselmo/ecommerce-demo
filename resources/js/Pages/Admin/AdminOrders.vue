<template>
    <Head title="Ordini" />
    <v-container>
        <v-row>
            <v-col>
                <TableServer
                    :totalItems="totalItems"
                    :items="orders"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                    :type="'order'"
                    :page="page"
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

const orders = ref([]);
const totalItems = ref(0);
const itemsPerPage = ref(10);
const page = ref(1);
const loading = ref(true);

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
function fetchOrders(options = {}) {
    loading.value = true;

    const currentPage = options.page || page.value;
    const currentItemsPerPage = options.itemsPerPage || itemsPerPage.value;
    const sortBy = options.sortBy || 'id';
    const sortDirection = options.sortDirection || 'asc';
    const searchQuery = options.search || { name: '', shippingNumber: '', orderNumber: '' };


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

</script>

<style>
/* Il tuo stile qui */
</style>
