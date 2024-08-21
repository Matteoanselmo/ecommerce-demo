<script setup>
import { computed } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';

const props = usePage().props;


console.log(props)
const statusCode = props.statusCode;
const message = props.message;

// Titolo dinamico basato sul codice di errore
const errorTitle = computed(() => {
    switch (statusCode) {
    case 404:
        return 'Page not found';
    case 500:
        return 'Server error';
    default:
        return 'Error occurred';
    }
});
</script>
<template>
    <Head title="Whoops" />
    <v-container class="d-flex justify-center align-center">
    <v-empty-state
        :headline="`Whoops, ${statusCode}`"
        :title="errorTitle"
    />
    </v-container>
</template>


