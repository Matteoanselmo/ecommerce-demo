<template>
    <v-data-table-server
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

    <template v-slot:tfoot>
        <tr>
        <td>
            <v-text-field v-model="name" class="ma-2" density="compact" placeholder="Utente" hide-details></v-text-field>
        </td>
        <td>
            <v-text-field
            v-model="shippingNumber"
            class="ma-2"
            density="compact"
            placeholder="Spedizione"
            hide-details
            ></v-text-field>
        </td>
        <td>
            <v-text-field
            v-model="orderNumber"
            class="ma-2"
            density="compact"
            placeholder="Numero Ordine"
            hide-details
            ></v-text-field>
        </td>
        </tr>
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
});

const name = ref('');
const shippingNumber = ref('');
const orderNumber = ref('');
const search = ref('');
const showModal = ref(false);
const selectedItem = ref({});

function openModal(event, item) {
    selectedItem.value = item.item; // Assegna l'elemento selezionato
    showModal.value = true; // Mostra il modale
}

const emit = defineEmits(['updateItems']);

function handleOptionsUpdate(options) {
    emit('updateItems', {
    ...options,
    search: {
        name: name.value,
        shippingNumber: shippingNumber.value,
        orderNumber: orderNumber.value,
    },
    });
}

// Utilizza lodash debounce per ritardare la chiamata
const debouncedHandleOptionsUpdate = debounce(handleOptionsUpdate, 500);

watch([name, shippingNumber, orderNumber], () => {
    debouncedHandleOptionsUpdate({
    page: props.page,
    itemsPerPage: props.itemsPerPage,
    });
});
</script>
