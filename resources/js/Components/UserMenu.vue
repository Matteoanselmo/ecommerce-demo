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
        window.location.href = route('user.dashboard');
    } else {
        window.location.href = route('home');
    }
};
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

        <v-list rounded="xl">
            <template v-if="!user">
                <v-list-item @click="login">
                    <v-list-item-title>Login</v-list-item-title>
                </v-list-item>
            </template>
            <template v-else>
                <v-list-item >
                    <v-list-item-title><span class="font-weight-bold">Buongiorno, </span> {{ user.name }} </v-list-item-title>
                </v-list-item>
                <v-list-item @click="goToDashboard" prepend-icon="mdi mdi-view-dashboard-outline">
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item>
                <v-list-item prepend-icon="mdi mdi-cog-outline">
                    <Link
                        as="button"
                        :href="route('profile.edit')"
                        @click="user = null"
                    >
                    Impostazioni Account
                    </Link>
                </v-list-item>
                <v-list-item prepend-icon="mdi mdi-logout">
                    <Link
                        method="POST"
                        as="button"
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
