<template>
    <v-snackbar
    v-model="notification.show"
    :color="notification.color"
    location="bottom"
    :timeout="notification.timeout"
    elevation="2"
    >
    {{ notification.message }}
    <template #actions>
        <v-btn icon @click="show = false">
        <v-icon>mdi-close</v-icon>
        </v-btn>
    </template>
    </v-snackbar>
</template>

<script setup>
import { useNotificationStore } from '@/stores/notification.store';
const notification = useNotificationStore();
</script>
