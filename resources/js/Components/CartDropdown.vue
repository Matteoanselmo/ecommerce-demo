<template>
    <v-menu
    v-model="menu"
    :close-on-content-click="false"
    offset-y
    >
    <template v-slot:activator="{ props }">
        <v-btn v-bind="props" icon>
            <v-badge color="red" :content="itemCount" >
                <v-icon>mdi-cart</v-icon>
            </v-badge>
        </v-btn>
    </template>

    <v-list rounded="xl">
        <v-list-item
        v-for="(product, i) in cartProducts"
        :key="i"
        :subtitle="'€ ' + $formatPrice(product.price)"
        :title="product.quantity + 'x ' + product.name"
        >
        <template v-slot:prepend>
            <v-list-img :src="product.cover_image_url" />
        </template>
        <template v-slot:append>
            <v-btn
            class="text-center p-0"
            append-icon="mdi mdi-delete"
            color="error"
            variant="text"
            block
            @click="cartStore.removeItem(product)"
            ></v-btn>
        </template>
        </v-list-item>

        <v-divider></v-divider>

        <v-list-item title="Totale" :subtitle="'€ ' + $formatPrice(totalAmount)">
        </v-list-item>

        <v-list-item>
        <v-btn color="primary" block>Checkout</v-btn>
        </v-list-item>

        <v-list-item>
            <!-- <v-btn  @click="viewCart">Visualizza il carrello</v-btn> -->

            <Link
            color="secondary"
            block
            text="Visualizza il carrello"
            :href="route('cart')"
            />
        </v-list-item>
    </v-list>
    </v-menu>
</template>

<script setup>
import { ref, watch, computed } from 'vue';
import { useCartStore } from '@/stores/cartStore';
import { Link } from '@inertiajs/vue3';

const menu = ref(false);
const cartStore = useCartStore();

// Accedi direttamente ai dati dallo store Pinia, che sono già reattivi
const cartProducts = computed(() => cartStore.items);
const itemCount = computed(() => cartStore.itemCount);
const totalAmount = computed(() => cartStore.totalAmount);

// Watch su itemCount per rilevare cambiament

const viewCart = () => {
    // Logica per visualizzare il carrello completo
};
</script>

<style scoped>
</style>
