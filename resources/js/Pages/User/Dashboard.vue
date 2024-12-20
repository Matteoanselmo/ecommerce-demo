<script setup>
import { Head, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
import OrdersTable from '@/Components/User/OrdersTable.vue';
import Address from '@/Components/User/Address.vue';
import BillingAddresses from '@/Components/User/BillingAddresses.vue';
import WishList from '@/Components/User/WishList.vue';
import TicketManager from '@/Components/User/TicketManager.vue';

const page = usePage()
const user = ref(page.props.auth.user);
const tab = ref("");
const sidebarLinks = ref([
    {
        icon: "mdi mdi-cart-outline",
        tab: "dashboard",
        title: 'I miei ordini',
    },
    {
        icon: "mdi mdi-home-outline",
        tab: "addresses",
        title: 'I miei indirizzi',
    },
    {
        icon: "mdi mdi-heart-outline",
        tab: "wishlist",
        title: 'WishList',
    },
    {
        icon: "mdi mdi-help-circle-outline",
        tab: "ticket",
        title: 'Serve aiuto?',
    },
]);

function getInitials(name) {
    if (!name) return '';
    return name
        .split(' ') // Divide il nome completo in parole
        .map(word => word.charAt(0)) // Prende la prima lettera di ogni parola
        .join('') // Unisce le lettere in una stringa
        .toUpperCase(); // Trasforma tutto in maiuscolo
}

</script>

<template>
    <Head title="I miei ordini" />

    <v-container fluid>
        <v-row>
            <!-- Sidebar -->
            <v-col cols="12" md="3" >
                <div class="py-5 px-4" >
                    <v-avatar size="56" color="primary" class="mb-3">{{ getInitials( user.name) }}</v-avatar>
                    <div class="text-h6">{{  user.name }}</div>
                </div>
                <v-divider></v-divider>
                <v-tabs v-model="tab" align-tabs="center" direction="vertical" density="comfortable" >
                    <v-tab class="ms-0" v-for="(link, i) in sidebarLinks" :key="i" :value="link.tab" :prepend-icon="link.icon" >{{ link.title }}</v-tab>
                </v-tabs>
            </v-col>

            <!-- Main Content -->
            <v-col cols="12" md="9">
                <v-tabs-window v-model="tab">

                    <!-- Orders Table -->
                    <v-tabs-window-item value="dashboard">
                        <OrdersTable/>
                    </v-tabs-window-item>
                    <!-- Address Table -->
                    <v-tabs-window-item value="addresses">
                        <Address/>
                        <BillingAddresses/>
                    </v-tabs-window-item>
                    <v-tabs-window-item value="wishlist">
                        <WishList/>
                    </v-tabs-window-item>
                    <v-tabs-window-item value="ticket">
                        <TicketManager/>
                    </v-tabs-window-item>
                </v-tabs-window>
            </v-col>
        </v-row>
    </v-container>
</template>

<style scoped>
.bg-light {
    background-color: #f8f9fa;
}
.text-decoration-none {
    text-decoration: none;
}
</style>
