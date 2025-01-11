<template>
    <div class="py-5 px-4">
        <div class="d-flex align-center justify-space-between">
            <div class="text-h4 mb-4">Wish List</div>
        </div>

        <!-- Lista dei prodotti -->
        <v-container>
            <v-row v-if="wishProducts.length" dense>
                <v-col
                    v-for="(product, index) in wishProducts"
                    :key="index"
                    cols="12"
                >
                    <v-card class="d-flex flex-row align-center mb-2" rounded="xl" elevation="0">
                        <!-- Immagine del prodotto -->
                        <v-img
                            :src="product.cover_image"
                            alt="Product image"
                            aspect-ratio="1"
                            max-width="100"
                        ></v-img>

                        <!-- Dettagli del prodotto -->
                        <v-card-text class="flex-grow-1">
                            <Link
                            :href="route('product.detail', { id: product.id })"
                            class="text-h6"
                            >
                                {{ product.name }}
                            </Link>
                        </v-card-text>

                        <!-- Prezzo e azioni -->
                        <v-card-actions>
                            <div class="text-h6  me-4">
                                € {{ $formatPrice(product.price) }}
                            </div>
                            <WishlistHeart
                                :productId="product.id"
                            />
                        </v-card-actions>
                    </v-card>
                </v-col>
            </v-row>

            <!-- Nessun prodotto -->
            <div v-else class="text-center text-h6 mt-5">
                La tua wishlist è vuota.
            </div>
        </v-container>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import WishlistHeart from '../Products/WishlistHeart.vue';
const wishProducts = ref([]);

function getWishList() {
    axios.get('/api/user-wishlist')
        .then((res) => {
            wishProducts.value = res.data;
        })
        .catch((e) => {
            console.error(e);
        });
}


function removeFromWishList(productId) {
    console.log(`Removing product ${productId} from wishlist`);
    // Implementa la logica per rimuovere il prodotto dalla wishlist
    wishProducts.value = wishProducts.value.filter(p => p.id !== productId);
}

// Ottieni i prodotti nella wishlist all'inizializzazione
getWishList();
</script>

<style scoped>
.text-orange-600 {
    color: #ff5722; /* Colore arancione personalizzato */
}
</style>
