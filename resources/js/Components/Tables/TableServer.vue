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

    >
        <template #item.actions="{ item }" >
            <v-btn v-if="props.crud.includes('store')" variant="outlined" size="small" color="warning" class="me-3" @click="openModal(item)">
                <span class="mdi mdi-file-edit-outline"></span>
            </v-btn>
            <v-btn v-if="props.crud.includes('delete')" variant="outlined" size="small" color="danger" @click="deleteItem(item.id)">
                <span class="mdi mdi-delete-alert-outline"></span>
            </v-btn>
            <Link
                as="button"
                :href="route('admin.product.crud', { product: item.id })"
                v-if="props.crud.includes('show')"
                class="v-btn v-btn--outlined v-btn--small v-btn--text text-primary"
            >
                <span class="mdi mdi-eye-outline"></span>
            </Link>
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
                        v-else-if="header.key !== 'actions' "
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
                <v-btn v-if="!isEditable" color="warning" type="submit" text="Modifica" @click="isEditable = true"></v-btn>
                <v-btn v-if="isEditable" color="success" type="submit" text="Salva" @click="saveChanges(), isEditable = false"></v-btn>
                <v-btn color="primary" @click="showModal = false, isEditable = false">Chiudi</v-btn>
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
// Computed property per filtrare gli items
const filteredHeaders = computed(() => {
    return props.headers.filter(item => item.hidden !== true);
});

function openModal(item) {
    selectedItem.value = item;
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
            notificationStore.notify(e, 'danger')
        });
    } else {
        notificationStore.notify('Operazione Annullata! ', 'warning')
    }
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
