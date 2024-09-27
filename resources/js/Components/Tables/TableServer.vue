<template>
    <v-data-table-server
        :rounded="true"
        :expand-on-click="false"
        :items-per-page="itemsPerPage"
        :headers="filteredHeaders"
        :items="items"
        :items-length="items.length"
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
            <v-card-title v-if="isEditable" class="text-warning">Attenzione Modifica Attiva!</v-card-title>
            <v-form class="px-3">
                    <div v-for="(header) in props.headers" :key="header.key" class="mb-3" >
                        <v-select
                            v-if="header.type ==='select'"
                            v-model="selectedItem[header.key]"
                            :label="header.title"
                            :items="header.items"
                            variant="solo-filled"
                            :disabled="!isEditable"
                        ></v-select>
                        <v-text-field
                        v-if="header.key !== 'actions' "
                        variant="solo-filled"
                            v-model="selectedItem[header.key]"
                            :type="header.type"
                            :label="header.title"
                            :key="header.key"
                            :disabled="!isEditable"
                        ></v-text-field>
                    </div>
                </v-form>
            <v-card-actions>
                <v-btn color="success" type="submit" :text="isEditable ? 'Salva' : 'Modifica'" @click="isEditable = !isEditable"></v-btn>
                <v-btn color="primary" @click="showModal = false">Chiudi</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>


<script setup>
import { ref, computed, watch } from 'vue';
import { debounce } from 'lodash';

// Riceviamo un array di campi di ricerca attraverso le props
const props = defineProps({
    items: Array,
    headers: Array,
    totalItems: Number,
    itemsPerPage: Number,
    loading: Boolean,
    type: String,
    page: Number,
    searchFields: Array // Adesso riceviamo un array di oggetti per i campi di ricerca
});

const search = ref('');
const showModal = ref(false);
const selectedItem = ref({});
const isEditable = ref(false);
// Computed property per filtrare gli items
const filteredHeaders = computed(() => {
    return props.headers.filter(item => item.hidden !== true);
});

function openModal(event, item) {
    selectedItem.value = item.item;
    console.log(selectedItem.value)
    showModal.value = true;
}

const emit = defineEmits(['updateItems']);

// Funzione per emettere i dati di ricerca
function handleOptionsUpdate(options) {
    const searchParams = {};
    props.searchFields.forEach(field => {
        searchParams[field.key] = field.value;
    });
    emit('updateItems', {
        ...options,
        search: searchParams,
    });
}

// Utilizza lodash debounce per ritardare la chiamata
const debouncedHandleOptionsUpdate = debounce(handleOptionsUpdate, 500);

// Osserva i cambiamenti nei valori di ricerca
watch(
    () => props.searchFields.map(field => field.value),
    () => {
        debouncedHandleOptionsUpdate({
            page: props.page,
            itemsPerPage: props.itemsPerPage,
        });
    }
);
</script>
