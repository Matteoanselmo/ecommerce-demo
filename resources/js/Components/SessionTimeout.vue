<template>
    <div>
    <!-- Modale che avvisa della scadenza della sessione -->
    <v-dialog v-model="sessionExpired" persistent max-width="400">
        <v-card>
        <v-card-title class="headline">Sessione Scaduta</v-card-title>
        <v-card-text>
            La tua sessione è scaduta. Sarai reindirizzato alla pagina di login.
        </v-card-text>
        <v-card-actions>
            <v-btn color="primary" @click="goToLogin">
            Torna al login
            </v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>
    </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { router, usePage } from '@inertiajs/vue3';

const sessionExpired = ref(false);
const { props } = usePage(); // Ottenere i dati dalla pagina tramite Inertia.js

// Funzione per controllare lo stato della sessione
const checkSession = () => {
    if (!props.auth.user) {
    sessionExpired.value = true; // La sessione è scaduta
    }
};

// Esegui il controllo al montaggio del componente
onMounted(() => {
    checkSession();
});

// Se vuoi monitorare cambiamenti in props.auth.user dinamicamente
watch(() => props.auth.user, (newValue) => {
    if (!newValue) {
    sessionExpired.value = true; // La sessione è scaduta
    }
});

// Funzione per reindirizzare al login
const goToLogin = () => {
    router.visit('/login');
};
</script>

<style scoped>
/* Stile personalizzato, se necessario */
</style>
