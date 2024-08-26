<template>
    <Head title="Carrello" />
    <v-container class="py-5">
    <!-- Lista degli articoli nel carrello -->
    <v-row>
        <v-col cols="12" md="8">
        <v-card class="pa-4 mb-4" v-for="(product, i) in cartProducts" :key="i">
            <v-row>
            <v-col cols="12" md="3">
                <v-img :src="product.cover_image_url" height="100" contain></v-img>
            </v-col>
            <v-col cols="12" md="6">
                <div class="text-h6">{{ product.name }}</div>
                <div class="text-subtitle-2 text-gray">{{ product.description }}</div>
                <v-btn
                text
                color="primary"
                @click="cartStore.removeItem(product.id)"
                >
                Remove
                </v-btn>
            </v-col>
            <v-col cols="12" md="3" class="d-flex align-end flex-column">
                <v-select
                v-model="product.quantity"
                :items="[1, 2, 3, 4, 5, 10]"
                class="mb-3"
                outlined
                dense
                hide-details
                @change="updateQuantity(product.id, product.quantity)"
                ></v-select>
                <div class="text-h6">{{ currencyFormat(product.totalPrice) }}</div>
            </v-col>
            </v-row>
        </v-card>
        </v-col>

        <!-- Riepilogo del carrello -->
        <v-col cols="12" md="4">
        <v-card class="pa-4">
            <v-list-item>
            <v-list-item-content>
                <v-list-item-title>Subtotal</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content class="text-right">
                <div>{{ currencyFormat(subtotal) }}</div>
            </v-list-item-content>
            </v-list-item>

            <v-list-item>
            <v-list-item-content>
                <v-list-item-title>Shipping estimate</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content class="text-right">
                <div>{{ currencyFormat(shipping) }}</div>
            </v-list-item-content>
            </v-list-item>

            <v-list-item>
            <v-list-item-content>
                <v-list-item-title>Tax estimate</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content class="text-right">
                <div>{{ currencyFormat(tax) }}</div>
            </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

            <v-list-item>
            <v-list-item-content>
                <v-list-item-title class="font-weight-bold">Order Total</v-list-item-title>
            </v-list-item-content>
            <v-list-item-content class="text-right">
                <div class="text-h6">{{ currencyFormat(orderTotal) }}</div>
            </v-list-item-content>
            </v-list-item>

            <v-btn color="primary" block class="my-4">Checkout</v-btn>
            <v-btn clas block class="my-4" variant="plain">
                <Link
                text="o Continua lo Shopping"
                class="text-center w-100"
                :href="route('home')"
                width="100%"
                >
                </Link>
            </v-btn>
        </v-card>
        </v-col>
    </v-row>
    </v-container>
</template>

<script setup>
import { computed } from 'vue';
import { useCartStore } from '@/stores/cartStore';
import { Link, Head } from '@inertiajs/vue3';

const cartStore = useCartStore();

// Estrarre i dati dallo store
const cartProducts = cartStore.items;

// Calcolo del riepilogo
const subtotal = computed(() => cartStore.totalAmount);
const shipping = 5.00; // Importo fisso o calcolato separatamente
const tax = computed(() => (subtotal.value + shipping) * 0.08); // 8% di imposta ipotetica
const orderTotal = computed(() => subtotal.value + shipping + tax.value);

// Funzione per formattare il prezzo
const currencyFormat = (value) => {
    return `€ ${value.toFixed(2)}`;
};

// Funzione per aggiornare la quantità del prodotto
const updateQuantity = (id, quantity) => {
    cartStore.incrementQuantity(id);
};
</script>

<style scoped>
.text-gray {
    color: gray;
}
</style>
