<template>
    <div class="text-center">
        <v-rating
            v-model="calculatedRating"
            active-color="secondary"
            color="primary"
            half-increments
            hover
        ></v-rating>
    </div>
</template>

<script setup>
import { ref, watchEffect } from 'vue';

const props = defineProps({
    ratings: {
    type: Array,
    required: true,
    },
});

const calculatedRating = ref(0);

watchEffect(() => {
    if (props.ratings.length === 0) {
    calculatedRating.value = 0;
    return;
    }

    const numericRatings = props.ratings.map(rating => parseFloat(rating));
    const sum = numericRatings.reduce((acc, rating) => acc + rating, 0);
    const average = sum / numericRatings.length;

    // Arrotonda la media al più vicino 0.5
    calculatedRating.value = Math.round(average * 2) / 2;
});
</script>

<style scoped>
/* Stile opzionale per la personalizzazione */
</style>
