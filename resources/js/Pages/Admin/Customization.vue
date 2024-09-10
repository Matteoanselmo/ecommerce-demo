<template>
    <v-container>
    <v-row>
        <v-col cols="12">
        <h1 class="text-h4 mb-4">Gestione Personalizzazioni</h1>

        <v-tabs v-model="activeTab" align-with-title>
            <v-tab value="discounts">Sconti</v-tab>
            <v-tab value="info">Info</v-tab>
            <v-tab value="returns">Resi</v-tab>
            <v-tab value="prices">Prezzi</v-tab>
        </v-tabs>
        <v-card-text>
            <v-tabs-window v-model="activeTab">
                <!-- Tab Sconti -->
                <v-tabs-window-item value="discounts">
                <v-card class="mt-4">
                    <v-card-title>* Le immagini selezionate saranno riportate nel carosello della Home</v-card-title>
                    <v-card-text >
                    <v-form>
                        <v-file-input
                        variant="solo"
                        v-model="discountImages"
                        label="Seleziona Immagini"
                        multiple
                        accept="image/*"
                        ></v-file-input>
                        <v-btn color="primary" @click="uploadDiscountImages">
                        Carica Immagini
                        </v-btn>
                    </v-form>
                    </v-card-text>
                </v-card>
                </v-tabs-window-item>

                <!-- Tab Info -->
                <v-tabs-window-item value="info">
                <v-card class="mt-4">
                    <v-card-title>Informazioni</v-card-title>
                    <v-card-text>
                    <h2 class="text-h6">Modifica le informazioni</h2>
                    <v-form>
                        <v-text-field
                        v-model="infoText"
                        label="Informazioni"
                        placeholder="Cerchi aiuto? 0832 156 0529 | Lun - Sab: 09.00 - 20.00 | Spedizione GRATIS sopra i 490 euro."
                        type="text"
                        rows="4"
                        variant="solo"
                        ></v-text-field>
                        <v-btn color="primary" @click="saveInfo">
                        Salva Informazioni
                        </v-btn>
                    </v-form>
                    </v-card-text>
                </v-card>
                </v-tabs-window-item>

                <!-- Tab Resi -->
                <v-tabs-window-item value="returns">
                <v-card class="mt-4">
                    <v-card-title>Resi</v-card-title>
                    <v-card-text>
                    <v-form>
                        <h2 class="text-h6">Carica PDF per i resi</h2>
                        <v-file-input
                        v-model="returnsPdf"
                        label="Seleziona PDF"
                        accept="application/pdf"
                        variant="solo"
                        ></v-file-input>
                        <v-btn color="primary" @click="uploadReturnsPdf">
                        Carica PDF
                        </v-btn>
                    </v-form>
                    </v-card-text>
                </v-card>
                </v-tabs-window-item>

                <!-- Tab Prezzi -->
                <v-tabs-window-item value="prices">
                <v-card class="mt-4">
                    <v-card-title>Prezzi</v-card-title>
                    <v-card-text>
                    <v-form>
                        <h2 class="text-h6">Carica PDF per i prezzi</h2>
                        <v-file-input
                        v-model="pricesPdf"
                        label="Seleziona PDF"
                        accept="application/pdf"
                        variant="solo"
                        ></v-file-input>
                        <v-btn color="primary" @click="uploadPricesPdf">
                        Carica PDF
                        </v-btn>
                    </v-form>
                    </v-card-text>
                </v-card>
                </v-tabs-window-item>
            </v-tabs-window>
        </v-card-text>
        </v-col>
    </v-row>
    </v-container>
</template>

<script setup>
import { ref } from "vue";

// State per la gestione dei tab
const activeTab = ref("discounts");

// State per gestire i form nei vari tab
const discountImages = ref([]);
const infoText = ref("Cerchi aiuto? 0832 156 0529 | Lun - Sab: 09.00 - 20.00 | Spedizione GRATIS sopra i 490 euro.");
const returnsPdf = ref(null);
const pricesPdf = ref(null);

// Funzioni placeholder per le operazioni
function uploadDiscountImages() {
    // Gestisci il caricamento delle immagini
    console.log("Caricamento immagini per sconti:", discountImages.value);
    // Esegui chiamate API per inviare i file a Laravel
}

function saveInfo() {
    // Salva le informazioni
    console.log("Salvataggio delle informazioni:", infoText.value);
    // Esegui chiamate API per salvare le informazioni
}

function uploadReturnsPdf() {
    // Carica il PDF dei resi
    console.log("Caricamento PDF resi:", returnsPdf.value);
    // Esegui chiamate API per inviare il PDF a Laravel
}

function uploadPricesPdf() {
    // Carica il PDF dei prezzi
    console.log("Caricamento PDF prezzi:", pricesPdf.value);
    // Esegui chiamate API per inviare il PDF a Laravel
}
</script>

<style scoped>
.product-detail-container {
    padding-top: 20px;
}
</style>
