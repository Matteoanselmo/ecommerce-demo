<template>
    <div class="py-5 px-4">
        <div class="d-flex align-center justify-space-between">
            <div class="text-h4 mb-4">I miei Ticket</div>
            <v-btn icon="mdi mdi-plus" color="info" class="mb-2" @click="openDialog()">
            </v-btn>
        </div>
        <v-row>
            <v-col cols="12" sm="6" lg="4" v-for="(ticket, index) in tickets" :key="ticket.id">
                <v-card
                    rounded="xl"
                    class="py-4 px-4"
                    border="0"
                    elevation="0"
                    :title="ticket.subject"
                >
                    <v-card-text>
                        <p class="text-body-2 mb-1">Prodotto: {{ ticket.product }}</p>
                        <p class="text-body-2 mb-1">Descrizione: {{ ticket.description }}</p>
                        <p class="text-body-2 mb-1">Stato: <strong>{{ ticket.status }}</strong></p>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>

        <!-- Dialog per creare nuovi ticket -->
        <v-dialog v-model="dialogVisible" persistent max-width="600">
            <v-card>
                <v-card-title>
                    <span class="text-h6">Crea Nuovo Ticket</span>
                </v-card-title>
                <v-card-text>
                    <v-form>
                        <v-text-field
                            v-model="form.product"
                            label="Prodotto"
                            required
                        ></v-text-field>
                        <v-text-field
                            v-model="form.subject"
                            label="Oggetto"
                            required
                        ></v-text-field>
                        <v-textarea
                            v-model="form.description"
                            label="Descrizione"
                            required
                        ></v-textarea>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="createTicket">Salva</v-btn>
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

const tickets = ref([]); // Lista dei ticket
const notificationStore = useNotificationStore(); // Store per notifiche
const dialogVisible = ref(false); // Stato del dialog
const form = ref({
    product: '',
    subject: '',
    description: '',
});

// Funzione per caricare i ticket dell'utente
function getTickets() {
    axios
        .get('/api/support-tickets')
        .then((res) => {
            tickets.value = res.data.data;
        })
        .catch((e) => {
            console.error(e);
        });
}

// Aprire il dialog per creare un nuovo ticket
function openDialog() {
    resetForm();
    dialogVisible.value = true;
}

// Chiudere il dialog
function closeDialog() {
    dialogVisible.value = false;
    resetForm();
}

// Resettare il form
function resetForm() {
    Object.assign(form.value, {
        product: '',
        subject: '',
        description: '',
    });
}

// Funzione per creare un nuovo ticket
function createTicket() {
    axios
        .post('/api/support-tickets', form.value)
        .then((res) => {
            getTickets(); // Ricarica i ticket
            notificationStore.notify(res.data.message || 'Ticket creato con successo.', 'success');
            closeDialog();
        })
        .catch((error) => {
            console.error(error);
            notificationStore.notify('Errore durante la creazione del ticket.', 'danger');
        });
}

// Carica i ticket all'inizio
getTickets();
</script>
