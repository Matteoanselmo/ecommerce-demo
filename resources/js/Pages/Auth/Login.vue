<script setup>
import { ref } from "vue";
import { useForm, Head, Link, router } from "@inertiajs/vue3";

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
    });
};

const showPassword = ref(false);
</script>

<template>
    <v-app>
        <v-main>
            <Head title="Log in" />
            <v-container class="mt-10">
                <v-card class="mx-auto px-5 py-5" max-width="400" rounded="xl" elevation="18">
                    <v-card-title> Log in </v-card-title>

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

                            <v-text-field
                                v-model="form.password"
                                label="Password"
                                :type="showPassword ? 'text' : 'password'"
                                required
                                :append-icon="
                                    showPassword ? 'mdi-eye' : 'mdi-eye-off'
                                "
                                @click:append="showPassword = !showPassword"
                                :error-messages="
                                    form.errors.password
                                        ? [form.errors.password]
                                        : []
                                "
                            ></v-text-field>

                            <v-checkbox
                                v-model="form.remember"
                                label="Remember me"
                                class="mt-4"
                            ></v-checkbox>

                            <div class="d-flex justify-end mt-4">
                                <Link
                                    v-if="canResetPassword"
                                    :href="route('password.request')"
                                    as="button"
                                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    Forgot your password?
                                </Link>

                                <v-btn
                                    class="ms-4"
                                    :disabled="form.processing"
                                    :loading="form.processing"
                                    type="submit"
                                >
                                    Log in
                                </v-btn>
                            </div>
                        </form>
                        <div class="text-h6 mb-3">Oppure</div>
                        <a href="/auth/google" class="v-btn v-btn--elevated v-theme--myCustomTheme v-btn--density-default v-btn--size-default v-btn--variant-elevated"><span class="mdi mdi-google"></span> continua con Goole</a>
                    </v-card-text>
                </v-card>

            </v-container>
        </v-main>
    </v-app>
</template>

<style scoped>
.v-main {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
}
</style>
