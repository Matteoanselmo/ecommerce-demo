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
                        @input="debouncedFetchcategories"
                        class="px-2"
                    ></v-text-field>
                </div>
            </v-col>
            <v-col cols="12">
                <TableServer
                    :totalItems="totalItems"
                    :items="categories"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                    :type="'category'"
                    :page="page"
                    :search-fields="searchFields"
                    @updateItems="fetchcategories"
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
const categories = ref([]);
const totalItems = ref(0);
const itemsPerPage = ref(10);
const page = ref(1);
const loading = ref(true);

// Array di campi di ricerca (nome, numero ordine, numero spedizione)
const searchFields = ref([
    { key: 'name', value: '', label: 'Nome', icon: 'mdi-magnify' },
]);

const headers = ref([
    {
        title: 'Nome',
        align: 'start',
        sortable: false,
        key: 'name',
        type: 'text'
    },
    {
        title: 'Icona',
        align: 'start',
        sortable: false,
        key: 'icon',
        type: 'text'
    },
    {
        title: "Azioni",
        key: "actions",
        align: 'end',
        sortable: false
    },
]);

// Funzione per recuperare i dati degli ordini
function fetchcategories(options = {}) {
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
        .get(`/api/categories`, {
            params: {
                page: currentPage,
                per_page: currentItemsPerPage,
                sort_by: sortBy,
                sort_direction: sortDirection,
                search_name: searchQuery.name,
            },
        })
        .then((res) => {
            categories.value = res.data.data;
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
const debouncedFetchcategories = debounce(fetchcategories, 500);
</script>
