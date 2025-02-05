<template>
    <Head :title="product.data.name" />
    <search-bar></search-bar>
    <v-container class="product-detail-container" >
    <v-row>
        <v-col cols="12" md="6">
            <v-carousel show-arrows="hover"
            >
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

            <div class="text-h4 font-weight-bold mb-2">
                {{ product.data.name }}
                <wish-list-heart :productId="product.data.id"></wish-list-heart>
            </div>
            <RatingStars
                :ratings="product.data.rating_star"
                :readOnly="true"
            />

            <v-card title="Descrizone Prodotto" rounded="xl" class="mb-4">
                <v-card-text v-html="product.data.description">

                </v-card-text>
            </v-card>
            <v-card title="Certificazioni:" rounded="xl" class="mb-4">
                <v-card-text>
                    <v-list class="py-0 my-2" lines="one" item-title="Certificazioni" variant="plain" rounded="xl" >
                        <v-list-item
                            v-for="certification in product.data.certifications"
                            :key="certification.id"
                            :subtitle="certification.name"
                        ></v-list-item>
                    </v-list>
                </v-card-text>
            </v-card>


            <v-chip-group v-model="product.data.productSizes" column>
                <v-chip
                    v-for="size in product.data.sizes"
                    :key="size.id"
                    :value="size.id"
                    mandatory
                    class="d-flex align-center mb-2"
                    size="x-large"
                    :prepend-icon="size.id === product.data.productSizes ? 'mdi-check' : ''"
                >
                    <div class="d-flex align-center">
                        <p class="mb-0 mr-4">
                            {{ size.name }} ({{ size.stock }} pezzi)
                        </p>
                        <v-number-input
                            v-model="size.quantity"
                            control-variant="split"
                            :max="size.stock"
                            :min="0"
                            class="ml-4"
                            variant="plain"
                            :disabled="size.id !== product.data.productSizes"
                        ></v-number-input>
                    </div>
                </v-chip>
            </v-chip-group>


            <p class="text-h6 " v-if="product.data.original_price > product.data.price">
                <span class="text-decoration-line-through">{{ $formatPrice(product.data.original_price) }}</span>
                <v-spacer></v-spacer>
                <span color="danger" class="font-weight-bold text-primary" >{{ $formatPrice(product.data.price) }}</span>
            </p>

            <p class="text-h6 " v-else>
                <span color="danger" class="font-weight-bold text-primary" >{{ $formatPrice(product.data.price) }}</span>
            </p>
                <v-btn
                    width="100%"
                    variant="tonal"
                    :color="cartAnimation ? 'success' : 'secondary '"
                    @click="cartStore.addItem(product.data),addToCartAnimation()"
                    :disabled="!canAddToCart"
                    :class="{'btn-add-to-cart': cartAnimation}"
                >
                <template v-if="cartAnimation">
                    <v-icon class="cart-icon text-white">mdi-cart</v-icon>
                    <span class="added-text  text-white">Aggiunto!</span>
                </template>
                <template v-else>
                    <span class="button-text">Aggiungi al Carrello</span>
                </template>
            </v-btn>
        </div>
        </v-col>
    </v-row>

    <v-divider class="my-6"></v-divider>
    <v-card rounded="xl">
        <v-tabs
        v-model="tab"
        bg-color="primary"

        >
        <v-tab value="one">Recensioni</v-tab>
        <v-tab value="two">FAQ</v-tab>
        <v-tab value="three">Scheda Tecnica / PDF</v-tab>
        </v-tabs>

        <v-card-text>
        <v-tabs-window v-model="tab">
            <!-- RECENSIONI -->
            <v-tabs-window-item value="one">
                <v-container>
                    <v-row>
                        <v-col cols="12" v-if="props.auth.user">
                            <RatingStars
                                :ratings="['0']"
                                :size="30"
                            />
                            <v-textarea label="Scrivi una recensione" variant="solo-filled"></v-textarea>
                        </v-col>
                        <v-col v-for="(review, i) in product.data.reviews" :key="i" cols="12" >
                            <v-card class="d-flex flex-row align-center px-2" variant="tonal" >
                                <!-- Icona dell'avatar dell'utente -->
                                <v-avatar color="primary" size="48" class="mr-4">
                                    <v-icon>mdi-account</v-icon>
                                </v-avatar>

                                <!-- Dettagli della recensione -->
                                <v-card-text>
                                    <div class="font-weight-medium">
                                        {{ review.user_name }}
                                        <RatingStars
                                            :ratings="review.rating_star"
                                            :size="30"
                                            :readOnly="true"
                                        />
                                    </div>

                                    <div class="text--secondary" v-if="review.description">{{ review.description }}</div>
                                </v-card-text>
                            </v-card>
                        </v-col>
                    </v-row>
                </v-container>
            </v-tabs-window-item>

            <v-tabs-window-item value="two">
                <v-expansion-panels v-for="(faq, i ) in product.data.faqs" :key="i">
                    <v-expansion-panel
                        :title="faq.question"
                        :text="faq.answer"
                    >
                    </v-expansion-panel>
                </v-expansion-panels>
            </v-tabs-window-item>

            <v-tabs-window-item value="three">
                <div  class="d-flex mb-3" width="100%">
                    <v-card
                        rounded="xl"
                        elevation="0"
                        border="md"
                        width="150"
                        prepend-icon="mdi mdi-file-pdf-box"
                        v-for="(datasheet, index) in props.product.data.datasheets"
                        :key="index"
                        :subtitle="datasheet.name"
                        class="mr-2"
                    >
                    <v-card-actions>
                        <v-btn color="info" icon="mdi mdi-download" :href="datasheet.path"  target="blank" download></v-btn>
                    </v-card-actions>
                    </v-card>
                </div>
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
import { Head, usePage } from '@inertiajs/vue3';

const page = usePage();
// Define props
const props = page.props;
// Access the product prop
const product = computed(() => props.product);

const cartAnimation = ref(false);

const canAddToCart = computed(() => {
    return product.value.data.sizes.some(size => size.quantity > 0);
});

function addToCartAnimation(){
    cartAnimation.value = !cartAnimation.value;
    setTimeout(() => {
        cartAnimation.value = false; // Disattiva l'animazione dopo 1 secondo
    }, 1000);
}

const cartStore = useCartStore();
const tab = ref(0);

</script>

<style scoped>

ul {
    list-style: disc inside;
}


</style>
