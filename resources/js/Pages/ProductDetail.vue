<template>
    <Head :title="product.data.name" />
    <search-bar></search-bar>
    <v-container class="product-detail-container" >
    <v-row>
        <v-col cols="12" md="6">
            <v-carousel show-arrows="hover" hide-delimiters>
                <v-carousel-item
                    rounded="lg"
                    v-for="(image, i) in product.data.images"
                    :key="i"
                    :src="image.image_url"
                    contain
                    >
                </v-carousel-item>
            </v-carousel>
        </v-col>

        <v-col cols="12" md="6" class="d-flex flex-column justify-start align-start">
        <div>
                <RatingStars
                    :ratings="product.data.rating_star"
                />
            <h1 class="text-h4 font-weight-bold mb-2">{{ product.data.name }}</h1>
            <p class="text-body-1 mb-4" v-html="product.data.description"> </p>
            <p class="text-h6 " v-if="product.data.original_price > product.data.price">
                <span class="text-decoration-line-through">{{ $formatPrice(product.data.original_price) }}</span>
                <v-spacer></v-spacer>
                <span color="danger" class="font-weight-bold text-primary" >{{ $formatPrice(product.data.price) }}</span>
            </p>
            <p class="text-h6 " v-else>
                <span color="danger" class="font-weight-bold text-primary" >{{ $formatPrice(product.data.price) }}</span>
            </p>
            <v-btn width="100%" variant="tonal" text="aggiungi al carrello" color="secondary" @click="cartStore.addItem(product)">
            </v-btn>
        </div>
        </v-col>
    </v-row>

    <v-divider class="my-6"></v-divider>
    <v-card>
        <v-tabs
        v-model="tab"
        bg-color="primary"
        >
        <v-tab value="one">Recensioni</v-tab>
        <v-tab value="two">FAQ</v-tab>
        <v-tab value="three">License</v-tab>
        </v-tabs>

        <v-card-text>
        <v-tabs-window v-model="tab">
            <v-tabs-window-item value="one">
            One
            </v-tabs-window-item>

            <v-tabs-window-item value="two">
            Two
            </v-tabs-window-item>

            <v-tabs-window-item value="three">
            Three
            </v-tabs-window-item>
        </v-tabs-window>
        </v-card-text>
    </v-card>
    </v-container>
</template>

<script setup>
import { ref, computed } from "vue";
import { useCartStore } from "@/stores/cartStore";
import RatingStars from "@/Components/Reviews/RatingStars.vue";
import { Head } from '@inertiajs/vue3';
// Define props
const props = defineProps({
    product: {
    type: Object,
    required: true,
    },
});

// Access the product prop
const product = computed(() => props.product);

const cartStore = useCartStore();
const tab = ref(0);

function handlePreview() {
    // Handle preview logic here
}

console.log(product.value.data)
</script>

<style scoped>

ul {
    list-style: disc inside;
}
</style>
