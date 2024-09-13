<script setup>
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Informazioni sul profilo</h2>
            <p class="mt-1 text-sm text-gray-600">
                Aggiorna le informazioni del profilo e l'indirizzo email del tuo account.
            </p>
        </header>

        <form @submit.prevent="form.patch(route('profile.update'))" class="mt-6 space-y-6">
            <div>
                <v-text-field
                    label="Name"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                    :error-messages="form.errors.name"
                    class="mt-1"
                />
            </div>

            <div>
                <v-text-field
                    label="Email"
                    type="email"
                    v-model="form.email"
                    required
                    autocomplete="username"
                    :error-messages="form.errors.email"
                    class="mt-1"
                />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="text-sm mt-2 text-gray-800">
                    Your email address is unverified.
                    <v-btn
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        text
                        class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    >
                        Fare clic qui per inviare nuovamente l'e-mail di verifica.
                    </v-btn>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 font-medium text-sm text-green-600"
                >
                Un nuovo link di verifica è stato inviato al tuo indirizzo email.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <v-btn :loading="form.processing" color="primary" type="submit">Save</v-btn>

                <v-fade-transition>
                    <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</p>
                </v-fade-transition>
            </div>
        </form>
    </section>
</template>
