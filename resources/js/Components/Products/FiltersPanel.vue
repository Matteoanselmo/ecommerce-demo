<template>
        <v-navigation-drawer
            v-model="drawer"
            temporary
            location="right"
            width="300"
        >
            <v-list-item
                class="mt-3 mb-5"
                prepend-icon="mdi mdi-filter-cog-outline"
                title="Filtri"
            ></v-list-item>

            <v-divider></v-divider>

            <v-rating
                class="mb-5"
                title="Range"
                v-model="ratingStars"
                active-color="secondary"
                color="primary"
                half-increments
                hover
            ></v-rating>
            <v-divider></v-divider>

            <v-range-slider
                prepend-icon="mdi mdi-currency-eur"
                v-model="priceRange"
                step="1"
                thumb-label="always"
            ></v-range-slider>

            <CategoryCards
                class="px-10"
            />
        </v-navigation-drawer>
        <v-main >
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
import { ref } from 'vue';
import CategoryCards from './CategoryCards.vue';


const drawer = ref(false);
const priceRange = ref([20, 40]);
const ratingStars = ref(0);

</script>

<style scoped>
.sidebar-panel {
    max-width: 300px;
}

.v-toolbar-title {
    font-weight: bold;
}
</style>
