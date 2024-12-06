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
    class="px-2"
    item-value="id"
    item-title="name"
    dense
    outlined
    v-model="selectedSubCategory"
    @change="filterProducts"
    ></v-select>

    <v-select
    :items="['Prezzo crescente', 'Prezzo decrescente']"
    label="Ordina per prezzo"
    prepend-icon="mdi mdi-sort"
    dense
    outlined
    v-model="priceOrder"
    @update:model-value="filterProducts"
    ></v-select>

    <v-card
    prepend-icon="mdi mdi-eyedropper-variant"
    title="Seleziona il colore"
    elevation="0"
    >
    <v-card-item>
        <v-radio-group
        v-model="selectedColor"
        density="comfortable"
        inline
        @update:modelValue="filterProducts"
        >
        <v-radio
            v-for="(color, i) in productStore.colors"
            :key="i"
            class="mr-5"
            density="comfortable"
            true-icon="mdi-check"
            :color="color.name"
            :base-color="color.name"
            :value="color.name"
        ></v-radio>
        </v-radio-group>
    </v-card-item>
    </v-card>

    <div class="d-flex justify-center">
    <v-btn @click="resetFilters()">Reset</v-btn>
    </div>
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
import { ref } from "vue";
import { useProductStore } from "@/stores/product.store";
import debounce from "lodash/debounce";

const productStore = useProductStore();
const drawer = ref(false);
const priceRange = ref([0, 100]);
const ratingStars = ref(0);
const selectedColor = ref("");
const selectedSubCategory = ref(null);
const priceOrder = ref(""); // Aggiunto filtro per ordinamento

// Funzione per filtrare i prodotti
const filterProducts = () => {
productStore.fetchFilteredProducts({
    category: productStore.category,
    priceRange: priceRange.value,
    rating: ratingStars.value,
    subCategory: selectedSubCategory.value,
    color: selectedColor.value,
    order: priceOrder.value === "Prezzo crescente" ? "asc" : "desc", // Aggiunto ordine prezzo
});
};

function resetFilters() {
priceRange.value = [0, 100];
ratingStars.value = 0;
selectedSubCategory.value = null;
selectedColor.value = "";
priceOrder.value = "";

productStore.getProducts();
}

const debouncedFilterProducts = debounce(filterProducts, 500);

productStore.fetchSubCategories();
productStore.getColors();
</script>

<style scoped>
.sidebar-panel {
max-width: 300px;
}
</style>
