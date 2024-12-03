<template>
    <Head title="Checkout" />
    <v-container>
        <v-row>
            <v-col md="6" cols="12">
                <div class="text-h6 mb-4">
                    Sommario Ordine
                </div>
                <v-card v-for="(product, i) in cartStore.items" :key="i"
                width="90%"
                :title="product.name + ' x' + product.quantity" class="mb-5 py-2" rounded="xl" elevation="0" >
                    <template v-slot:prepend>
                        <v-avatar :image="product.cover_image_url" rounded="0">
                        </v-avatar>
                    </template>
                    <v-card-text v-html="product.description" :opacity="0.5" style="max-height: 100px;">
                    </v-card-text>
                    <v-card-subtitle>
                        <p>({{ product.sizeName }}) <span>{{ $formatPrice(product.totalPrice) }}</span></p>
                    </v-card-subtitle>
                </v-card>
                <v-card  class="px-4 mb-5" rounded="xl" elevation="0" width="90%">
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
            </v-col>
            <v-col md="6" cols="12">
                <div class="text-h6 mb-4">
                    Informazioni Contatto
                </div>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { useCartStore } from '@/stores/cartStore';

const cartStore = useCartStore();

const subtotal = computed(() => cartStore.totalAmount);
const shipping = 500; // Importo fisso o calcolato separatamente
const tax = computed(() => (subtotal.value + shipping) * 0.08); // 8% di imposta ipotetica
const orderTotal = computed(() => subtotal.value + shipping + tax.value);

console.log(cartStore.items)
</script>
