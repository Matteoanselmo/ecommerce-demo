<template>
    <Head title="Personalizzazione" />
    <v-container fluid>
        <v-row>
        <v-col cols="12">

            <v-tabs v-model="activeTab" align-with-title>
            <v-tab value="discounts">Sconti</v-tab>
            <v-tab value="info">Info</v-tab>
            <v-tab value="returns">Resi</v-tab>
            <v-tab value="prices">Prezzi</v-tab>
            </v-tabs>
            <v-card-text>
            <v-tabs-window v-model="activeTab">
                <!-- Tab Sconti -->
                <v-tabs-window-item value="discounts">
                <FileUploader endpoint="/api/discount-banner" fileType="image" />
                </v-tabs-window-item>

                <!-- Tab Info -->
                <v-tabs-window-item value="info">
                <FileUploader endpoint="/api/info-policies" fileType="pdf" />
                </v-tabs-window-item>

                <!-- Tab Resi -->
                <v-tabs-window-item value="returns">
                <FileUploader endpoint="/api/return-policies" fileType="pdf" />
                </v-tabs-window-item>

                <!-- Tab Prezzi -->
                <v-tabs-window-item value="prices">
                <FileUploader endpoint="/api/price-policies" fileType="pdf" />
                </v-tabs-window-item>
            </v-tabs-window>
            </v-card-text>
        </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import FileUploader from '@/Components/FileUploader.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';

const activeTab = ref("discounts");
</script>
