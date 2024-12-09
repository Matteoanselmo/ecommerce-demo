<template>
    <div class="py-5 px-4">
        <div class="d-flex align-center justify-space-between">
            <div class="text-h4 mb-4">I miei Ticket</div>
            <v-btn icon="mdi mdi-plus" color="info" class="mb-2" @click="openDialog()"></v-btn>
        </div>
        <v-row>
            <v-col cols="12" sm="6" lg="4" v-for="(ticket, index) in tickets" :key="ticket.id">
                <v-card
                    rounded="xl"
                    class="py-4 px-4"
                    border="1"
                    elevation="0"
                    :class="{ 'selected-card': selectedCard === index }"
                    :title="ticket.subject"
                    @click="handleCardClick(ticket, index)"
                    height="300"
                >
                    <v-card-subtitle>
                        <p class="text-body-2 mb-1">Prodotto: {{ ticket.product }}</p>
                        <p class="text-body-2 mb-1">Descrizione: {{ ticket.description }}</p>
                        <p class="text-body-2 mb-1">
                            Stato: <strong>{{ ticket.status }}</strong>
                        </p>
                    </v-card-subtitle>
                    <v-card-actions class="justify-end">
                        <v-btn
                            icon="mdi mdi-pencil"
                            color="warning"
                            @click.stop="openDialog(index)"
                        ></v-btn>
                        <v-btn
                            icon="mdi mdi-delete"
                            color="red"
                            @click.stop="deleteTicket(ticket.id)"
                        ></v-btn>
                    </v-card-actions>
                </v-card>
            </v-col>
        </v-row>

        <!-- Dialog per aggiungere o modificare ticket -->
        <v-dialog v-model="dialogVisible" persistent max-width="600">
            <v-card>
                <v-card-title>
                    <span class="text-h6">
                        {{ editingIndex === null ? 'Crea Nuovo Ticket' : 'Modifica Ticket' }}
                    </span>
                </v-card-title>
                <v-card-text>
                    <v-form>
                        <v-text-field v-model="form.product" label="Prodotto" required></v-text-field>
                        <v-text-field v-model="form.subject" label="Oggetto" required></v-text-field>
                        <v-textarea v-model="form.description" label="Descrizione" required></v-textarea>
                    </v-form>
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn text color="success" @click="saveTicket">Salva</v-btn>
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

const tickets = ref([]);
const notificationStore = useNotificationStore();
const dialogVisible = ref(false);
const editingIndex = ref(null);
const selectedCard = ref(null);
const form = ref({
    product: '',
    subject: '',
    description: '',
});

function getTickets() {
    axios
        .get('/api/support-tickets')
        .then((res) => {
            tickets.value = res.data.data;
        })
        .catch((e) => console.error(e));
}

function handleCardClick(ticket, index) {
    selectedCard.value = index;
}

function openDialog(index = null) {
    editingIndex.value = index;

    if (index !== null) {
        Object.assign(form.value, tickets.value[index]);
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
        product: '',
        subject: '',
        description: '',
    });
}

function saveTicket() {
    if (editingIndex.value === null) {
        axios
            .post('/api/support-tickets', form.value)
            .then((res) => {
                getTickets();
                notificationStore.notify(res.data.message, 'success');
                closeDialog();
            })
            .catch((error) => {
                console.error(error);
                notificationStore.notify('Errore durante il salvataggio del ticket.', 'danger');
            });
    } else {
        const ticketId = tickets.value[editingIndex.value].id;
        axios
            .post(`/api/support-tickets/${ticketId}`, form.value, {
                headers: {
                    'X-HTTP-Method-Override': 'PUT',
                },
            })
            .then((res) => {
                console.log(res.data)
                getTickets();
                notificationStore.notify('Ticket aggiornato con successo.', 'info');
                closeDialog();
            })
            .catch((error) => {
                console.error(error);
                notificationStore.notify('Errore durante l\'aggiornamento del ticket.', 'danger');
            });
    }
}

function deleteTicket(id) {
    if (window.confirm('Sei sicuro di voler eliminare questo ticket?')) {
        axios
            .delete(`/api/support-tickets/${id}`)
            .then(() => {
                getTickets();
                notificationStore.notify('Ticket eliminato con successo.', 'success');
            })
            .catch((error) => {
                console.error('Errore durante l\'eliminazione:', error);
                notificationStore.notify('Errore durante l\'eliminazione del ticket.', 'danger');
            });
    }
}

getTickets();
</script>

<style scoped>
.selected-card {
    border: 2px solid #2A9BF3 !important;
    transform: scale(0.95);
    transition: transform 0.5s, border-color 0.2s;
}
</style>
