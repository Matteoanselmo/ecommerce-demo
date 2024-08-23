<template>
    <v-switch v-model="isDarkMode" >
        <template v-slot:label >
            <v-icon v-if="isDarkMode">mdi-weather-night</v-icon>
            <v-icon v-else>mdi-white-balance-sunny</v-icon>
        </template>
    </v-switch>
</template>

<script setup>
import { ref, computed, watch, defineEmits } from 'vue';
import { useTheme } from 'vuetify';

// Definire gli eventi che il componente può emettere
const emit = defineEmits(['update:theme']);

const theme = useTheme();
const isDarkMode = ref(false);

const currentTheme = computed(() => {
    return isDarkMode.value ? 'myCustomThemeDark' : 'myCustomTheme';
});

// Guarda il cambiamento di isDarkMode e aggiorna il tema
watch(isDarkMode, (newValue) => {
    theme.global.name.value = currentTheme.value;
});
</script>
