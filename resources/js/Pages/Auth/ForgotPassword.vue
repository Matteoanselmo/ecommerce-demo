<template>
    <v-app>
        <v-main class="login-background">
            <Head title="Forgot Password" />
            <v-container class="mt-10">
                <v-card class="mx-auto px-5 py-5" max-width="400" rounded="xl" elevation="18">
                    <v-card-title> Password Dimenticata </v-card-title>

                    <v-card-text>
                        <div
                            v-if="status"
                            class="mb-4 font-medium text-sm text-green-600"
                        >
                            {{ status }}
                        </div>

                        <form @submit.prevent="submit" class="mb-3">
                            <v-text-field
                                v-model="form.email"
                                label="Email"
                                type="email"
                                required
                                :error-messages="
                                    form.errors.email ? [form.errors.email] : []
                                "
                                autofocus
                            ></v-text-field>

                            <div class="d-flex justify-end mt-4">
                                <v-btn
                                    class="ms-4"
                                    :disabled="form.processing"
                                    :loading="form.processing"
                                    type="submit"
                                >
                                    Invia Email di reset
                                </v-btn>
                            </div>
                        </form>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { useForm, Head } from "@inertiajs/vue3";

const props = defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email'));
};
</script>

<style scoped>
.login-background {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: url('/images/background/bg-wave.svg') no-repeat center;
    background-size: cover;
    overflow: hidden;
    position: relative;
}
</style>
