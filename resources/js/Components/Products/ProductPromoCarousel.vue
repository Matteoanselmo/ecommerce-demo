<template>
    <v-container>
    <v-row>
        <v-col cols="12" class="d-flex align-center justify-center flex-column">
        <div class="text-h3 font-weight-bold">{{ title }}</div>
        </v-col>

        <!-- Sezione per lo scroll orizzontale -->
        <v-col cols="12" class="d-flex justify-center">
        <VInfiniteScroll
            class="infinite-scroll"
            direction="horizontal"
            :items="promoProducts"
            :item-size="cardWidth"
            @load="load"
        >
        <template v-for="(item, index) in promoProducts" :key="index">
                <ProductCard :product="item" />
        </template>
        <template v-slot:empty>

        </template>
        </VInfiniteScroll>
        </v-col>
    </v-row>
    </v-container>
</template>

<script setup>
import { ref, onUnmounted, onMounted } from 'vue';
import ProductCard from './ProductCard.vue'; // Componente per i prodotti
import { VInfiniteScroll } from 'vuetify/lib/components/index.mjs';

// Props ricevuti dal componente padre
const props = defineProps({
    promoProducts: Array,
    title: String,
});

// Larghezza di ogni card (usata per calcolare lo spazio nel componente infinite scroll)
const cardWidth = ref(320); // Regola in base alla dimensione effettiva delle card

// Aggiorna la larghezza della card in base alla larghezza dello schermo
function updateCardWidth() {
    const width = window.innerWidth;
    if (width >= 1200) cardWidth.value = 280;
    else if (width >= 960) cardWidth.value = 240;
    else if (width >= 600) cardWidth.value = 200;
    else cardWidth.value = 160;
}

function load ({ done }) {
    done('empty')
}
// Aggiorna la larghezza della card quando la finestra viene ridimensionata
onMounted(() => {
    updateCardWidth();
    window.addEventListener('resize', updateCardWidth);
});

// Rimuovi l'evento quando il componente viene distrutto
onUnmounted(() => {
    window.removeEventListener('resize', updateCardWidth);
});
</script>

<style scoped>
.infinite-scroll {
    display: flex;
    overflow-x: auto;
    padding: 10px 0;
}

.infinite-scroll::-webkit-scrollbar {
    display: none; /* Nasconde la scrollbar su browser basati su Webkit */
}

.infinite-scroll {
    scrollbar-width: none; /* Nasconde la scrollbar su Firefox */
}
</style>
