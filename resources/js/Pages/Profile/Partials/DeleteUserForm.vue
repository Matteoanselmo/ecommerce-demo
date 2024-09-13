<script setup>
import { ref, nextTick } from 'vue';
import { useForm } from '@inertiajs/vue3';

const confirmingUserDeletion = ref(false);
const passwordInput = ref(null);

const form = useForm({
password: '',
});

const confirmUserDeletion = () => {
confirmingUserDeletion.value = true;

nextTick(() => passwordInput.value.focus());
};

const deleteUser = () => {
form.delete(route('profile.destroy'), {
    preserveScroll: true,
    onSuccess: () => closeModal(),
    onError: () => passwordInput.value.focus(),
    onFinish: () => form.reset(),
});
};

const closeModal = () => {
confirmingUserDeletion.value = false;
form.reset();
};
</script>

<template>
<section class="space-y-6">
    <header>
    <h2 class="text-lg font-medium text-gray-900">Elimina Account</h2>
    <p class="mt-1 text-sm text-gray-600">
        Una volta eliminato il tuo account, tutte le sue risorse e i suoi dati verranno eliminati definitivamente. Prima di eliminare
        il tuo account, scarica tutti i dati o le informazioni che desideri conservare.
    </p>
    </header>

    <v-btn color="red" @click="confirmUserDeletion" depressed>Elimina Account</v-btn>

    <v-dialog v-model="confirmingUserDeletion" max-width="500px">
    <template v-slot:default>
        <v-card>
        <v-card-title>
            <h2 class="text-lg font-medium text-gray-900">
                Sei sicuro di voler eliminare il tuo account?
            </h2>
        </v-card-title>

        <v-card-text>
            <p class="mt-1 text-sm text-gray-600">
                Una volta eliminato il tuo account, tutte le sue risorse e i suoi dati verranno eliminati definitivamente. Per favore
                inserisci la tua password per confermare che desideri eliminare definitivamente il tuo account.
            </p>

            <v-text-field
            v-model="form.password"
            ref="passwordInput"
            label="Password"
            type="password"
            placeholder="Password"
            :error-messages="form.errors.password"
            @keyup.enter="deleteUser"
            class="mt-4"
            />
        </v-card-text>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn text @click="closeModal">Annulla</v-btn>
            <v-btn color="red" :loading="form.processing" @click="deleteUser">Elimina Account</v-btn>
        </v-card-actions>
        </v-card>
    </template>
    </v-dialog>
</section>
</template>
