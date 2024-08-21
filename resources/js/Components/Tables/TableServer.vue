<template>
    <v-data-table-server
        :items-per-page="itemsPerPage"
        :headers="headers"
        :items="items"
        :items-length="totalItems"
        :loading="loading"
        :search="search"
        loading-text="Caricamento in corso..."
        item-value="name"
        @update:options="handleOptionsUpdate"
    >
        <template v-slot:tfoot>
        <tr>
            <td>
                <v-text-field v-model="name" class="ma-2" density="compact" placeholder="Utente" hide-details></v-text-field>
            </td>
            <td>
                <v-text-field
                    v-model="shippingNumber"
                    class="ma-2"
                    density="compact"
                    placeholder="Spedizione"
                    hide-details
                ></v-text-field>
            </td>
        </tr>
        </template>
    </v-data-table-server>
</template>
<script setup>
// TO-DO riportare tutto su pinia

import { ref, watch } from 'vue'
    const props = defineProps({
    items: {
        type: Array,
        required: true,
    },
    headers: {
        type: Array,
        required: true,
    },
    totalItems: {
        type: Number,
        default: 5
    },
    itemsPerPage: {
        default: 10,
        type: Number
    },
    loading: {
        type: Boolean,
        default: true
    },
    type: {
        type: String,
        required: true
    },
    page: {
        type: Number,
        default: 1
    }
    });
    const name = ref('');
    const shippingNumber = ref('');
    const search = ref('');

    const emit = defineEmits(['updateItems']);

    function handleOptionsUpdate(options) {
        emit('updateItems', {
            ...options,
            search: {
                name: name.value,
                shippingNumber: shippingNumber.value, // Aggiungi il numero di spedizione al payload
            },
        });
    }

    watch(name, (newName) => {
        search.value = newName;
        handleOptionsUpdate({
            page: props.page,
            itemsPerPage: props.itemsPerPage,
        });
    });

    watch([name, shippingNumber], () => {
    handleOptionsUpdate({
        page: props.page,
        itemsPerPage: props.itemsPerPage,
    });
});
</script>
