<template>
    <v-navigation-drawer
    v-model="drawer"
    temporary
    location="right"
    width="400"
    >
    <v-list-item
        class="mt-3 mb-5"
        prepend-icon="mdi mdi-filter-cog-outline"
        title="Filtri"
    ></v-list-item>

    <v-divider></v-divider>

    <v-rating
        class="mb-5"
        title="Rating"
        v-model="ratingStars"
        active-color="secondary"
        color="primary"
        half-increments
        hover
        @update:modelValue="debouncedFilterProducts"
    ></v-rating>
    <v-divider></v-divider>

    <v-range-slider
        prepend-icon="mdi mdi-currency-eur"
        v-model="priceRange"
        min="1"
        max="200"
        step="0.1"
        thumb-label="always"
        @update:modelValue="debouncedFilterProducts"
    ></v-range-slider>

    <v-select
        :items="productStore.subCategories"
        label="Sotto-Categorie"
        prepend-icon="mdi mdi-shape-outline"
        class="mb-5 px-2"
        item-value="id"
        item-title="name"
        dense
        outlined
        v-model="selectedSubCategory"
        @change="filterProducts"
    ></v-select>
    </v-navigation-drawer>
    <v-main>
    <div class="d-flex justify-start align-center h-100">
        <v-btn
        color="primary"
        @click.stop="drawer = !drawer"
        prepend-icon="mdi mdi-filter-cog-outline"
        >
        Filtri
        </v-btn>
    </div>
    </v-main>
</template>

<script setup>
import { ref, watch } from 'vue';
import { useProductStore } from '@/stores/product.store';
import debounce from 'lodash/debounce';

const productStore = useProductStore();
const drawer = ref(false);
const priceRange = ref([0, 100]);
const ratingStars = ref(0);
const selectedSubCategory = ref(null);

// Funzione per filtrare i prodotti
const filterProducts = () => {
    productStore.fetchFilteredProducts({
    category: productStore.category,
    priceRange: priceRange.value,
    rating: ratingStars.value,
    subCategory: selectedSubCategory.value,
    });
};

const debouncedFilterProducts = debounce(filterProducts, 500);

productStore.fetchSubCategories();
</script>

<style scoped>
.sidebar-panel {
    max-width: 300px;
}
</style>
