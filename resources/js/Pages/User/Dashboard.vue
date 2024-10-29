<script setup>
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const links = ref([
    {
        icon: "mdi mdi-order-alphabetical-ascending",
        link: "",
        title: 'Ordini'
    },
    {
        icon: "mdi mdi-order-alphabetical-ascending",
        link: "",
        title: 'Indirizzi'
    },
    {
        icon: "mdi mdi-list-box-outline",
        link: "",
        title: 'Lista'
    },
    {
        icon: "mdi mdi-face-man-profile",
        link: "",
        title: 'Profilo'
    },
])

function getOrders(){
    axios.get('/api/user-orders')
        .then((res) => {
            console.log(res.data)
        }).catch((e) =>{
            console.error(e)
        })
}

getOrders();
</script>

<template>
    <Head title="Dashboard" />

    <v-container class="mt-5" fluid >
        <v-row >
            <!-- Card Ordini -->
            <v-col cols="6" v-for="(card, i) in links" :key="i">
                <v-card class="d-flex align-center justify-center" rounded="xl" height="150"  color="primary" >
                    <v-card-text class="d-flex align-center justify-center">
                        <v-icon :icon="card.icon" class="mr-2"></v-icon>
                        <strong class="text-h6">{{ card.title }}</strong>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
