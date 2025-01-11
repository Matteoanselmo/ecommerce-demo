<template>
    <Head title="Taglie" />
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
                        @input="debouncedFetchSizes"
                        class="px-2"
                    ></v-text-field>
                    <v-select
                        variant="solo-filled"
                        :items="categories"
                        item-title="name"
                        item-value="id"
                        label="Categoria"
                        clearable
                        v-model="selectedCategory"
                        @update:modelValue="debouncedFetchSizes"
                        class="px-2"
                    ></v-select>
                </div>
            </v-col>
            <v-col cols="12">
                <TableServer
                    :totalItems="totalItems"
                    :items="sizes"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                    :type="'size'"
                    :page="page"
                    :search-fields="searchFields"
                    @updateItems="fetchSizes"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import TableServer from '@/Components/Tables/TableServer.vue';
import { ref, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { debounce } from 'lodash';

// Stato per gli ordini e altri parametri
const sizes = ref([]);
const totalItems = ref(0);
const itemsPerPage = ref(10);
const page = ref(1);
const loading = ref(true);
const categories = ref([]); // Stato per memorizzare le categorie
const selectedCategory = ref(null); // Stato per la categoria selezionata
const categoryNames = ref([]);
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
        type: 'text',
        isEditable: true
    },
    {
        title: 'Categoria',
        align: 'start',
        sortable: false,
        key: 'category.name',
        model: 'category_id',
        type: 'select',
        items: categories,
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
function fetchSizes(options = {}) {
    loading.value = true;

    // Aggiungiamo dei fallback per assicurarci che tutte le variabili abbiano un valore di default
    const currentPage = options.page || page.value || 1;
    const currentItemsPerPage = options.itemsPerPage || itemsPerPage.value || 10;
    const sortBy = options.sortBy || 'id';
    const sortDirection = options.sortDirection || 'asc';

    // Costruzione dell'oggetto di ricerca dall'array searchFields
    const searchQuery = searchFields.value.reduce((acc, field) => {
        acc[field.key] = field.value;
        return acc;
    }, {});

    // Costruisce i parametri per la richiesta, aggiungendo category_id solo se è definito
    const params = {
        page: currentPage,
        per_page: currentItemsPerPage,
        sort_by: sortBy,
        sort_direction: sortDirection,
        search_name: searchQuery.name,
    };

    // Aggiungi category_id solo se è selezionato
    if (selectedCategory.value) {
        params.category_id = selectedCategory.value;
    }

    axios
        .get(`/api/sizes`, { params })
        .then((res) => {
            sizes.value = res.data.data;
            totalItems.value = res.data.total;
            page.value = res.data.current_page;
            loading.value = false;
            categories.value.forEach(category => {
                categoryNames.value.push(category.name);
            });
            console.log(res.data)
        })
        .catch((e) => {
            console.log(e);
            loading.value = false;
        });
}


// Funzione per recuperare le categorie
function fetchCategories() {
    axios.get(`/api/all-categories`).then((res) => {
        categories.value = res.data;
        console.log(categories.value)
    }).catch((e) => {
        console.log(e);
    });
}

// Utilizza lodash debounce per ritardare la chiamata alla funzione di ricerca
const debouncedFetchSizes = debounce(fetchSizes, 500);

fetchCategories();

</script>
