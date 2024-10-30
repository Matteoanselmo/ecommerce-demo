<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProductCard from '@/Components/Products/ProductCard.vue';

const links = ref([
    {
        icon: "mdi mdi-order-alphabetical-ascending",
        link: "user.dashboard",
        title: 'Ordini'
    },
    {
        icon: "mdi mdi-order-alphabetical-ascending",
        link: "user.dashboard",
        title: 'Indirizzi'
    },
    {
        icon: "mdi mdi-list-box-outline",
        link: "user.wishlist",
        title: 'Lista'
    },
    {
        icon: "mdi mdi-face-man-profile",
        link: "user.dashboard",
        title: 'Profilo'
    },
])

const recentWishList = ref([]);

function getRecentWishList(){
    axios.get('/api/user-recent-wishlist')
        .then((res) => {
            recentWishList.value = res.data;
        }).catch((e) =>{
            console.error(e)
        })
}

getRecentWishList();
</script>

<template>
    <Head title="Dashboard" />

    <v-container class="mt-5" fluid >
        <v-row class="mb-5" >
            <!-- Card Ordini -->
            <v-col cols="6" v-for="(card, i) in links" :key="i">
                <v-card class="d-flex align-center justify-center" rounded="xl" height="150"  color="primary" >
                    <v-card-text class="d-flex align-center justify-center">
                        <Link as="button" :href="route(card.link)">
                            <v-icon :icon="card.icon" class="mr-2"></v-icon>
                            <strong class="text-h6">{{ card.title }}</strong>
                        </Link>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
        <v-divider></v-divider>
        <v-row>
            <v-col cols="12">
                <div class="text-h4">
                    Continua ad Acquistare
                </div>
            </v-col>
            <v-col cols="6" md="3" v-for="(product, i) in recentWishList" :key="i">
                <ProductCard
                    :product="product"
                    :expanded="false"
                />
            </v-col>
        </v-row>
    </v-container>
</template>
