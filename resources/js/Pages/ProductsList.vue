<template>
    <Head title="Prodotti" />
    <search-bar></search-bar>
    <v-container>
        <v-row>

        </v-row>
        <v-row>
            <v-col>
                <FiltersPanel/>
            </v-col>
            <v-col aria-colspan="12" sm="6" md="4" lg="3" v-for="(product, i) in productsStore.products" :key="i">
                <ProductCard
                    :product="product"
                />
            </v-col>
        </v-row>
    </v-container>
    <v-pagination
        v-model="productsStore.page"
        :length="productsStore.pagination.last_page"
        @input="onPageChange"
    />
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { useProductStore } from "@/stores/product.store";
import { watch } from 'vue';
import ProductCard from '@/Components/Products/ProductCard.vue';
import FiltersPanel from '@/Components/Products/FiltersPanel.vue';
const productsStore = useProductStore();

const onPageChange = () => {
    productsStore.getProducts(productsStore.page);
};

// Watch per osservare i cambiamenti nella pagina
watch(() => productsStore.page, (newPage) => {
    productsStore.getProducts(newPage);
});

productsStore.resetPage();
productsStore.getProducts();
</script>
