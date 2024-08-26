<template>
    <v-container>
    <v-row>
        <!-- Bottone per aprire/chiudere il pannello dei filtri -->
        <v-btn
        color="primary"
        @click="expand = !expand"
        class="mb-4"
        variant="outlined"
        >
        {{ expand ? 'Filtri' : 'Filtri' }}
        </v-btn>

        <!-- Pannello laterale che si espande orizzontalmente -->
        <v-expand-transition max-width="800">
        <v-col v-if="expand" class="sidebar-panel">
            <v-card class="py-4 px-3">
            <v-toolbar flat>
                <v-toolbar-title>Filtri</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon @click="expand = false">
                <v-icon>mdi-close</v-icon>
                </v-btn>
            </v-toolbar>

            <v-divider></v-divider>

            <!-- Sezione Categorie -->
            <v-list dense>
                <v-subheader>Categorie</v-subheader>
                <CategoryCards />
            </v-list>

            <v-divider class="my-4"></v-divider>

            <!-- Sezione Filtri -->
            <v-expansion-panels>
                <v-expansion-panel v-for="(filter, index) in filterOptions" :key="index">
                <v-expansion-panel-title>{{ filter.name }}</v-expansion-panel-title>
                <v-expansion-panel-text>
                    <!-- Contenuto dinamico dei filtri -->
                </v-expansion-panel-text>
                </v-expansion-panel>
            </v-expansion-panels>
            </v-card>
        </v-col>
        </v-expand-transition>
    </v-row>
    </v-container>
</template>

<script setup>
import { ref } from 'vue';
import CategoryCards from './CategoryCards.vue';

const expand = ref(false);
const filterOptions = ref([
    { name: 'Prezzo' },
    { name: 'Tipologia' },
]);
</script>

<style scoped>
.sidebar-panel {
    max-width: 300px;
}

.v-toolbar-title {
    font-weight: bold;
}
</style>
