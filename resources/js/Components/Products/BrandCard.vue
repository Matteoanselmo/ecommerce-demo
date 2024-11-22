<template>
    <v-card  rounded="xl" elevation="0">
        <v-card-title class="d-flex justify-space-between">
            <span>Brands</span>
            <v-btn size="small" color="info" icon="mdi-plus" @click="dialog = true, selectedBrand = undefined"></v-btn>
        </v-card-title>
        <v-card-text>
            <v-chip-group filter v-model="selectedBrand" prepend-icon="mdi-check" @update:modelValue="console.log(selectedBrand)" >
                <v-chip v-for="(brand, i) in brands" :key="i" :value="brand.name">
                    {{ brand.name }}
                </v-chip>
            </v-chip-group>
        </v-card-text>
        <v-card-actions class="justify-end" :disabled="selectedBrand === ''">
            <v-btn color="warning" :disabled="selectedBrand === undefined"   append-icon="mdi-pen" @click="dialog = true"></v-btn>
            <v-btn color="danger"  :disabled="selectedBrand === undefined" append-icon="mdi-delete"></v-btn>
        </v-card-actions>
    </v-card>
    <v-dialog
        v-model="dialog"
        max-width="600"
        >
        <v-card
            :title="selectedBrand !== undefined ? 'Aggiorna il Brand ' + selectedBrand : 'Crea Nuovo Brand'"
            rounded="xl"
        >
        <v-card-text>
            <v-text-field
            v-model="selectedBrand"
            hide-details="auto"
            label="Nome Brand"
            ></v-text-field>
        </v-card-text>
        <v-card-actions class="justify-end">
            <v-btn
                :append-icon="selectedBrand !== undefined ? 'mdi-pen' : 'mdi-check'"
                :color="selectedBrand !== undefined ? 'warning' : 'success'"
                @click="dialog = false"
            ></v-btn>
            <v-btn
                append-icon="mdi-close"
                color="danger"
                @click="dialog = false"
            ></v-btn>
        </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script setup>
import { ref } from 'vue';
const selectedBrand = ref(undefined)
const dialog = ref(false)
const props = defineProps({
    brands: {
        type: Array,
        required: true
    }
});

</script>
