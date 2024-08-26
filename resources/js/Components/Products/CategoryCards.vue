<template>
    <div class="d-flex category-scroll">

        <v-card v-for="(item, index) in items"
        :key="index"
        class="text-center"
        outlined
        rounded
        elevation="0"
        >
        <template v-slot:actions>
            <Link :href="route(item.link, item.params)" :text="item.title"/>
        </template>

        </v-card>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

const items = ref([]);

function fetchCategorie() {
    axios.get('/api/categories')
    .then((res) => {
        res.data.forEach(category => {
        const route = {
            title: category.name,
            link: 'products.list',  // Nome della rotta Laravel
            params: { category: category.name.toLowerCase() } // Parametro dinamico
        };
        items.value.push(route);
        });
    })
    .catch((error) => {
        console.error('Error fetching categories:', error);
    });
}

// Calcola il numero di colonne automaticamente
const autoCols = computed(() => {
    return Math.min(Math.round(12 / items.value.length), 4);  // Limita il massimo a 4 colonne
});

// Carica le categorie al montaggio del componente
onMounted(() => {
    fetchCategorie();
});
</script>

<style scoped>
.category-scroll {
    max-width: 800px;
    display: flex;
    overflow-x: auto;
    padding-bottom: 10px;
    scrollbar-color: var(--v-primary-base) transparent; /* Colore della scrollbar */
    scrollbar-width: thin; /* Larghezza della scrollbar */
}

.category-scroll::-webkit-scrollbar {
    height: 8px;
}

.category-scroll::-webkit-scrollbar-thumb {
    background-color: var(--v-primary-base); /* Colore del thumb */
    border-radius: 10px;
}

.category-scroll::-webkit-scrollbar-track {
    background: transparent;
}

.v-card {
    min-width: 200px; /* Imposta una larghezza minima per le card */
    height: 150px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
}

.v-card-text {
    text-transform: capitalize;
}
</style>
