<template>
    <v-container fluid>
    <v-row>
        <v-col cols="12">
        <v-autocomplete
            :items="productsToSearch"
            class="mx-auto"
            density="comfortable"
            menu-icon=""
            placeholder="Cerca il prodotto giusto per te"
            prepend-inner-icon="mdi-magnify"
            style="max-width: 100%;"
            variant="solo"
            auto-select-first
            item-props
            rounded
            @change="saveSearch"
        ></v-autocomplete>
        </v-col>
    </v-row>
    </v-container>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { usePage } from '@inertiajs/vue3';
import axios from 'axios';

// Recupera l'utente autenticato dalle prop di Inertia
const page = usePage();
const authUser = ref(page.props.auth.user);

console.log(authUser);

const productsToSearch = ref([]);

// Funzione per salvare la ricerca
const saveSearch = (searchQuery) => {
    if (authUser.value) {
    axios.post('/api/user-searches', { search_query: searchQuery })
        .then(response => {
        console.log('Search saved:', response.data);
        })
        .catch(error => {
        console.error('Error saving search:', error);
        });
    } else {
    console.log('User not logged in. Search not saved.');
    }
};

// Recupera le ricerche salvate dell'utente loggato
const getUserSearches = () => {
    if (authUser.value) {
    axios.get('/api/user-searches')
        .then(response => {
            productsToSearch.value = response.data.map(search => search.search_query);
        })
        .catch(error => {
        console.error('Error fetching user searches:', error);
        });
    }
};

// Recupera le ricerche dell'utente quando il componente è montato
onMounted(() => {
    getUserSearches();
});
</script>
