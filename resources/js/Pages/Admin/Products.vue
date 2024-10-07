<template>
    <Head title="Prodotti" />
    <v-container fluid>
        <v-row>
            <v-col cols="10" class="pb-0 mt-5">
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
                        @input="debouncedfetchProducts"
                        class="px-2"
                    ></v-text-field>
                </div>
            </v-col>
            <v-col cols="12">
                <TableServer
                    :totalItems="totalItems"
                    :items="products"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                    :type="'product'"
                    :page="page"
                    :crud="['show']"
                    :search-fields="searchFields"
                    @updateItems="fetchProducts"
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

// Stato per i clienti e altri parametri
const products = ref([]);
const totalItems = ref(0);
const itemsPerPage = ref(10);
const page = ref(1);
const loading = ref(true);

// Array di campi di ricerca (nome ed email)
const searchFields = ref([
    { key: 'name', value: '', label: 'Nome', icon: 'mdi-magnify' },
    { key: 'min_price', value: '', label: 'Prezzo minimo', icon: 'mdi-magnify' },
    { key: 'max_price', value: '', label: 'Prezzo massimo', icon: 'mdi-magnify' },
    { key: 'search_category', value: '', label: 'Categoria', icon: 'mdi-magnify' },
]);

// Definisci le intestazioni della tabella
const headers = ref([
    {
        title: 'Nome',
        align: 'start',
        sortable: false,
        type: 'text',
        key: 'name',
    },
    {
        title: 'Descrizione',
        hidden: true,
        align: 'start',
        sortable: false,
        type: 'text',
        key: 'description',
    },
    {
        title: 'Categoria',
        align: 'start',
        sortable: false,
        type: 'text',
        key: 'category.name',
    },
    {
        title: 'Stock',
        align: 'start',
        sortable: false,
        type: 'number',
        key: 'stock_quantity',
    },
    {
        title: 'Prezzo',
        align: 'start',
        sortable: false,
        type: 'number',
        key: 'price',
    },
    {
        title: "Azioni",
        key: "actions",
        sortable: false
    },
]);

// Funzione per recuperare i dati degli utenti
function fetchProducts(options = {}) {
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
        .get(`/api/products`, {
            params: {
                page: currentPage,
                per_page: currentItemsPerPage,
                sort_by: sortBy,
                sort_direction: sortDirection,
                search_name: searchQuery.name,
                min_price: searchQuery.min_price,
                max_price: searchQuery.max_price,
                search_category: searchQuery.search_category,
            },
        })
        .then((res) => {
            products.value = res.data.data;
            totalItems.value = res.data.total;
            page.value = res.data.current_page;
            loading.value = false;
            console.log(res.data)
        })
        .catch((e) => {
            console.log(e);
            loading.value = false;
        });
}

// Utilizza lodash debounce per ritardare la chiamata alla funzione di ricerca
const debouncedfetchProducts = debounce(fetchProducts, 500);
</script>
