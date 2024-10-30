<template>
    <v-container>
        <v-row>
            <v-col cols="12">
                <div class="text-h4">
                    La tua Lista
                </div>
            </v-col>
            <v-col cols="12" sm="6" md="4" lg="3" v-for="(product, i) in productsInList" :key="i">
                <ProductCard
                    :product="product"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import ProductCard from '@/Components/Products/ProductCard.vue';
import { ref } from 'vue';

const productsInList = ref([]);

function getWishList(){
    axios.get('/api/user-wishlist')
        .then((res) => {
            productsInList.value = res.data;
        }).catch((e) =>{
            console.error(e)
        })
}

getWishList();
</script>
