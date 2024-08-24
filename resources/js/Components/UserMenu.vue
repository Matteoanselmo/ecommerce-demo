<script setup>
import { ref } from 'vue';
import { usePage, Link } from '@inertiajs/vue3';

const menu = ref(false);

// Accesso ai dati condivisi globalmente
const page = usePage();
const user = page.props.auth.user;

// Methods to handle login/logout/dashboard navigation
const login = () => {
    // Redirect to login page
    window.location.href = route('login');
};


const goToDashboard = () => {
    // Redirect to dashboard
    if(user.role === 'admin'){
        window.location.href = route('admin.dashboard');
    } else if(user.role === 'user'){
        window.location.href = route('dashboard');
    } else {
        window.location.href = route('home');
    }
};

console.log(user)
</script>

<template>
    <v-menu
        v-model="menu"
        offset-y
        origin="top right"
        transition="scale-transition"
        close-on-click
        :nudge-width="200"
    >
        <template v-slot:activator="{ props }">
            <v-btn
                icon
                v-bind="props"
            >
                <v-icon>{{ user ? 'mdi-account-circle' : 'mdi-login' }}</v-icon>
            </v-btn>
        </template>

        <v-list>
            <template v-if="!user">
                <v-list-item @click="login">
                    <v-list-item-title>Login</v-list-item-title>
                </v-list-item>
            </template>
            <template v-else>
                <v-list-item @click="goToDashboard">
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item>
                <v-list-item>
                    <Link
                        method="POST"
                        as="button"
                        class="text-black"
                        :href="route('logout')"
                        @click="user = null"
                    >
                    Logout
                    </Link>
                </v-list-item>
            </template>
        </v-list>
    </v-menu>
</template>
