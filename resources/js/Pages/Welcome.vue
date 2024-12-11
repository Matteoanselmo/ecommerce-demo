<script setup>
import { Head } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Jumbotron from '@/Components/Home/Jumbotron.vue';
import DiscountBanner from '@/Components/DiscountBanner.vue';
import CategoryCards from '@/Components/Products/CategoryCards.vue';
import ProductPromoCarousel from '@/Components/Products/ProductPromoCarousel.vue';
import SecurityBanner from '@/Components/SecurityBanner.vue';
import { ref } from 'vue';

const promoProducts = ref([]);

function getPromoProducts() {
    axios.get('/api/promo-products')
    .then((res) => {
    promoProducts.value = res.data; // Adatta i dati con i gruppi di sconto e prodotti
    })
    .catch((e) => {
        console.error(e);
    });
}


defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
});

getPromoProducts();
</script>

<template>
    <Head title="Home" />
    <Banner data-aos="fade-down"/>
    <search-bar></search-bar>
    <DiscountBanner/>
    <div class="text-h2 my-5 text-center">
        Inizia da qui!
    </div>
    <Jumbotron/>
    <CategoryCards class="mb-5"/>
    <div v-for="(promo, i) in promoProducts" :key="i">
        <ProductPromoCarousel
            :title="promo.discount_name"
            :promoProducts="promo.products"
        />
    </div>
    <SecurityBanner/>
</template>

<style>
.bg-dots-darker {
    background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E");
}
@media (prefers-color-scheme: dark) {
    .dark\:bg-dots-lighter {
        background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E");
    }
}
</style>
