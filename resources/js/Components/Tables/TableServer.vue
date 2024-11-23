<template>
    <v-data-table-server
        :expand-on-click="false"
        :items-per-page="itemsPerPage"
        :headers="filteredHeaders"
        :items="items"
        :items-length="totalItems"
        :loading="loading"
        :search="search"
        loading-text="Caricamento in corso..."
        item-value="name"
        @update:options="handleOptionsUpdate"
    >
    <!-- Slot per contenuti aggiuntivi in alto -->
        <template #top>
            <div class="d-flex justify-end align-center py-3 pr-3">
                <v-btn
                    v-if="props.crud.includes('store')"
                    color="info"
                    size="small"
                    @click="openCreateModal()"
                    icon="mdi mdi-plus"
                    rounded="xl"
                >
                </v-btn>
            </div>
        </template>
        <template #item.color.name="{ item }" >
            <v-card :text="item.color.name" elevation="0" :color="item.color.name" class="my-2">
            </v-card>
        </template>

        <template #item.actions="{ item }" >
            <Link
                as="button"
                :href="route('admin.product.crud', { product: item.id })"
                v-if="props.crud.includes('show')"
                class="v-btn v-theme--myCustomTheme text-warning v-btn--density-default v-btn--size-small v-btn--variant-outlined me-3"
            >
                <span class="mdi mdi-edit-outline"></span>
            </Link>
            <v-btn v-if="props.crud.includes('update')" variant="outlined" size="small" color="warning" class="me-3" @click="openModal(item)">
                <span class="mdi mdi-file-edit-outline"></span>
            </v-btn>
            <v-btn v-if="props.crud.includes('delete')" variant="outlined" size="small" color="danger" @click="deleteItem(item.id)">
                <span class="mdi mdi-delete-alert-outline"></span>
            </v-btn>
        </template>
    </v-data-table-server>

    <v-dialog v-model="showModal" max-width="600">
    <v-card rounded="xl">
        <v-card-title>{{ isCreateMode ? 'Crea Nuovo Elemento' : 'Dettagli Ordine' }}</v-card-title>
        <v-form class="px-3">
            <div v-for="(header) in props.headers" :key="header.key" class="mb-3">
                <v-select
                    v-if="header.type === 'select'"
                    v-model="selectedItem[header.model]"
                    :label="header.title"
                    :items="header.items"
                    item-title="name"
                    item-value="id"
                    variant="solo-filled"
                    :disabled="!isEditable && !isCreateMode"
                    @update:modelValue="handleSelectChange(header.key, selectedItem[header.model])"
                ></v-select>
                <v-text-field
                    v-else-if="header.key !== 'actions' && header.isEditable"
                    variant="solo-filled"
                    v-model="selectedItem[header.key]"
                    :type="header.type"
                    :label="header.title"
                    :key="header.key"
                    :disabled="!isEditable && !isCreateMode"
                ></v-text-field>
            </div>
        </v-form>
        <v-card-actions>
            <v-btn
                v-if="isCreateMode"
                color="success"
                text="Crea"
                @click="createNewItem(), closeModal()"
            ></v-btn>
            <v-btn
                v-if="!isEditable && !isCreateMode"
                color="warning"
                text="Modifica"
                @click="isEditable = true"
            ></v-btn>
            <v-btn
                v-if="!isCreateMode && isEditable"
                color="success"
                text="Salva"
                @click="saveChanges(), isEditable = false, closeModal()"
            ></v-btn>
            <v-btn
                color="primary"
                @click="closeModal()"
            >
                Chiudi
            </v-btn>
        </v-card-actions>
    </v-card>
</v-dialog>

</template>


<script setup>
import { ref, computed, watch } from 'vue';
import { debounce } from 'lodash';
import { useNotificationStore } from '@/stores/notification.store';
import { Link } from '@inertiajs/vue3';
const props = defineProps({
    items: Array,
    headers: Array,
    totalItems: Number,
    itemsPerPage: Number,
    loading: Boolean,
    type: String,
    page: Number,
    searchFields: Array,
    crud: {
        type: Array,
        default: ['store', 'update', 'delete']
    }
});

const notificationStore = useNotificationStore();
const search = ref('');
const showModal = ref(false);
const selectedItem = ref({});
const isEditable = ref(false);
const isCreateMode = ref(false);
// Computed property per filtrare gli items
const filteredHeaders = computed(() => {
    return props.headers.filter(item => item.hidden !== true);
});

function openModal(item) {
    selectedItem.value = item;
    showModal.value = true;
}

function openCreateModal(item) {
    isCreateMode.value = true;
    selectedItem.value = item;
    selectedItem.value = {}; // Oggetto effimero per la creazione
    showModal.value = true;
}

function closeModal() {
    isCreateMode.value = false;
    isEditable.value = false;
    showModal.value = false;
}

const emit = defineEmits([
    'updateItems',
    'select-change'
]);

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
// Funzione per salvare le modifiche (PATCH)
function saveChanges() {
    axios.patch(`/api/${props.type}/${selectedItem.value.id}`, selectedItem.value)
    .then(res => {
        console.log(res)
        notificationStore.notify(res.data.message, res.data.color);
        emit('updateItems');
    }).catch((e) => {
        notificationStore.notify(e, 'danger')
        console.error(e)
    });
}

// Funzione per eliminare un elemento (DELETE)
function deleteItem(id) {
    if(window.confirm("Sei sicuro di voler eliminare questo elemento?")){
        axios.delete(`/api/${props.type}/${id}`)
        .then(res => {
            notificationStore.notify(res.data.message, res.data.color);
            console.log(res.data)
            emit('updateItems');
        }).catch((e) => {
            console.error(e)
            notificationStore.notify(e.response.data.message, 'danger')
        });
    } else {
        notificationStore.notify('Operazione Annullata! ', 'warning')
    }
}
// Funzione per creare un elemento
function createNewItem() {
    axios.post(`/api/${props.type}`, selectedItem.value)
        .then(res => {
            notificationStore.notify(res.data.message, res.data.color);
            emit('updateItems'); // Aggiorna la lista
            closeModal(); // Chiudi il modale
        })
        .catch(e => {
            notificationStore.notify(e.response?.data?.message || 'Errore nella creazione', 'danger');
        });
}

function handleSelectChange(key, value) {
    // Emetti un evento al genitore con la chiave e il valore selezionato
    emit('select-change', { key, value });
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
