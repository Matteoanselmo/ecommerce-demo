<template>
    <v-icon
        :color="isWished ? 'red' : 'grey'"
        size="36"
        @click="handleWishlist"
    >
    mdi-heart
    </v-icon>

    <CheckAuthDialog v-model="loginPrompt" :text="' Effettua l\'accesso per aggiungere questo prodotto alla tua lista dei desideri.'" />

</template>

<script setup>
import { ref, computed } from "vue";
import { usePage, Link } from "@inertiajs/vue3";
import axios from "axios";
import CheckAuthDialog from "../CheckAuthDialog.vue";
import { useNotificationStore } from "@/stores/notification.store";

const props = defineProps({
    productId: {
        type: Number,
        required: true,
    },
});

// Stato del cuore e popup di login
const isWished = ref(false);
const loginPrompt = ref(false);
const notificationStore = useNotificationStore();
// Verifica se l'utente è loggato
const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth.user);

// Funzione per verificare se il prodotto è nella wishlist
function checkWishlistStatus() {
    if (isAuthenticated.value) {
        axios.get(`/api/exist/wishlist/${props.productId}`)
            .then(response => {
                isWished.value = response.data;

            })
            .catch(error => {
                console.error("Errore nella verifica della wishlist:", error);
            });
    } else {
        isWished.value = false;
    }
}

function handleWishlist() {
    if (!isAuthenticated.value) {
    // Mostra il popup per chiedere l'accesso
        loginPrompt.value = true;
    } else {
        // Aggiungi o rimuovi dalla wishlist
        if (!isWished.value) {
            axios.post("/api/wishlist", { product_id: props.productId })
            .then((res) => {
                isWished.value = true;
                notificationStore.notify(res.data.message, res.data.color)
                console.log(res)
            })
            .catch(error => {
                console.error("Errore nell'aggiunta alla wishlist:", error);
                notificationStore.notify("Errore nell'aggiunta alla wishlist:", "danger")
            });
        } else {
            axios.delete(`/api/wishlist/${props.productId}`)
            .then((res) => {
                isWished.value = false;
                notificationStore.notify(res.data.message, res.data.color)
            })
            .catch(error => {
                notificationStore.notify("Errore nella rimozione dalla wishlist:", "danger")
                console.error(error);
            });
    }
    }
}

checkWishlistStatus();
</script>

<style scoped>
/* Stili aggiuntivi */
</style>
