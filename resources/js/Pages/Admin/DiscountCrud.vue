<template>
    <Head title="Modifica Sconto" />

    <v-container>
        <v-card elevation="0" rounded="xl">
            <v-card-title>
                Modifica Sconto - {{ discount.name }}
            </v-card-title>
            <v-card-text>
                <!-- Nome e valore dello sconto -->
                <v-text-field
                    label="Nome dello sconto"
                    v-model="discount.name"
                    outlined
                />
                <v-text-field
                    label="Valore dello sconto"
                    v-model="discount.discount_value"
                    outlined
                    type="number"
                />

                <!-- Selezione del tipo di sconto -->
                <v-select
                    label="Tipo di sconto"
                    :items="['percentage', 'fixed']"
                    v-model="discount.discount_type"
                    outlined
                />

                <!-- Selezione delle categorie -->
                <v-select
                    label="Categorie"
                    v-model="selectedCategories"
                    :items="categories"
                    item-title="name"
                    item-value="id"
                    multiple
                    chips
                    outlined
                />

                <!-- Selezione dei prodotti -->
                <v-select
                    v-model="selectedProducts"
                    :items="products"
                    label="Prodotti"
                    item-title="name"
                    item-value="id"
                    chips
                    multiple
                ></v-select>
            </v-card-text>
            <v-card-actions class="justify-end">
                <v-btn color="primary" @click="updateDiscount()">Salva</v-btn>
            </v-card-actions>
        </v-card>
    </v-container>
</template>

<script setup>
import { usePage, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notification.store';

// Props da Inertia
const { props } = usePage();
const discount = ref({ ...props.discount });
const notificationStore = useNotificationStore();
// Stati locali per categorie e prodotti
const categories = ref([]);
const products = ref([]);
const selectedCategories = ref(discount.value.categories.map(cat => cat.id));
const selectedProducts = ref(discount.value.products.map(prod => prod.id));

// Caricamento iniziale delle categorie e dei prodotti

function fetchCategories() {
    axios.get('/api/all-categories')
        .then(response => {
            categories.value = response.data;
        })
        .catch(error => {
            console.error('Errore nel caricamento delle categorie:', error);
        });
}
function fetchProducts() {
    axios.get('/api/all-products')
        .then(response => {
            // console.log(response.data);
            products.value = response.data;
        })
        .catch(error => {
            console.error('Errore nel caricamento dei prodotti:', error);
        });
}
// Salvataggio dello sconto
const updateDiscount = () => {
    axios.patch(`/api/discount/${discount.value.id}`, {
        ...discount.value,
        categories: selectedCategories.value,
        products: selectedProducts.value
    })
        .then(response => {
            notificationStore.notify(response.data.message, response.data.color);
            // Inserire un messaggio di successo
        })
        .catch(error => {
            console.error('Errore nel salvataggio dello sconto:', error);
            notificationStore.notify(error.response.message, 'error');
            // Inserire un messaggio di errore
        });
};
// ti manca da gestire l'eliminazione delle categorie e dei prodotti
fetchCategories();
fetchProducts();
</script>
