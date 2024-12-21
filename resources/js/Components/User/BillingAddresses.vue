<template>
    <div class="py-5 px-4">
        <div class="d-flex align-center justify-space-between">
            <div class="text-h4 mb-4">Indirizzi di Fatturazione</div>
            <v-btn icon="mdi mdi-plus" color="info" class="mb-2" @click="openDialog()"></v-btn>
        </div>
        <v-row>
            <v-col cols="12" sm="6" lg="4" v-for="(address, index) in billingAddresses" :key="address.id">
                <v-card
                    rounded="xl"
                    class="py-4 px-4"
                    border="1"
                    elevation="0"
                    :class="{ 'selected-card': selectedCard === index }"
                    :title="address.address + ' - ' + (address.house_number || '')"
                    @click="handleCardClick(address, index)"
                    height="352"
                >
                    <v-card-subtitle>
                        <p class="text-body-2 mb-1">{{ address.city }}, {{ address.state }}, {{ address.country }}</p>
                        <p class="text-body-2 mb-1">{{ address.postal_code }}</p>
                        <p v-if="address.type === 'private'">{{ address.first_name }} {{ address.last_name }}</p>
                        <p v-if="address.type === 'company'">{{ address.company_name }}</p>
                        <p v-if="address.tax_code">C.F.: {{ address.tax_code }}</p>
                        <p v-if="address.vat_number">P.IVA: {{ address.vat_number }}</p>
                        <p v-if="address.sdi_code">SDI: {{ address.sdi_code }}</p>
                        <p>{{ address.phone_number }}</p>
                    </v-card-subtitle>
                    <v-card-actions class="justify-end">
                        <v-btn icon="mdi mdi-pencil" color="warning" @click="openDialog(index)"></v-btn>
                        <v-btn icon="mdi mdi-delete" color="red" @click="deleteBillingAddress(address.id)"></v-btn>
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
                        <v-select
                            v-model="form.type"
                            :items="['private', 'company']"
                            label="Tipo (Privato/Azienda)"
                            required
                        ></v-select>
                        <v-text-field
                            v-if="form.type === 'private'"
                            v-model="form.first_name"
                            label="Nome (Privati)"
                        ></v-text-field>
                        <v-text-field
                            v-if="form.type === 'private'"
                            v-model="form.last_name"
                            label="Cognome (Privati)"
                        ></v-text-field>
                        <v-text-field
                            v-if="form.type === 'company'"
                            v-model="form.company_name"
                            label="Ragione Sociale (Aziende)"
                        ></v-text-field>
                        <v-text-field
                            v-model="form.tax_code"
                            label="Codice Fiscale (Obbligatorio per Privati e Aziende)"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-if="form.type === 'company'"
                            v-model="form.vat_number"
                            label="Partita IVA (Aziende)"
                        ></v-text-field>
                        <v-text-field
                            v-if="form.type === 'company'"
                            v-model="form.sdi_code"
                            label="Codice SDI (Aziende)"
                        ></v-text-field>
                        <v-text-field
                            v-model="form.address"
                            label="Indirizzo"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.internal"
                            label="Interno (Opzionale)"
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
                            label="Telefono (Opzionale)"
                        ></v-text-field>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="saveBillingAddress" :disabled="!isFormValid">Salva</v-btn>
                    <v-btn text color="red" @click="closeDialog">Annulla</v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
    </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import axios from 'axios';
import { useNotificationStore } from '@/stores/notification.store';

const billingAddresses = ref([]);
const notificationStore = useNotificationStore();
const emit = defineEmits(['billing-address-selected']);

const dialogVisible = ref(false);
const editingIndex = ref(null);
const selectedCard = ref(null);
const form = ref({
    type: 'private',
    first_name: '',
    last_name: '',
    company_name: '',
    tax_code: '',
    vat_number: '',
    sdi_code: '',
    address: '',
    internal: '',
    house_number: '',
    postal_code: '',
    city: '',
    state: '',
    country: 'Italia',
    phone_number: '',
});

// Computed property per validare i campi obbligatori
const isFormValid = computed(() => {
    const baseValidation =
        form.value.address &&
        form.value.postal_code &&
        form.value.city &&
        form.value.state &&
        form.value.country;

    if (form.value.type === 'private') {
        return baseValidation && form.value.first_name && form.value.last_name && form.value.tax_code;
    } else if (form.value.type === 'company') {
        return baseValidation && form.value.company_name && form.value.tax_code;
    }
    return false;
});

function getBillingAddresses() {
    axios
        .get('/api/billing-addresses')
        .then((res) => {
            console.log(res.data)
            billingAddresses.value = res.data;
        })
        .catch((e) => {
            console.error(e);
        });
}

function handleCardClick(address, index) {
    selectedCard.value = index;
    emit('billing-address-selected', address);
}

function openDialog(index = null) {
    editingIndex.value = index;

    if (index !== null) {
        Object.assign(form.value, billingAddresses.value[index]);
    } else {
        resetForm();
    }

    dialogVisible.value = true;
}

function closeDialog() {
    dialogVisible.value = false;
    resetForm();
}

function resetForm() {
    Object.assign(form.value, {
        type: 'private',
        first_name: '',
        last_name: '',
        company_name: '',
        tax_code: '',
        vat_number: '',
        sdi_code: '',
        address: '',
        internal: '',
        house_number: '',
        postal_code: '',
        city: '',
        state: '',
        country: 'Italia',
        phone_number: '',
    });
}

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

getBillingAddresses();
</script>

<style scoped>
.selected-card {
    border: 2px solid #2A9BF3 !important;
    transform: scale(0.95);
    transition: transform 0.5s, border-color 0.2s;
}
</style>
