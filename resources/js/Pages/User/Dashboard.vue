<script setup>
import { Head, Link, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';


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
        icon: "mdi mdi-account-outline",
        tab: "details",
        title: 'Dettagli',
    },
    {
        icon: "mdi mdi-lock-outline",
        tab: "change-password",
        title: 'Cambia Password',
    },
    {
        icon: "mdi mdi-home-outline",
        tab: "addresses",
        title: 'I miei indirizzi',
    },
    {
        icon: "mdi mdi-help-circle-outline",
        tab: "help",
        title: 'Serve aiuto?',
    },
    {
        icon: "mdi mdi-heart-outline",
        tab: "wishlist",
        title: 'WishList',
    },
]);

const headers = [
    { title: "Ordine", value: "order_number", align: "start" },
    { title: "Data", value: "order_date" },
    { title: "Spedito in", value: "shipped_in" },
    { title: "Pagamento", value: "payment" },
    { title: "Stato Ordine", value: "status" },
    { title: "Spedizione", value: "shipping_number", sortable: false },
    { title: "Dettagli", value: "details", sortable: false },
    { title: "Totale", value: "total_amount" },
    { title: "Fattura", value: "fattura", sortable: false },
];

const items = ref([]);
const loading = ref(false);
const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    sortDesc: [],
    groupBy: [],
    groupDesc: [],
    search: '',
});

const totalItems = ref(0);

function fetchOrders() {
    loading.value = true;

    // API request for server-side data
    axios
        .get('/api/user-orders', options.value)
        .then((response) => {
            const { data, total } = response.data;
            items.value = data;
            console.log(items.value)
            totalItems.value = total;
        })
        .finally(() => {
            loading.value = false;
        });
}

// Watch options to refetch data when changes occur
watch(options, fetchOrders, { deep: true });

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
                        <div class="py-5 px-4">
                            <h1 class="text-h4 mb-4">I miei ordini</h1>
                            <v-data-table-server
                                :expand-on-click="false"
                                :items-per-page="options.itemsPerPage"
                                :headers="headers"
                                :items="items"
                                :items-length="totalItems"
                                :loading="loading"
                                loading-text="Caricamento in corso..."
                                @update:options="fetchOrders"
                            >
                                <template #item.payment="{ item }">
                                    <v-chip color="success" text="white">{{ item.payment }}</v-chip>
                                </template>
                                <template #item.status="{ item }">
                                    <v-chip v-if="item.status === 'confirmed'" color="success"  text="white">{{ item.status }}</v-chip>
                                    <v-chip v-else-if="item.status === 'delivered'" color="warning" text="white">{{ item.status }}</v-chip>
                                    <v-chip v-else-if="item.status === 'on_delivery'" color="warning" text="white">{{ item.status }}</v-chip>
                                    <v-chip v-else-if="item.status === 'in_progress'" color="info" text="white">{{ item.status }}</v-chip>
                                    <v-chip v-else-if="item.status === 'returned'" color="danger" text="white">{{ item.status }}</v-chip>
                                </template>
                                <template #item.tracking="{ item }">
                                    <Link href="/" class="text-decoration-none">{{ item.tracking }}</Link>
                                </template>
                                <template #item.details="{ item }">
                                    <Link  href="/" class="text-decoration-none">{{ item.details }}</Link>
                                </template>
                                <template #item.total_amount="{ item }">
                                    {{ $formatPrice(item.total_amount) }}
                                </template>
                                <template #item.fattura="{ item }">
                                    <v-btn icon v-if="item.fattura" size="small">
                                        <v-icon icon="mdi mdi-download"></v-icon>
                                    </v-btn>
                                </template>
                            </v-data-table-server>
                        </div>
                    </v-tabs-window-item>
                    <!-- Address Table -->
                    <v-tabs-window-item value="addresses">
                        <div class="py-5 px-4">
                            <div class="d-flex align-center justify-space-between">
                                <h1 class="text-h4 mb-4">Indirizzi</h1>
                                <v-btn icon="mdi mdi-plus" color="primary" class="mb-2"></v-btn>
                            </div>
                            <v-row>
                                <v-col cols="4" >
                                    <v-card rounded="xl" class="py-4 px-4" border="0" elevation="0">
                                        <v-form>
                                            qua andranno gli indirizzi e ne caso aggiungerli o modificarli
                                        </v-form>
                                    </v-card>
                                </v-col>
                            </v-row>
                        </div>
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
