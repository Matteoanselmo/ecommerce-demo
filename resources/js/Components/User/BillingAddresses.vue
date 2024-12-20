<template>
    <div class="py-5 px-4">
        <div class="d-flex align-center justify-space-between">
            <div class="text-h4 mb-4">Indirizzi di Fatturazione</div>
            <v-btn icon="mdi mdi-plus" color="info" class="mb-2" @click="openDialog()">
            </v-btn>
        </div>
        <v-row>
            <v-col cols="12" sm="6" lg="4" v-for="(address, index) in billingAddresses" :key="address.id">
                <v-card
                    rounded="xl"
                    class="py-4 px-4"
                    border="1"
                    elevation="0"
                    :class="{ 'selected-card': selectedCard === index }"
                    :title="address.address + ' - ' + address.house_number"
                    @click="handleCardClick(address, index)"
                    height="352"
                >
                    <v-card-subtitle>
                        <p class="text-body-2 mb-1">{{ address.city }}, {{ address.state }}, {{ address.country }}</p>
                        <p class="text-body-2 mb-1">{{ address.postal_code }}</p>
                        <p>{{ address.name }}</p>
                        <p v-if="address.tax_id">P.IVA/C.F.: {{ address.tax_id }}</p>
                        <p>{{ address.phone_number }}</p>
                    </v-card-subtitle>
                    <v-card-actions class="justify-end">
                        <v-btn
                            icon="mdi mdi-pencil"
                            color="warning"
                            @click="openDialog(index)"
                        ></v-btn>
                        <v-btn
                            icon="mdi mdi-delete"
                            color="red"
                            @click="deleteBillingAddress(address.id)"
                        ></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <!-- Dialog per aggiungere o modificare indirizzi -->
        <v-dialog v-model="dialogVisible" persistent max-width="600">
            <v-card>
                <v-card-title>
                    <span class="text-h6">
                        {{ editingIndex === null ? 'Aggiungi Indirizzo di Fatturazione' : 'Modifica Indirizzo di Fatturazione' }}
                    </span>
                </v-card-title>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            v-model="form.name"
                            label="Nome/Denominazione"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.tax_id"
                            label="P.IVA / Codice Fiscale"
                        ></v-text-field>
                        <v-text-field
                            v-model="form.address"
                            label="Indirizzo"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.house_number"
                            label="N. Civico"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.postal_code"
                            label="CAP"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.city"
                            label="Città"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.state"
                            label="Provincia"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.country"
                            label="Nazione"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.phone_number"
                            label="Telefono"
                            required
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="saveBillingAddress">Salva</v-btn>
                    <v-btn text color="red" @click="closeDialog">Annulla</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notification.store';

// Stato degli indirizzi di fatturazione
const billingAddresses = ref([]);
const notificationStore = useNotificationStore();
const emit = defineEmits(['billing-address-selected']);

// Stato del dialog
const dialogVisible = ref(false);
const editingIndex = ref(null);
const selectedCard = ref(null);
const form = ref({
    name: '',
    tax_id: '',
    address: '',
    house_number: '',
    postal_code: '',
    city: '',
    state: '',
    country: 'Italia',
    phone_number: '',
});

// Carica gli indirizzi di fatturazione dal server
function getBillingAddresses() {
    axios
        .get('/api/billing-addresses')
        .then((res) => {
            billingAddresses.value = res.data;
        })
        .catch((e) => {
            console.error(e);
        });
}

// Gestisci il click sulla card
function handleCardClick(address, index) {
    selectedCard.value = index;
    emit('billing-address-selected', address);
}

// Apri il dialog per aggiungere o modificare
function openDialog(index = null) {
    editingIndex.value = index;

    if (index !== null) {
        Object.assign(form.value, billingAddresses.value[index]);
    } else {
        resetForm();
    }

    dialogVisible.value = true;
}

// Chiudi il dialog
function closeDialog() {
    dialogVisible.value = false;
    resetForm();
}

// Resetta il form
function resetForm() {
    Object.assign(form.value, {
        name: '',
        tax_id: '',
        address: '',
        house_number: '',
        postal_code: '',
        city: '',
        state: '',
        country: 'Italia',
        phone_number: '',
    });
}

// Salva o modifica un indirizzo di fatturazione
function saveBillingAddress() {
    if (editingIndex.value === null) {
        axios
            .post('/api/billing-addresses', form.value)
            .then((res) => {
                getBillingAddresses();
                notificationStore.notify(res.data.message, 'success');
                closeDialog();
            })
            .catch((error) => {
                console.error(error);
                notificationStore.notify(
                    "Si è verificato un errore durante il salvataggio dell'indirizzo.",
                    'danger'
                );
            });
    } else {
        const addressId = billingAddresses.value[editingIndex.value].id;
        axios
            .post(`/api/billing-addresses/${addressId}`, form.value, {
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                },
            })
            .then(() => {
                getBillingAddresses();
                notificationStore.notify('Indirizzo aggiornato con successo.', 'info');
                closeDialog();
            })
            .catch((error) => {
                console.error(error);
                notificationStore.notify(
                    "Si è verificato un errore durante l'aggiornamento dell'indirizzo.",
                    'danger'
                );
            });
    }
}

// Elimina un indirizzo di fatturazione
function deleteBillingAddress(id) {
    if (window.confirm('Sei sicuro di voler eliminare questo indirizzo?')) {
        axios
            .delete(`/api/billing-addresses/${id}`)
            .then(() => {
                getBillingAddresses();
                notificationStore.notify('Indirizzo eliminato con successo.', 'success');
            })
            .catch((error) => {
                console.error('Errore durante l\'eliminazione:', error);
                notificationStore.notify(
                    "Si è verificato un errore durante l'eliminazione dell'indirizzo.",
                    'danger'
                );
            });
    }
}

// Inizializza caricando gli indirizzi di fatturazione
getBillingAddresses();
</script>

<style scoped>
.selected-card {
    border: 2px solid #2A9BF3 !important;
    transform: scale(0.95);
    transition: transform 0.5s, border-color 0.2s;
}
</style>
