<template>
    <v-container fluid class="newsletter-container bg-primary" data-aos="fade-down" >
        <v-row class="d-flex justify-center align-center" >
            <v-col
                cols="12"
                md="6"
                class="d-flex justify-center align-center newsletter-content"
            >
                <div class="text-content">
                    <h2 class="text-white">
                        ISCRIVITI ALLA NOSTRA NEWSLETTER !
                    </h2>
                </div>
            </v-col>
            <v-col cols="12" md="6" class="text-center">
                <v-form v-model="valid">
                    <div class="d-flex align-center justify-center">
                        <v-text-field
                            v-model="email"
                            placeholder="Inserisci la tua email"
                            hide-details
                            required
                            class="text-end"
                            variant="solo"
                            bg-color="black"
                            :loading="loading"
                            elevation="0"
                        >
                            <v-btn
                                @click="subscribe"
                                color="primary"
                                class="subscribe-btn me-3 aling-end"
                                :disabled="!checkbox || email === ''"
                                >Iscrivimi</v-btn
                            >
                        </v-text-field>
                    </div>
                    <v-checkbox
                        class="text-white"
                        v-model="checkbox"
                        label="Acconsento al trattamento dei dati"
                        required
                    ></v-checkbox>
                </v-form>
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { ref } from "vue";
import { useNotificationStore } from "@/stores/notification.store";
// Store per notifiche
const notificationStore = useNotificationStore();
const email = ref("");
const valid = ref(false);
const checkbox = ref(false);
const loading = ref(false);

function subscribe (){
    if (email.value) {
        loading.value = true;
        axios.post('/api/subscribe', {
            'email' : email.value
        }).then((res) => {
            loading.value= false;
            notificationStore.notify(res.data.message, res.data.color);
            email.value = "";
        }).catch((err) => {
            loading.value= false;
            notificationStore.notify(res.data.message, res.data.color);
        })
    } else {
        console.log("Email is required");
    }
};
</script>

<style>
.newsletter-content {
    padding: 20px;
    border-radius: 8px;
    /* background: rgba(0, 0, 0, 0.7); */
    /* backdrop-filter: blur(10px); */
    max-width: 900px;
}
.subscribe-btn {
    margin-left: 8px;
    border-radius: 4px;
    min-width: 150px;
}
.privacy-text {
    margin-top: 10px;
}
.privacy-link {
    color: #42a5f5;
    text-decoration: none;
}
.privacy-link:hover {
    text-decoration: underline;
}
</style>
