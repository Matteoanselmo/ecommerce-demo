<template>
    <div class="d-flex category-scroll justify-center" :max-width="maxWidth">

        <v-card v-for="(item, index) in items"
        :key="index"
        class="text-center"
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
const props = defineProps({
    maxWidth: {
        required: false,
        type: String,
        default: '800'
    }
})
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
    min-width: 100px; /* Imposta una larghezza minima per le card */
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 10px;
}

.v-card:hover {
    transform: scale(1.05);
    transition: all .125s;
}

.v-card-text {
    text-transform: capitalize;
}
</style>
