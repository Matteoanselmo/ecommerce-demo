<template>
    <v-switch v-model="isDarkMode">
        <template v-slot:label>
            <v-icon v-if="isDarkMode">mdi-weather-night</v-icon>
            <v-icon v-else>mdi-white-balance-sunny</v-icon>
        </template>
    </v-switch>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue';
import { useTheme } from 'vuetify';

const emit = defineEmits(['update:theme']);
const theme = useTheme();

// Inizializza isDarkMode in base al tema globale al montaggio del componente
const isDarkMode = ref(false);

onMounted(() => {
    isDarkMode.value = theme.global.name.value === 'myCustomThemeDark';
});

// Guarda il cambiamento di isDarkMode e aggiorna il tema globale
watch(isDarkMode, (newValue) => {
    theme.global.name.value = newValue ? 'myCustomThemeDark' : 'myCustomTheme';
    emit('update:theme', theme.global.name.value);
});
</script>
