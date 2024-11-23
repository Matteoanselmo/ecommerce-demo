<template>
    <v-card rounded="xl" elevation="0">
        <v-card-title class="d-flex justify-space-between">
            <span>Brands</span>
            <v-btn
                size="small"
                color="info"
                icon="mdi-plus"
                @click="openDialog()"
            ></v-btn>
        </v-card-title>
        <v-card-text>
            <v-chip-group filter v-model="selectedBrand" prepend-icon="mdi-check">
                <v-chip
                    v-for="(brand, i) in brands"
                    :key="i"
                    :value="brand.id"
                    @click="selectBrand(brand)"
                >
                    {{ brand.name }}
                </v-chip>
            </v-chip-group>
        </v-card-text>
        <v-card-actions class="justify-end">
            <v-btn
                color="warning"
                :disabled="!selectedBrand"
                append-icon="mdi-pen"
                @click="editBrand()"
            ></v-btn>
            <v-btn
                color="danger"
                :disabled="!selectedBrand"
                append-icon="mdi-delete"
                @click="deleteBrand()"
            ></v-btn>
        </v-card-actions>
    </v-card>
    <v-dialog v-model="dialog" max-width="600">
        <v-card :title="isEditingMode ? 'Aggiorna il Brand' : 'Crea Nuovo Brand'" rounded="xl">
            <v-card-text>
                <v-text-field
                    v-model="form.name"
                    hide-details="auto"
                    label="Nome Brand"
                ></v-text-field>
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
    brands: {
        type: Array,
        required: true,
    },
});

// Reactive variables
const selectedBrand = ref(null); // Brand selezionato
const dialog = ref(false); // Stato del dialog
const isEditingMode = ref(false); // Modalità modifica o creazione
const form = ref({
    id: null, // ID del brand
    name: '', // Nome del brand
});
const emit = defineEmits(['brandsUpdated']);


// Funzione per aprire il dialog
function openDialog () {
    isEditingMode.value = false;
    form.value = { id: null, name: '' };
    dialog.value = true;
};

// Funzione per selezionare un brand
const selectBrand = (brand) => {
    selectedBrand.value = brand;
};

// Funzione per aprire il dialog in modalità modifica
function editBrand() {
    if (selectedBrand.value) {
        isEditingMode.value = true;
        const brand = props.brands.find((b) => b.id === selectedBrand.value || b.name === selectedBrand.value);

        if (brand) {
            form.value = { id: brand.id, name: brand.name };
            dialog.value = true;
        } else {
            console.error('Brand non trovato per il valore selezionato:', selectedBrand.value);
        }
    }
};
// Funzione per chiudere il dialog
function closeDialog () {
    dialog.value = false;
    isEditingMode.value = false;
    form.value = { id: null, name: '' };
};

// Funzione per creare o aggiornare un brand
function storeOrUpdate (){
    const url = isEditingMode.value
        ? `/api/brands/${form.value.id}` // URL per aggiornare
        : '/api/brands'; // URL per creare
    const method = isEditingMode.value ? 'patch' : 'post';

    axios[method](url, { name: form.value.name })
        .then((response) => {
            closeDialog();
            notificationStore.notify(response.data.message, response.data.color)
            emit('brandsUpdated');
        })
        .catch((error) => {
            console.error('Errore nella creazione/aggiornamento del brand:', error.response?.data || error);
            notificationStore.notify('Errore nella creazione/aggiornamento del brand:', error.response?.data || error, 'danger')
        });
};

// Funzione per eliminare un brand
function deleteBrand () {
    if(window.confirm('Sei sicuro di voler eliminare questo Brandf?')){
        axios.delete('/api/brands/' + selectedBrand.value)
            .then((res) => {
                notificationStore.notify(res.data.message, res.data.color);
                emit('brandsUpdated');
            }).catch((e) =>{
                console.error(e);
                notificationStore.notify('Errore nell\'eliminazione del brand', 'danger');
            })
    }
};
</script>
