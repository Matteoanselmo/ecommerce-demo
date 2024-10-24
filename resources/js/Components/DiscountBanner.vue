<template>
<v-container fluid>
    <v-row >
        <v-col elevation="8" cols="12">
            <v-parallax v-if="banner && banner.image_path" :src="'/storage/' + banner.image_path"></v-parallax>
                <div v-else>
                    <!-- Messaggio di caricamento o fallback -->
                    <v-skeleton-loader type="image" />
                </div>
        </v-col>
    </v-row>
</v-container>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

const banner = ref(null);

// Funzione per ottenere il banner dall'API
function fetchBanner() {
axios.get('/api/discount-banner')
    .then(res => {
        if (res.data.length > 0) {
            banner.value = res.data[0]; // Supponiamo che l'array contenga solo un elemento
            console.log(banner.value.image_path)
        }
    })
    .catch(error => {
        console.error("Errore nel recupero del banner:", error);
    });
}


fetchBanner();
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
