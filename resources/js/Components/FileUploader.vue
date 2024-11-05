<template>
    <v-card class="mt-4" color="transparent">
    <v-card-title v-if="fileType === 'image'">
        * L'immagine sarà riportata nel banner sconti della Home
    </v-card-title>
    <v-card-text>
        <v-container fluid class="px-0">
        <v-row>
            <!-- Visualizzazione File -->
            <v-col :cols="fileType === 'image' ? 12 : 4" v-for="(file, i) in filesArray" :key="i">
                <v-skeleton-loader
                    v-if="loading"
                    class="mx-auto"
                    elevation="12"
                    max-width="400"
                    type="card, image"
                >
                </v-skeleton-loader>
            <v-card elevation="0" v-else>
                <v-img
                v-if="fileType === 'image'"
                width="100%"
                :src="file.image_path"
                height="200"
                >
                </v-img>
                <v-card-title>{{ file.title }}</v-card-title>
                <v-card-actions>
                <v-btn
                    icon
                    @click="downloadFile(file.file_path)"
                    color="primary"
                    v-if="fileType === 'pdf'"
                >
                    <v-icon>mdi-download</v-icon>
                </v-btn>
                <v-btn
                    icon
                    @click="deleteFile(file.id)"
                    color="error"
                >
                    <v-icon>mdi-delete</v-icon>
                </v-btn>
                </v-card-actions>
            </v-card>
            </v-col>
        </v-row>
        </v-container>

        <!-- Upload File -->
        <v-form>
        <v-file-input
            :prepend-icon="fileType === 'image' ? 'mdi-camera' : 'mdi-paperclip'"
            variant="solo-inverted"
            v-model="fileInput"
            :label="`Seleziona ${fileType === 'image' ? 'Immagini' : 'PDF'}`"
            :loading="loading"
            :accept="fileType === 'image' ? 'image/*' : 'application/pdf'"
            :show-size="true"
        />
            <v-btn color="primary" type="submit" @click="uploadFile">
                Carica {{ fileType === 'image' ? 'Immagini' : 'PDF' }}
            </v-btn>
        </v-form>
    </v-card-text>
    </v-card>
</template>

<script setup>
import { ref } from "vue";
import axios from "axios";
import { useNotificationStore } from "@/stores/notification.store";

const props = defineProps({
    endpoint: String,  // L'endpoint per l'API
    fileType: {        // Il tipo di file: 'image' o 'pdf'
        type: String,
        default: 'image',
    }
});

// Store per notifiche
const notificationStore = useNotificationStore();
const loading = ref(false);

// State per i file e l'input
const fileInput = ref([]);
const filesArray = ref([]);

// Funzione per caricare file
function uploadFile() {
    if (!fileInput.value) return;
    loading.value = true;

    const formData = new FormData();
    formData.append(props.fileType === 'image' ? 'image' : 'file', fileInput.value);
    axios.post(props.endpoint, formData, {
    headers: { 'Content-Type': 'multipart/form-data' }
    })
    .then(res => {
        loading.value = false;
        notificationStore.notify(res.data.message, 'success');
        fetchFiles();  // Ricarica la lista dei file
        fileInput.value = null;
    })
    .catch(error => {
        loading.value = false;
        notificationStore.notify(error.response.data.message, 'error');
    });
}

// Funzione per ottenere i file
function fetchFiles() {
    axios.get(props.endpoint)
    .then(res => {
        filesArray.value = res.data;
        console.log(res.data)
    })
    .catch(error => {
        console.error(error);
    });
}

// Funzione per cancellare un file
function deleteFile(id) {
    loading.value = true;
    if (confirm(`Sei sicuro di voler eliminare questo ${props.fileType}?`)) {
    axios.delete(`${props.endpoint}/${id}`)
        .then(res => {
            loading.value=false;
            notificationStore.notify(res.data.message, 'success');
            fetchFiles();
        })
        .catch(error => {
            loading.value=false;
            notificationStore.notify(error.response.data.message, 'error');
        });
    }
}

// Funzione per scaricare file (solo PDF)
function downloadFile(filePath) {
    const link = document.createElement('a');
    link.href = `/storage/${filePath}`;
    link.download = filePath.split('/').pop();
    link.click();
}

// Richiama i file inizialmente
fetchFiles();
</script>
