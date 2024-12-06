<template>
    <Head title="Carrello" />
    <v-container class="py-5">
    <!-- Lista degli articoli nel carrello -->
    <v-row>
        <v-col cols="12" md="8">
        <v-card class="pa-4 mb-4" v-for="(product, i) in cartProducts" :key="i" rounded="xl">
            <v-row>
            <v-col cols="12" md="3">
                <v-img :src="product.cover_image_url" height="100" contain></v-img>
            </v-col>
            <v-col cols="12" md="6">
                <v-card :title="product.name" elevation="0" >
                    <v-card-subtitle v-html="product.description" :opacity="0.5" style="max-height: 100px;">
                    </v-card-subtitle>
                </v-card>
            </v-col>
            <v-col cols="12" md="3" class="d-flex align-end flex-column">
                <v-card height="100%" elevation="0" :title="'(' + product.sizeName + ') '+ $formatPrice(product.totalPrice)" :subtitle="'X '+ product.quantity ">
                    <v-card-actions class="justify-center">
                        <v-btn
                        size="xl"
                        append-icon="mdi mdi-delete"
                        color="danger"
                        @click="cartStore.removeItem(product)"
                        >
                        </v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
            </v-row>
        </v-card>
        </v-col>

        <!-- Riepilogo del carrello -->
        <v-col cols="12" md="4">
            <v-card  class="px-4 mb-5" rounded="xl">
                <div class="d-flex align-center justify-space-between py-4 border-b-md">
                    <p>Subtotale</p>
                    <p>{{ $formatPrice(subtotal) }}</p>
                </div>
                <div class="d-flex align-center justify-space-between py-4 border-b-md">
                    <p>Spedizione</p>
                    <p>{{ $formatPrice(shipping) }}</p>
                </div>
                <div class="d-flex align-center justify-space-between py-4 border-b-md">
                    <p>Tasse</p>
                    <p>{{ $formatPrice(tax) }}</p>
                </div>
                <div class="d-flex align-center justify-space-between py-4 ">
                    <p>Totale Ordine</p>
                    <p>{{ $formatPrice(orderTotal) }}</p>
                </div>
            </v-card>
        <v-card class="pa-4" rounded="xl">
            <v-btn color="primary" rounded="xl" block class="my-4" @click="checkIfAuthenticated()">Checkout</v-btn>
            <CheckAuthDialog v-model="loginPrompt" :text="'Per completare l\'ordine devi effettuare il Login'"/>
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
import { computed, ref } from 'vue';
import { useCartStore } from '@/stores/cartStore';
import { Link, Head, usePage, router } from '@inertiajs/vue3';
import CheckAuthDialog from '@/Components/CheckAuthDialog.vue';

const page = usePage();

const isAuthenticated = computed(() => !!page.props.auth.user);
const cartStore = useCartStore();
const loginPrompt = ref(false);
// Estrarre i dati dallo store
const cartProducts = cartStore.items;

// Calcolo del riepilogo
const subtotal = computed(() => cartStore.totalAmount);
const shipping = 500; // Importo fisso o calcolato separatamente
const tax = computed(() => (subtotal.value + shipping) * 0.08); // 8% di imposta ipotetica
const orderTotal = computed(() => subtotal.value + shipping + tax.value);

function checkIfAuthenticated(){
    if(!isAuthenticated.value){
        loginPrompt.value = true;
    } else{
        loginPrompt.value = false;
        router.get('checkout');
    }
}

console.log(isAuthenticated)
</script>

<style scoped>
.text-gray {
    color: gray;
}
</style>
