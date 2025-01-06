<script setup>
import { ref } from 'vue';

// qua dovrai fare una axios get per ottenenre le recensioni da google
const reviews = ref([
{
    id: 1,
    authorName: 'Mario Rossi',
    authorPhoto: 'https://via.placeholder.com/40',
    rating: 5,
    relativeTimeDescription: '1 settimana fa',
    text: 'Ottimo servizio, personale gentile e professionale!',
},
{
    id: 2,
    authorName: 'Giulia Bianchi',
    authorPhoto: 'https://via.placeholder.com/40',
    rating: 4,
    relativeTimeDescription: '3 giorni fa',
    text: 'Buona esperienza, ma migliorerei il supporto clienti.',
},
{
    id: 3,
    authorName: 'Luca Verdi',
    authorPhoto: 'https://via.placeholder.com/40',
    rating: 5,
    relativeTimeDescription: '2 settimane fa',
    text: 'Servizio eccellente e risultati straordinari!',
},
]);
</script>

<template>
<v-container class="py-5">
    <v-row>
        <v-col cols="12">
            <div class="text-center text-h2 text-uppercase  mb-4">Cosa pensano di noi</div>
        </v-col>
        <v-col cols="12" md="6" lg="4" v-for="review in reviews" :key="review.id">
            <v-card elevation="0" class="mb-4" rounded="xl">
            <v-card-title>
                <v-avatar size="40" class="me-3">
                    <img :src="review.authorPhoto" alt="Foto profilo" />
                </v-avatar>
                <div>
                    <div>{{ review.authorName }}</div>
                    <div class="text-caption text-grey">{{ review.relativeTimeDescription }}</div>
                </div>
            </v-card-title>
            <v-card-subtitle>
                <v-rating :length="5" :model-value="review.rating" readonly dense color="primary" />
            </v-card-subtitle>
            <v-card-text>
                {{ review.text }}
            </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</v-container>
</template>

<style scoped>
.text-grey {
color: rgba(0, 0, 0, 0.6);
}
</style>
