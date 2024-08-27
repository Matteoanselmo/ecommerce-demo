<template>
    <v-container fluid>
    <v-row>
        <v-col cols="12">
        <v-autocomplete
            :items="productsToSearch"
            class="mx-auto"
            density="comfortable"
            placeholder="Cerca il prodotto giusto per te"
            prepend-inner-icon="mdi-magnify"
            style="max-width: 100%;"
            variant="solo"
            auto-select-first
            item-props
            rounded
            v-model:search="productsStore.searchString"
            @update:search="handleInputChange"
        ></v-autocomplete>
        </v-col>
    </v-row>
    </v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import debounce from 'lodash/debounce';
import { useProductStore } from "@/stores/product.store";
const productsStore = useProductStore();

const page = usePage();
const authUser = ref(page.props.auth.user);

const productsToSearch = ref([]);

// Funzione per gestire il cambiamento di input con debounce
const handleInputChange = debounce((value) => {
    console.log(value)
    if(value !== ''){
        productsStore.performSearch();
    }
}, 500); // ritardo di 300ms


// const saveSearch = (searchQuery) => {
//     if (authUser.value) {
//     axios.post('/api/user-searches', { search_query: searchString.value })
//         .then(response => {
//             performSearch(searchQuery);
//         })
//         .catch(error => {
//         console.error('Error saving search:', error);
//         });
//     } else {
//     console.log('User not logged in. Search not saved.');
//     performSearch(searchQuery);
//     }
// };

// const performSearch = (searchQuery) => {
//     axios.post('/api/search', { query: searchString.value })
//     .then(response => {
//         const products = response.data;
//         console.log(response.data)
//         // router.push({ name: 'products.list', params: { products } });
//     })
//     .catch(error => {
//         console.error('Error performing search:', error);
//     });
// };


//     }).catch((err) => {
//         console.error(err)
//     })
// }

// const getUserSearches = () => {
//     if (authUser.value) {
//     axios.get('/api/user-searches')
//         .then(response => {
//         productsToSearch.value = response.data.map(search => search.search_query);
//         })
//         .catch(error => {
//         console.error('Error fetching user searches:', error);
//         });
//     }
// };

// onMounted(() => {
//     getUserSearches();
// });
</script>
