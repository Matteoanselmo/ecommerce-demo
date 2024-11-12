<template>
    <v-dialog v-model="show" max-width="600">
    <v-card rounded="xl">
        <v-card-title class="text-h6">Accedi per continuare</v-card-title>
        <v-card-text>
            {{ props.text }}
        </v-card-text>
        <v-card-actions>
        <v-spacer></v-spacer>
        <Link :href="route('login')">
            <v-btn color="primary" append-icon="mdi-login">Accedi</v-btn>
        </Link>
        <v-btn color="danger" @click="closeDialog" append-icon="mdi-close">Chiudi</v-btn>
        </v-card-actions>
    </v-card>
    </v-dialog>
</template>

<script setup>
import { ref, defineProps, defineEmits, watch } from "vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
    modelValue: {
        type: Boolean,
        required: true,
    },
    text: {
        type: String,
        default: "Per completare questa operazione devi prima effettuare l'accesso"
    }
});

const emit = defineEmits(["update:modelValue"]);

const show = ref(props.modelValue);

function closeDialog() {
    show.value = false;
    emit("update:modelValue", false);
}

// Sincronizza il valore di `show` con `modelValue`
watch(
    () => props.modelValue,
    (newValue) => {
    show.value = newValue;
    }
);
</script>

<style scoped>
/* Stili aggiuntivi */
</style>
