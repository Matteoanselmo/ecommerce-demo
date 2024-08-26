<template>
    <v-container class="position-sticky top-0">
    <!-- Intestazione con il pulsante per aprire/chiudere i filtri -->
    <v-row align="center" justify="space-between" class="mb-4">
        <v-btn icon @click="toggleFilters">
        <v-icon>{{ filtersOpen ? 'mdi-chevron-left' : 'mdi-filter' }}</v-icon>
        <span v-if="filtersOpen">Filtri</span>
        </v-btn>
        <v-btn text color="error" @click="clearFilters">
        Elimina Filtri
        </v-btn>
    </v-row>

    <!-- Sezione Categorie -->
    <v-row v-if="filtersOpen">
        <v-col cols="12">
        <h5>Categorie</h5>
        </v-col>
        <v-col>
            <CategoryCards/>
        </v-col>
    </v-row>

    <v-divider v-if="filtersOpen" class="my-4"></v-divider>

    <!-- Sezione Filtri -->
    <v-accordion v-if="filtersOpen" v-model="expandedFilters">
        <v-expansion-panels>
        <v-expansion-panel v-for="(filter, index) in filterOptions" :key="index">
            <v-expansion-panel-title>{{ filter.name }}</v-expansion-panel-title>
            <v-expansion-panel-text>
            <!-- Contenuto dinamico dei filtri -->
            </v-expansion-panel-text>
        </v-expansion-panel>
        </v-expansion-panels>
    </v-accordion>
    </v-container>
</template>

<script setup>
import { ref } from 'vue';
import CategoryCards from './CategoryCards.vue';
const filtersOpen = ref(true);
const expandedFilters = ref([]);
const categories = ref([
    { title: 'Sedie Girevoli' },
    { title: 'Sedie e Poltrone' },
    { title: 'Mobili e Complementi' },
]);

const filterOptions = ref([
    { name: 'Prezzo' },
    { name: 'Tipologia' },
]);

const toggleFilters = () => {
    filtersOpen.value = !filtersOpen.value;
};

const clearFilters = () => {
    expandedFilters.value = [];
    // Logica per resettare i filtri
};
</script>

<style scoped>
.v-btn {
    font-weight: bold;
}

.v-divider {
    margin: 10px 0;
}
</style>
