<template>
    <v-card rounded="xl" elevation="0">
        <v-card-title class="d-flex justify-space-between">
            <span>{{ capitalize(type) }}</span>
            <v-btn
                size="small"
                color="info"
                icon="mdi-plus"
                @click="openDialog()"
            ></v-btn>
        </v-card-title>
        <v-card-text>
            <v-chip-group filter v-model="selectedItem" prepend-icon="mdi-check">
                <v-chip
                    v-for="(item, i) in items"
                    :key="i"
                    :value="item.id"
                    @click="selectItem(item)"
                    :color="props.input === 'color' ? item.hex : ''"
                >
                    <v-card
                        elevation="0"
                        rounded="xl"
                        width="100%"
                        v-if="props.input === 'color'"
                        :text="item.name"
                        :color="item.name"
                    >

                    </v-card>
                    <span v-else>
                        {{ item.name }}
                    </span>

                </v-chip>
            </v-chip-group>
        </v-card-text>
        <v-card-actions class="justify-end">
            <v-btn
                color="warning"
                :disabled="!selectedItem"
                append-icon="mdi-pen"
                @click="editItem()"
            ></v-btn>
            <v-btn
                color="danger"
                :disabled="!selectedItem"
                append-icon="mdi-delete"
                @click="deleteItem()"
            ></v-btn>
        </v-card-actions>
    </v-card>
    <v-dialog v-model="dialog" max-width="600">
        <v-card :title="isEditingMode ? `Aggiorna ${capitalize(type)}` : `Crea Nuovo ${capitalize(type)}`" rounded="xl">
            <v-card-text>
                <v-text-field
                    v-if="props.input === 'text'"
                    v-model="form.name"
                    hide-details="auto"
                    :label="`Nome ${capitalize(type)}`"
                ></v-text-field>
                <div  v-else-if="props.input === 'color'" class="d-flex justify-space-around align-center">
                    <v-color-picker
                        v-model="form.name"
                        :modes="['hex']"
                        show-swatches
                        hide-inputs
                    >
                    </v-color-picker>
                    <v-color-picker
                        :modes="['hex']"
                        v-model="form.name"
                        hide-canvas
                        hide-sliders
                    ></v-color-picker>
                </div>
            </v-card-text>
            <v-card-actions class="justify-end">
                <v-btn
                    :append-icon="isEditingMode ? 'mdi-pen' : 'mdi-check'"
                    :color="isEditingMode ? 'warning' : 'success'"
                    @click="storeOrUpdate"
                >
                    {{ isEditingMode ? 'Aggiorna' : 'Crea' }}
                </v-btn>
                <v-btn
                    append-icon="mdi-close"
                    color="danger"
                    @click="closeDialog"
                >
                    Chiudi
                </v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notification.store';

const notificationStore = useNotificationStore();

// Props
const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    type: {
        type: String,
        required: true,
    },
    input: {
        type: String,
        default: 'text'
    }
});

// Reactive variables
const selectedItem = ref(null); // Elemento selezionato
const dialog = ref(false); // Stato del dialog
const isEditingMode = ref(false); // Modalità modifica o creazione
const form = ref({
    id: null, // ID dell'elemento
    name: '', // Nome dell'elemento
});

const emit = defineEmits(['itemsUpdated']);

// Utility per capitalizzare la prima lettera
const capitalize = (str) => str.charAt(0).toUpperCase() + str.slice(1);

// Funzione per aprire il dialog
function openDialog() {
    isEditingMode.value = false;
    form.value = { id: null, name: '' };
    dialog.value = true;
}

// Funzione per selezionare un elemento
const selectItem = (item) => {
    selectedItem.value = item;
};

// Funzione per aprire il dialog in modalità modifica
function editItem() {
    if (selectedItem.value) {
        isEditingMode.value = true;
        const item = props.items.find((b) => b.id === selectedItem.value || b.name === selectedItem.value);

        if (item) {
            form.value = { id: item.id, name: item.name };
            dialog.value = true;
        } else {
            console.error('Elemento non trovato per il valore selezionato:', selectedItem.value);
        }
    }
}

// Funzione per chiudere il dialog
function closeDialog() {
    dialog.value = false;
    isEditingMode.value = false;
    form.value = { id: null, name: '' };
}

// Funzione per creare o aggiornare un elemento
function storeOrUpdate() {
    const url = isEditingMode.value
        ? `/api/${props.type}/${form.value.id}` // URL per aggiornare
        : `/api/${props.type}`; // URL per creare
    const method = isEditingMode.value ? 'patch' : 'post';

    axios[method](url, { name: form.value.name })
        .then((response) => {
            closeDialog();
            notificationStore.notify(response.data.message, response.data.color);
            emit('itemsUpdated');
        })
        .catch((error) => {
            console.error(`Errore nella creazione/aggiornamento di ${props.type}:`, error.response?.data || error);
            notificationStore.notify(
                `Errore nella creazione/aggiornamento di ${props.type}: ${error.response?.data?.message || 'Errore'}`,
                'danger'
            );
        });
}

// Funzione per eliminare un elemento
function deleteItem() {
    if (window.confirm(`Sei sicuro di voler eliminare questo ${capitalize(props.type)}?`)) {
        axios.delete(`/api/${props.type}/${selectedItem.value}`)
            .then((res) => {
                notificationStore.notify(res.data.message, res.data.color);
                emit('itemsUpdated');
            })
            .catch((e) => {
                console.error(e);
                notificationStore.notify(`Errore nell'eliminazione del ${capitalize(props.type)}`, 'danger');
            });
    }
}
</script>
