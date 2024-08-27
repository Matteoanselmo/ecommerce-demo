<template>
    <Head :title="product.name" />
    <!-- The rest of your component -->
    <v-container class="product-detail-container" >
    <v-row>
        <v-col cols="12" md="6">
            <v-carousel>
            <v-carousel-item
                v-for="(image, i) in product.images"
                :key="i"
                :src="image.image_url"
                cover
                >
                <v-overlay
                    v-model="overlays[i]"
                    absolute
                    color="rgba(0, 0, 0, 0.7)"
                    @click="enlargeImage(image.image_url)"
                >
                    <v-btn icon>
                    <v-icon>mdi-magnify</v-icon>
                    </v-btn>
                </v-overlay>
                </v-carousel-item>
            </v-carousel>
        </v-col>

        <v-col cols="12" md="6" class="d-flex flex-column justify-start align-start">
        <div>
            <!-- <v-rating
            v-model="calculatedRating"
            length="5"
            half-increments
            readonly
            ></v-rating> -->
            <RatingStars
                    :ratings="product.rating_star"
                />
            <h1 class="text-h4 font-weight-bold mb-2">{{ product.name }}</h1>
            <p class="text-body-1 mb-4">{{ product.description }}</p>
            <span class="text-h6 font-weight-bold text-primary" >${{ product.price }}</span>
        </div>

        <div class="mt-4">
            <h2 class="text-h6 font-weight-bold mb-2">Highlights</h2>
            <ul>
            <li v-for="highlight in product.highlights" :key="highlight">
                {{ highlight }}
            </li>
            </ul>
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
const calculatedRating = ref(product.value.rating_star); // Use product.value because it's a computed property

const overlays = ref(new Array(props.product.images.length).fill(false));
const dialog = ref(false);
const enlargedImage = ref('');

function enlargeImage(imageUrl) {
    enlargedImage.value = imageUrl;
    dialog.value = true;
}
function handlePreview() {
    // Handle preview logic here
}

console.log(product)
</script>

<style scoped>

ul {
    list-style: disc inside;
}
</style>
