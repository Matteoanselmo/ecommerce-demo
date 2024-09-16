<template>
<v-container fluid>
    <v-row justify="center">
    <v-col elevation="8" cols="12">
        <v-img

            v-if="banner"
            :src="'/storage/' + banner.image_path"
            height="400"
            width="100%"
            alt="Banner Image"
            cover
        >
        </v-img>
            <v-progress-circular
            v-else
            indeterminate
            color="primary"
            ></v-progress-circular>
    </v-col>
    </v-row>
</v-container>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const banner = ref(null);

// Funzione per ottenere il banner dall'API
function fetchBanner() {
axios.get('/api/discount-banner')
    .then(res => {
    if (res.data.length > 0) {
        banner.value = res.data[0]; // Supponiamo che l'array contenga solo un elemento
    }
    })
    .catch(error => {
    console.error("Errore nel recupero del banner:", error);
    });
}

// Richiama il banner all'inizializzazione del componente
onMounted(() => {
fetchBanner();
});
</script>

<style scoped>
.banner-card {
position: relative;
}

.v-img {
border-radius: 12px;
}

.v-card-title {
background-color: rgba(0, 0, 0, 0.5);
padding: 16px;
position: absolute;
bottom: 0;
left: 0;
width: 100%;
}
</style>
