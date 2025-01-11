<template>
    <div class="py-5 px-4">
        <div class="text-h4 mb-4">I miei ordini</div>
        <div class="d-flex justify-between">
            <!-- Cicliamo su searchFields per generare i campi di input -->
            <v-text-field
                v-for="(field, index) in searchFields"
                :key="index"
                variant="solo-filled"
                :prepend-inner-icon="field.icon"
                v-model="field.value"
                :label="field.label"
                clearable
                @input="debouncedFetchOrders"
                class="px-2"
            ></v-text-field>
        </div>
        <v-data-table-server
            :expand-on-click="false"
            :items-per-page="options.itemsPerPage"
            :headers="headers"
            :items="items"
            :items-length="totalItems"
            :loading="loading"
            loading-text="Caricamento in corso..."
            item-value="name"
            @update:options="updateOptions"
            mobile-breakpoint="md"
        >
            <template #item.payment="{ item }">
                <v-chip color="success" text="white">{{ item.payment }}</v-chip>
            </template>
            <template #item.status="{ item }" >
                <div @click="openStatusDialog(item)" style="cursor: pointer;">
                    <v-chip v-if="item.status === 'confirmed'" color="info"  text="white">{{ item.status }}</v-chip>
                    <v-chip v-else-if="item.status === 'delivered'" color="success" text="white">{{ item.status }}</v-chip>
                    <v-chip v-else-if="item.status === 'on_delivery'" color="warning" text="white">{{ item.status }}</v-chip>
                    <v-chip v-else-if="item.status === 'in_progress'" text="white">{{ item.status }}</v-chip>
                    <v-chip v-else-if="item.status === 'returned'" color="danger" text="white">{{ item.status }}</v-chip>
                </div>
            </template>
            <template #item.tracking="{ item }">
                <Link href="/" class="text-decoration-none">{{ item.tracking }}</Link>
            </template>
            <template #item.details="{ item }">
                <p>{{ item.details }}</p>
            </template>
            <template #item.total_amount="{ item }">
                {{ $formatPrice(item.total_amount) }}
            </template>
            <template #item.fattura="{ item }">
                <a class="v-btn v-btn--elevated v-theme--myCustomTheme v-btn--density-default v-btn--size-small v-btn--variant-outlined" :href="item.fattura" download target="blank" v-if="item.fattura">
                <v-icon icon="mdi-download"></v-icon>
            </a>
            </template>
        </v-data-table-server>
    </div>
    <v-dialog
        v-model="statusDialog"
        transition="dialog-bottom-transition"
        fullscreen
        >
        <v-card>
            <v-toolbar>
                <v-btn
                    icon="mdi-close"
                    @click="closeStatusDialog()"
                ></v-btn>
                <v-toolbar-title>Status Ordine</v-toolbar-title>
                <v-spacer></v-spacer>
            </v-toolbar>
                <v-card elevation="0" height="100%" class="mt-5 d-flex flex-column" title="Storico della spedizione" subtitle="Segui passo passo le informazioni relative allo stato del tuo Ordine">
                    <v-card-text>
                        <v-timeline side="end" >
                            <v-timeline-item
                                v-for="item in statusItems"
                                :key="item.id"
                                size="small"
                            >
                                <v-alert
                                    :icon="item.icon"
                                    :value="true"
                                >
                                    {{ item.text }}
                                </v-alert>
                            </v-timeline-item>
                        </v-timeline>
                    </v-card-text>
                    <v-card-actions class="justify-end">
                        <v-btn v-if="selectedStatusItem.status !== 'returned'" variant="outlined" text="Reso / Rimborso">
                        </v-btn>
                    </v-card-actions>
                </v-card>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

const headers = [
    { title: "Ordine", value: "order_number", align: "start" },
    { title: "Data", value: "order_date" },
    { title: "Spedito in", value: "shipped_in" },
    { title: "Pagamento", value: "payment" },
    { title: "Stato Ordine", value: "status" },
    { title: "Spedizione", value: "shipping_number", sortable: false },
    { title: "Dettagli", value: "details", sortable: false },
    { title: "Totale", value: "total_amount" },
    { title: "Fattura", value: "fattura", sortable: false },
];

const searchFields = ref([
    { key: 'orderNumber', value: '', label: 'Ordine', icon: 'mdi-magnify' },
    { key: 'shippingNumber', value: '', label: 'Spedizione', icon: 'mdi-magnify' },
]);

const statusDialog = ref(false);
const statusItems = ref([]);
const selectedStatusItem = ref({})
const items = ref([]);
const totalItems = ref(0);
const loading = ref(false);
const options = ref({
    page: 1,
    itemsPerPage: 10,
    sortBy: [],
    sortDesc: [],
    groupBy: [],
    groupDesc: [],
    search: '',
});

function openStatusDialog(item) {
    statusItems.value = [];
    selectedStatusItem.value = item;
    // Aggiungi un evento per la creazione dell'ordine
    if (item.order_date) {
        statusItems.value.push({
            id: 1,
            icon: 'mdi-cart',
            text: `Il tuo ordine è stato creato il ${item.order_date}. Siamo entusiasti di iniziare a lavorare per preparare i tuoi prodotti.`,
        });
    }

    // Aggiungi un evento per la spedizione dell'ordine (se disponibile)
    if (item.shipped_in) {
        statusItems.value.push({
            id: 2,
            icon: 'mdi-truck',
            text: `Il tuo ordine è stato spedito il ${item.shipped_in}. Ti consigliamo di tenere d'occhio il numero di tracciamento ${item.shipping_number} per aggiornamenti sulla consegna.`,
        });
    }

    // Aggiungi un evento per lo stato dell'ordine
    if (item.status) {
        const statusMessages = {
            confirmed: `Il tuo ordine è stato confermato e sarà presto processato per la spedizione. Grazie per la tua fiducia!`,
            delivered: `Siamo felici di informarti che il tuo ordine è stato consegnato con successo. Speriamo che i prodotti siano di tuo gradimento!`,
            on_delivery: `Il tuo ordine è attualmente in consegna. Il corriere è in viaggio per portarti il pacco. Presto sarà tra le tue mani!`,
            in_progress: `Il tuo ordine è attualmente in fase di lavorazione. Il nostro team sta preparando con cura i tuoi prodotti.`,
            returned: `Il tuo ordine è stato restituito. Per ulteriori informazioni o assistenza, ti invitiamo a contattare il nostro servizio clienti.`,
        };

        statusItems.value.push({
            id: 3,
            icon: 'mdi-information',
            text: statusMessages[item.status] || `Lo stato corrente del tuo ordine è: ${item.status}.`,
        });
    }

    // Aggiungi eventuali dettagli personalizzati dall'oggetto `item.details`
    if (item.details) {
        statusItems.value.push({
            id: 4,
            icon: 'mdi-note-text',
            text: `Dettagli aggiuntivi dell'ordine: ${item.details}. Questi possono includere note o informazioni specifiche fornite durante l'acquisto.`,
        });
    }

    statusDialog.value = true;
}


function closeStatusDialog(){
    statusDialog.value = false;
    selectedStatusItem.value = {};
}

function fetchOrders() {
    loading.value = true;

     // Costruiamo l'oggetto di ricerca dall'array searchFields
    const searchQuery = searchFields.value.reduce((acc, field) => {
        acc[field.key] = field.value;
        return acc;
    }, {});

    // API request for server-side data
    axios
        .get('/api/user-orders', {
            params: {
                page: options.value.page,
                per_page: options.value.itemsPerPage,
                sortBy: options.value.sortBy,
                sortDesc: options.value.sortDesc,
                search: options.value.search,
                search_shipping_number: searchQuery.shippingNumber,
                search_order_number: searchQuery.orderNumber,
            },
        })
        .then((res) => {
            items.value = res.data.data;
            totalItems.value = res.data.total;
        })
        .finally(() => {
            loading.value = false;
        }).catch((e) => {
            loading.value = false;
            console.error(e)
        })
}

function updateOptions(newOptions) {
    options.value = { ...options.value, ...newOptions };
    fetchOrders(); // Chiamare la funzione per aggiornare i dati
}

const debouncedFetchOrders = debounce(fetchOrders, 500);

// Aggiungi un watcher per osservare i cambiamenti nei campi di ricerca
watch(
    () => searchFields.value.map(field => field.value), // Osserva i valori dei campi di ricerca
    () => {
        debouncedFetchOrders(); // Chiamata debounce quando un valore cambia
    }
);
</script>
