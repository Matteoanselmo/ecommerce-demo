<template>
    <v-container fluid>
        <v-row>
            <v-col cols="12">
                <v-autocomplete
                    :loading="productStore.loading"
                    :items="productStore.productsToSearch"
                    density="comfortable"
                    placeholder="Cerca il prodotto giusto per te"
                    prepend-inner-icon="mdi-magnify"
                    variant="solo"
                    item-value="id"
                    item-title="name"
                    rounded
                    v-model="productStore.selectedProductId"
                    @update:search="handleInputChange"
                    @select="navigateToProduct"
                >
                    <template v-slot:item="{ item, attrs }">
                        <v-list-item
                            v-bind="attrs"
                            @click="navigateToProduct(item.raw.id)"
                            :title="item.raw.name"
                            :prepend-avatar="item.raw.cover_image_url"
                        >
                        </v-list-item>
                    </template>
                </v-autocomplete>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import debounce from 'lodash/debounce';
import { useProductStore } from '@/stores/product.store';

// Inizializza lo store Pinia
const productStore = useProductStore();

// Funzione per gestire il cambiamento di input con debounce
const handleInputChange = debounce((value) => {
    if (value !== '') {
        productStore.fetchSearchResults(value); // Usa lo store per effettuare la ricerca
    }
}, 300); // ritardo di 300ms

// Funzione per navigare alla pagina del prodotto selezionato
function navigateToProduct(productId) {
    productStore.navigateToProduct(productId); // Usa lo store per la navigazione
}
</script>
