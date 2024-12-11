<template>
    <v-app>
        <v-main class="login-background">
            <Head title="Authentication" />
            <v-container class="mt-10">
                <v-card class="mx-auto px-5 py-5" max-width="500" rounded="xl" elevation="18">
                    <v-tabs v-model="activeTab" background-color="primary" dark>
                        <v-tab value="login">Login</v-tab>
                        <v-tab value="register">Registrati</v-tab>
                    </v-tabs>

                    <v-card-text>
                        <v-tabs-window v-model="activeTab">
                            <!-- Login Tab -->
                            <v-tabs-window-item value="login">
                                    <div
                                        v-if="status"
                                        class="mb-4 font-medium text-sm text-green-600"
                                    >
                                        {{ status }}
                                    </div>
                                    <form @submit.prevent="submitLogin" class="mb-3">
                                        <v-text-field
                                            v-model="loginForm.email"
                                            label="Email"
                                            type="email"
                                            required
                                            :error-messages="
                                                loginForm.errors.email ? [loginForm.errors.email] : []
                                            "
                                            autofocus
                                        ></v-text-field>

                                        <v-text-field
                                            v-model="loginForm.password"
                                            label="Password"
                                            :type="showPassword ? 'text' : 'password'"
                                            required
                                            :append-icon="
                                                showPassword ? 'mdi-eye' : 'mdi-eye-off'
                                            "
                                            @click:append="showPassword = !showPassword"
                                            @keydown.enter.prevent="submitLogin"
                                            :error-messages="
                                                loginForm.errors.password
                                                    ? [loginForm.errors.password]
                                                    : []
                                            "
                                        ></v-text-field>

                                        <v-checkbox
                                            v-model="loginForm.remember"
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
                                                :disabled="loginForm.processing"
                                                :loading="loginForm.processing"
                                                type="submit"
                                            >
                                                Log in
                                            </v-btn>
                                        </div>
                                    </form>
                                    <div class="text-h6 mb-3">Oppure</div>
                                    <a href="/auth/google" class="v-btn v-btn--elevated v-theme--myCustomTheme v-btn--density-default v-btn--size-default v-btn--variant-elevated mb-4"><span class="mdi mdi-google"></span> continua con Google</a>
                            </v-tabs-window-item>
                            <!-- Register Tab -->
                            <v-tabs-window-item value="register" >
                                    <v-form @submit.prevent="submitRegister"  >
                                        <v-text-field
                                            v-model="registerForm.name"
                                            label="Nome"
                                            type="text"
                                            required
                                            :error-messages="
                                                registerForm.errors.name ? [registerForm.errors.name] : []
                                            "
                                        ></v-text-field>

                                        <v-text-field
                                            v-model="registerForm.email"
                                            label="Email"
                                            type="email"
                                            required
                                            :error-messages="
                                                registerForm.errors.email ? [registerForm.errors.email] : []
                                            "
                                        ></v-text-field>

                                        <v-text-field
                                            v-model="registerForm.password"
                                            label="Password"
                                            type="password"
                                            required
                                            :error-messages="
                                                registerForm.errors.password ? [registerForm.errors.password] : []
                                            "
                                        ></v-text-field>

                                        <v-text-field
                                            v-model="registerForm.password_confirmation"
                                            label="Conferma Password"
                                            type="password"
                                            required
                                            :error-messages="
                                                registerForm.errors.password_confirmation
                                                    ? [registerForm.errors.password_confirmation]
                                                    : []
                                            "
                                        ></v-text-field>

                                        <div class="d-flex justify-end ">
                                            <v-btn
                                                class="ms-4 mb-4"
                                                :disabled="registerForm.processing"
                                                :loading="registerForm.processing"
                                            >
                                                Registrati
                                            </v-btn>
                                        </div>
                                    </v-form>
                                    <div class="text-h6 mb-3">Oppure</div>
                                    <a href="/auth/google" class="v-btn v-btn--elevated v-theme--myCustomTheme v-btn--density-default v-btn--size-default v-btn--variant-elevated mb-4"><span class="mdi mdi-google"></span> Registrati con Google</a>
                            </v-tabs-window-item>
                        </v-tabs-window>
                    </v-card-text>
                </v-card>
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref } from "vue";
import { useForm, Head, Link } from "@inertiajs/vue3";

const props = defineProps({
    canResetPassword: Boolean,
    status: String,
});

const activeTab = ref(0);
const showPassword = ref(false);

// Login form logic
const loginForm = useForm({
    email: "",
    password: "",
    remember: false,
});

const submitLogin = () => {
    loginForm.post(route("login"), {
        onError: (errors) => {
            console.log(errors); // Log degli errori dal server
        },
        onFinish: () => loginForm.reset("password"),
    });
};

// Register form logic
const registerForm = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submitRegister = () => {
    registerForm.post(route("register"), {
        onFinish: () => registerForm.reset("password", "password_confirmation"),
    });
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
