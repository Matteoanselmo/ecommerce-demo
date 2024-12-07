<template>
    <div class="py-5 px-4">
        <div class="d-flex align-center justify-space-between">
            <div class="text-h4 mb-4">Indirizzi</div>
            <v-btn icon="mdi mdi-plus" color="info" class="mb-2" @click="openDialog()">
            </v-btn>
        </div>
        <v-row>
            <v-col cols="12" sm="6" lg="4" v-for="(address, index) in addresses" :key="address.id">
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
                        <p v-if="address.company_name">{{ address.company_name }}</p>
                        <p>{{ address.recipient_name }}</p>
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
                            @click="deleteAddress(address.id)"
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
                        {{ editingIndex === null ? 'Aggiungi Indirizzo' : 'Modifica Indirizzo' }}
                    </span>
                </v-card-title>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            v-model="form.recipient_name"
                            label="Destinatario"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.company_name"
                            label="Presso"
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
                    <v-btn text color="success" @click="saveAddress">Salva</v-btn>
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

// Stato degli indirizzi
const addresses = ref([]);
const notificationStore = useNotificationStore();
const emit = defineEmits(['address-selected']);

// Stato del dialog
const dialogVisible = ref(false);
const editingIndex = ref(null); // Indica se stiamo modificando o aggiungendo
const selectedCard = ref(null); // Tiene traccia della card selezionata
const form = ref({
    recipient_name: '',
    company_name: '',
    address: '',
    house_number: '',
    postal_code: '',
    city: '',
    state: '',
    country: 'Italia',
    phone_number: '',
});

// Carica gli indirizzi dal server
function getAddresses() {
    axios
        .get('/api/user-addresses')
        .then((res) => {
            addresses.value = res.data;
            // Seleziona automaticamente l'indirizzo primario
            const primaryIndex = addresses.value.findIndex((address) => address.is_primary === 1);
            if (primaryIndex !== -1) {
                selectedCard.value = primaryIndex;
                emit('address-selected', addresses.value[primaryIndex]); // Emetti l'indirizzo primario selezionato
            }
        })
        .catch((e) => {
            console.error(e);
        });
}

// Gestisci il click sulla card
function handleCardClick(address, index) {
    selectedCard.value = index;
    emit('address-selected', address);
}

// Apri il dialog per aggiungere o modificare
function openDialog(index = null) {
    editingIndex.value = index;

    if (index !== null) {
        Object.assign(form.value, addresses.value[index]);
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
        recipient_name: '',
        company_name: '',
        address: '',
        house_number: '',
        postal_code: '',
        city: '',
        state: '',
        country: 'Italia',
        phone_number: '',
    });
}

function saveAddress() {
    if (editingIndex.value === null) {
        axios
            .post('/api/user-addresses', form.value)
            .then((res) => {
                getAddresses();
                notificationStore.notify(res.data.message, res.data.color);
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
        const addressId = addresses.value[editingIndex.value].id;
        axios
            .post(`/api/user-addresses/${addressId}`, form.value, {
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                },
            })
            .then((res) => {
                getAddresses();
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

function deleteAddress(id) {
    if (window.confirm('Sei sicuro di voler eliminare questo indirizzo?')) {
        axios
            .delete(`/api/user-addresses/${id}`)
            .then(() => {
                getAddresses();
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

// Inizializza caricando gli indirizzi
getAddresses();
</script>


<style scoped>
.selected-card {
    border: 2px solid #2A9BF3 !important;
    transform: scale(0.95);
    transition: transform 0.5s, border-color 0.2s;
}
</style>
