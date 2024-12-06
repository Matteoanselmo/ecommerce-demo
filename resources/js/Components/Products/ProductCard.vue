<template>
    <v-lazy
    :min-height="200"
    :options="{'threshold':0.5}"
    transition="fade-transition"
    data-aos="fade-up"
    data-aos-duration="800"
    >
        <template v-if="productsStore.loading">
            <v-skeleton-loader
                class="mx-auto my-4"
                max-width="344"
                type="card"
                height="340px"
            />
        </template>
        <template v-else>
            <v-card
            class="mx-auto my-4 px-2 py-2"
            max-width="344"
            elevation="0"
            color="background"
            rounded="xl"

            >
                <v-img
                    :src="product.cover_image_url"
                    alt="Product Image"
                    :height="props.expanded ? '400px' : '200px'"
                    class="rounded-lg p-2 position-relative"
                    rounded="xl"
                    cover
                >
                    <div class="promo-label" v-if="props.expanded && product.original_price > product.price ">
                        promo
                    </div>
                    <div class="overlay d-flex align-center justify-center h-100">
                    <v-btn
                        class="ma-0 hover-button"
                        color="black"
                        dark
                        style="border-radius: 8px;"
                        text="Maggiori informazioni"
                        @click="productsStore.navigateToProduct(props.product.id)"
                    >
                    </v-btn>
                    </div>
                </v-img>

                <v-card-subtitle color="black" class="text-h6 mt-3 mb-1" >
                    {{ product.name }}
                </v-card-subtitle>
                <v-card-text class="grey--text text--darken-2 mb-2" :html="product.description" v-if="props.expanded">
                </v-card-text>

                <v-card-actions class="justify-between" color="primary" v-if="props.expanded">
                    <v-spacer></v-spacer>
                    <!-- <span class="text-h6 font-weight-bold text-primary" >{{ $formatPrice(product.price) }}</span> -->
                    <p class="text-h6 " v-if="product.original_price > product.price">
                        <span color="danger" class="font-weight-bold text-primary" >{{ $formatPrice(product.price) }} - </span>
                        <span class="text-decoration-line-through text-black">{{ $formatPrice(product.original_price) }}</span>
                    </p>
                    <p class="text-h6 " v-else>
                        <span color="danger" class="font-weight-bold text-primary" >{{ $formatPrice(product.price) }}</span>
                    </p>
                    </v-card-actions>
                <v-card-actions class="justify-center" color="primary" width="100%" v-if="props.expanded">
                    <RatingStars
                        :ratings="product.rating_star"
                    />
                </v-card-actions>
            </v-card>
        </template>
    </v-lazy>
</template>

<script setup>
import { useProductStore } from "@/stores/product.store";
import { useCartStore } from "@/stores/cartStore";
import RatingStars from "../Reviews/RatingStars.vue";
import { router } from '@inertiajs/vue3';

const props = defineProps({
    product: {
        type: Object,
        required: true,
    },
    expanded: {
        type: Boolean,
        default: true
    }
});

const cartStore = useCartStore();

const productsStore = useProductStore();


</script>

<style scoped>
/* .v-card {
    color: white;
    border-radius: 12px;
} */

.v-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.v-card:hover {
    transform: scale(1.05);
    box-shadow: 0px 8px 20px rgba(0, 0, 0, 0.2);
}

.v-img {
    border-top-left-radius: 12px;
    border-top-right-radius: 12px;
    margin: 0.5rem;
    position: relative;
    overflow: hidden;
    transition: transform 0.25s ease, filter 0.25s ease;
}

.v-img:hover {
    filter: brightness(0.8);
    transform: scale(1.05);
}

.hover-button {
    opacity: 0;
    transition: opacity 0.3s ease;
}

.v-img:hover .hover-button {
    opacity: 1;
}

.overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    transition: background-color 0.3s ease;
}

.v-card-actions {
    padding-bottom: 16px;
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
