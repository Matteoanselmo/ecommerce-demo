<template>
<v-container>
    <v-row>
    <v-col cols="12" class="d-flex align-center justify-center flex-column">
        <div class="text-h3 font-weight-bold">{{ title }}</div>
    </v-col>

    <!-- Cicla sugli sconti -->
    <v-col cols="12">
        <!-- Carousel per i prodotti associati a ciascuno sconto -->
        <v-carousel
        hide-delimiter-background
        cycle
        height="auto"
        class="my-5"
        :continuous="true"
        >
        <v-carousel-item
            v-for="(group, index) in productGroups"
            :key="index"
            class="d-flex justify-center align-center"
        >
            <v-row>
            <v-col
                v-for="(promoProduct, i) in group"
                :key="i"
                cols="12"
                sm="6"
                md="4"
                lg="3"
            >
                <ProductCard
                :product="promoProduct"
                />
            </v-col>
            </v-row>
        </v-carousel-item>
        </v-carousel>
    </v-col>
    </v-row>
</v-container>
</template>

<script setup>
import { ref, watchEffect } from 'vue';
import ProductCard from './ProductCard.vue'; // Componente per i prodotti

// Props ricevuti dal componente padre
const props = defineProps({
promoProducts: Array,
title: String,
});

const productGroups = ref([]); // Variabile che conterrà i gruppi di prodotti

// Funzione per determinare il numero di prodotti da visualizzare in base alla larghezza dello schermo
function getGroupSize() {
const width = window.innerWidth;
if (width >= 1200) return 4; // Su schermi grandi (desktop), 4 prodotti
if (width >= 960) return 3;  // Su schermi medi (tablet), 3 prodotti
if (width >= 600) return 2;  // Su schermi piccoli, 2 prodotti
return 1;                    // Su schermi molto piccoli, 1 prodotto
}

// Funzione per raggruppare i prodotti dinamicamente
function groupProducts(products) {
const groupSize = getGroupSize();
const groups = [];
for (let i = 0; i < products.length; i += groupSize) {
    groups.push(products.slice(i, i + groupSize));
}
return groups;
}

// Funzione che aggiorna i gruppi di prodotti quando cambia la dimensione dello schermo
function updateGroups() {
productGroups.value = groupProducts(props.promoProducts);
}

// Watcher che aggiorna i gruppi di prodotti ogni volta che cambia il numero di prodotti o la dimensione dello schermo
watchEffect(() => {
updateGroups();
});

// Aggiungi un listener per il ridimensionamento della finestra
window.addEventListener('resize', updateGroups);
</script>

<style scoped>

</style>
