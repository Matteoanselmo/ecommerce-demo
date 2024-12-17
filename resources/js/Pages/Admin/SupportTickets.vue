<template>
    <Head title="Tickets" />
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
                        @input="debouncedFetchTickets"
                        class="px-2"
                    ></v-text-field>
                </div>
            </v-col>
            <v-col cols="12">
                <TableServer
                    :totalItems="totalItems"
                    :items="tickets"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                    :type="'ticket'"
                    :page="page"
                    :search-fields="searchFields"
                    @updateItems="fetchTickets"
                    :crud="['update', 'delete']"
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
const tickets = ref([]);
const totalItems = ref(0);
const itemsPerPage = ref(10);
const page = ref(1);
const loading = ref(true);

// Array di campi di ricerca (nome ed email)
const searchFields = ref([
    { key: 'name', value: '', label: 'Nome', icon: 'mdi-magnify' },
    { key: 'email', value: '', label: 'Email', icon: 'mdi-magnify' },
    { key: 'status', value: '', label: 'Status', icon: 'mdi-magnify' },
]);

// Definisci le intestazioni della tabella
const headers = ref([
    {
        title: 'Nome',
        align: 'start',
        sortable: false,
        type: 'text',
        key: 'user_name',
    },
    {
        title: 'Email',
        align: 'start',
        sortable: false,
        type: 'email',
        key: 'user_email',
    },
    {
        title: 'Soggetto',
        align: 'start',
        sortable: false,
        type: 'text',
        key: 'subject',
    },
    {
        title: 'Descrizione',
        align: 'start',
        sortable: false,
        type: 'text',
        key: 'description',
    },
    {
        title: 'Status',
        align: 'start',
        sortable: false,
        key: 'status',
        model: 'status',
        type: 'select',
        items: ['Aperto', 'In Attesa', 'Chiuso'],
        isEditable: true
    },
    {
        title: "Azioni",
        key: "actions",
        align: 'end',
        sortable: false
    },
]);

// Funzione per recuperare i dati degli utenti
function fetchTickets(options = {}) {
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
        .get(`/api/tickets`, {
            params: {
                page: currentPage,
                per_page: currentItemsPerPage,
                sort_by: sortBy,
                sort_direction: sortDirection,
                search_name: searchQuery.name,
                search_email: searchQuery.email,
                role_not: 'admin' // Filtra utenti con ruolo diverso da admin
            },
        })
        .then((res) => {
            tickets.value = res.data.data;
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
const debouncedFetchTickets = debounce(fetchTickets, 500);
</script>
