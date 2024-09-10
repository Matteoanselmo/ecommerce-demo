<template>
    <v-data-table-server
    :rounded="true"
    :expand-on-click="false"
    :items-per-page="itemsPerPage"
    :headers="headers"
    :items="items"
    :items-length="totalItems"
    :loading="loading"
    :search="search"
    loading-text="Caricamento in corso..."
    item-value="name"
    @update:options="handleOptionsUpdate"
    @click:row="openModal"
    >
    <template #item.actions="{ item }">
        <v-btn variant="outlined" size="small" color="warning" class="me-3">
        <span class="mdi mdi-file-edit-outline"></span>
        </v-btn>
        <v-btn variant="outlined" size="small" color="danger">
        <span class="mdi mdi-delete-alert-outline"></span>
        </v-btn>
    </template>
    </v-data-table-server>

    <v-dialog v-model="showModal" max-width="600">
    <v-card>
        <v-card-title>Dettagli Ordine</v-card-title>
        <v-card-text>
        <p>Nome: {{ selectedItem.user.name }}</p>
        <p>Numero Ordine: {{ selectedItem.order_number }}</p>
        <p>Data Ordine: {{ selectedItem.order_date }}</p>
        <p>Numero Spedizione: {{ selectedItem.shipping_number }}</p>
        <p>Status: {{ selectedItem.status }}</p>
        <p>Totale Ordine: &euro;{{ selectedItem.total_amount }}</p>
        <!-- Aggiungi altro contenuto -->
        </v-card-text>
        <v-card-actions>
        <v-btn color="primary" @click="showModal = false">Chiudi</v-btn>
        </v-card-actions>
    </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

const props = defineProps({
    items: Array,
    headers: Array,
    totalItems: Number,
    itemsPerPage: Number,
    loading: Boolean,
    type: String,
    page: Number,
    searchName: String,
    searchOrderNumber: String,
    searchShippingNumber: String,
});

const search = ref('');
const showModal = ref(false);
const selectedItem = ref({});

function openModal(event, item) {
    selectedItem.value = item.item;
    showModal.value = true;
}

const emit = defineEmits(['updateItems']);

// Funzione per emettere i dati di ricerca
function handleOptionsUpdate(options) {
    emit('updateItems', {
        ...options,
        search: {
            name: props.searchName,
            shippingNumber: props.searchShippingNumber,
            orderNumber: props.searchOrderNumber,
        },
    });
}

// Utilizza lodash debounce per ritardare la chiamata
const debouncedHandleOptionsUpdate = debounce(handleOptionsUpdate, 500);

// Osserva i cambiamenti nei valori di ricerca
watch([() => props.searchName, () => props.searchShippingNumber, () => props.searchOrderNumber], () => {
    debouncedHandleOptionsUpdate({
        page: props.page,
        itemsPerPage: props.itemsPerPage,
    });
});
</script>
