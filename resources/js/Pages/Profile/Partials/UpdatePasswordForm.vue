<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const passwordInput = ref(null);
const currentPasswordInput = ref(null);

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
});

const updatePassword = () => {
    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation');
                passwordInput.value.focus();
            }
            if (form.errors.current_password) {
                form.reset('current_password');
                currentPasswordInput.value.focus();
            }
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">Aggiorna Password</h2>
            <p class="mt-1 text-sm text-gray-600">
                Assicurati che il tuo account utilizzi una password lunga e casuale per rimanere sicuro.
            </p>
        </header>

        <form @submit.prevent="updatePassword" class="mt-6 space-y-6">
            <div>
                <v-text-field
                    v-model="form.current_password"
                    ref="currentPasswordInput"
                    label="Current Password"
                    type="password"
                    :error-messages="form.errors.current_password"
                    autocomplete="current-password"
                    class="mt-1"
                />
            </div>

            <div>
                <v-text-field
                    v-model="form.password"
                    ref="passwordInput"
                    label="New Password"
                    type="password"
                    :error-messages="form.errors.password"
                    autocomplete="new-password"
                    class="mt-1"
                />
            </div>

            <div>
                <v-text-field
                    v-model="form.password_confirmation"
                    label="Confirm Password"
                    type="password"
                    :error-messages="form.errors.password_confirmation"
                    autocomplete="new-password"
                    class="mt-1"
                />
            </div>

            <div class="flex items-center gap-4">
                <v-btn :loading="form.processing" color="primary" type="submit">
                    Save
                </v-btn>

                <v-fade-transition>
                    <span v-if="form.recentlySuccessful" class="text-sm text-gray-600">Saved.</span>
                </v-fade-transition>
            </div>
        </form>
    </section>
</template>
