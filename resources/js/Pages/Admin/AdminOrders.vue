<template>
    <v-container>
        <v-row>
            <v-col>
                <TableServer
                    :totalItems="totalItems"
                    :items="orders"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import TableServer from '@/Components/Tables/TableServer.vue';
import { ref } from 'vue';
const orders = ref([]);
const totalItems = ref();
const itemsPerPage = ref(0);
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
])
function fetchOrders () {
    axios
        .get(`/api/orders?page=${page.value}`)
        .then((res) => {
            itemsPerPage.value = res.data.per_page;
            orders.value = res.data.data;
            totalItems.value = res.data.total;
            loading.value = false;
            console.log(res.data);
        })
        .catch((e) => {
            console.log(e);
        });
};

fetchOrders();
</script>

<style>

</style>
