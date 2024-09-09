<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <v-autocomplete
                    :loading="loading"
                    :items="productsToSearch"
                    density="comfortable"
                    placeholder="Cerca il prodotto giusto per te"
                    prepend-inner-icon="mdi-magnify"
                    variant="solo"
                    item-value="id"
                    item-title="name"
                    rounded
                    v-model="selectedProductId"
                    @update:search="handleInputChange"

                >
                    <template v-slot:item="{ item, attrs }">
                        <v-list-item
                        v-bind="attrs"
                        @click="navigateToProduct(item.raw.id)"
                        :title="item.title"
                        :prepend-avatar="item.raw.cover_image_url">
                        </v-list-item>
                    </template>
                </v-autocomplete>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import debounce from 'lodash/debounce';
import { router } from '@inertiajs/vue3';

const loading = ref(false);
const productsToSearch = ref([]);
const selectedProductId = ref(null);

// Funzione per gestire il cambiamento di input con debounce
const handleInputChange = debounce((value) => {
    if (value !== '') {
        fetchSearchResults(value);
    }
}, 300); // ritardo di 300ms

function fetchSearchResults (query) {
    loading.value = true;
    axios.get('/api/search-products', {
        params: { query }
    }).then((res) => {
        loading.value = false;
        productsToSearch.value = res.data;
        console.log(productsToSearch.value)
    }).catch((err) => {
        console.log(err)
    })
}

// Funzione per navigare alla pagina del prodotto selezionato
function navigateToProduct(productId) {
    if (productId) {
        router.get(route('product.detail', { product: productId }));
    }
}

</script>
