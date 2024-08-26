<template>
    <v-lazy
    :min-height="200"
    :options="{'threshold':0.5}"
    transition="fade-transition"
    >
    <template v-if="productsStore.loading">
        <v-skeleton-loader
        class="mx-auto my-4"
        max-width="344"
        type="card"
        height="400px"
        />
    </template>
    <template v-else>
        <v-card
        class="mx-auto my-4"
        max-width="344"
        elevation="4"
        rounded="lg"
        tile
        color="transparent"
        >
        <v-img
            :src="product.cover_image_url"
            alt="Product Image"
            height="400px"
            class="rounded-lg p-2"
            cover
        >
            <div class="overlay d-flex align-center justify-center h-100">
            <v-btn
                class="ma-0 hover-button"
                color="black"
                dark
                style="border-radius: 8px;"
            >
                See more info
            </v-btn>
            </div>
        </v-img>

        <v-card-subtitle color="black" class="text-h6 mt-3 mb-1">
            {{ product.name }}
        </v-card-subtitle>
        <v-card-text class="grey--text text--darken-2 mb-2">
            {{ product.category }}
        </v-card-text>

        <v-card-actions class="justify-between">
            <v-spacer></v-spacer>
            <span class="text-h6 font-weight-bold">${{ product.price }}</span>
        </v-card-actions>
        </v-card>
    </template>
    </v-lazy>
</template>

<script setup>
import { useProductStore } from "@/stores/product.store";
const props = defineProps({
    product: {
    type: Object,
    required: true,
    },
});

const productsStore = useProductStore();
</script>

<style scoped>
.v-card {
    color: white;
    border-radius: 12px;
}

.v-img {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    margin: 0.5rem;
    position: relative;
    overflow: hidden;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transition: background-color 0.3s ease;
}

.hover-button {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.v-img:hover {
    filter: brightness(0.8);
    transition: all 0.25s;
}

.v-img:hover .hover-button {
    opacity: 1;
}

.v-card-actions {
    padding-bottom: 16px;
}
</style>
