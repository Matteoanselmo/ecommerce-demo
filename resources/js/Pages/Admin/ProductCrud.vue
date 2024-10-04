<template>
    <Head title="Modifica Prodotto" />
    <v-lazy
    :min-height="200"
    :options="{'threshold':0.5}"
    transition="fade-transition"
    >
        <template v-if="loading">
            <v-skeleton-loader
                class="mx-auto my-4"
                max-width="100%"
                type="table"
                height="80vh"
            />
        </template>
        <template v-else>
            <v-container fluid>
                <v-row>
                    <v-col cols="10" class="d-flex align-center justify-start">
                        <div class="text-h3">{{ props.product.name }}</div>
                        <RatingStars
                            :ratings="props.product.rating_star"
                            :readOnly="true"
                        />
                    </v-col>
                    <v-col cols="2" class="d-flex justify-end">
                        <v-checkbox
                            color="warning"
                            v-model="isChange"
                            :label="isChange ? 'Disabilita la modifica' : 'Abilita la modifica'"
                            :messages="isChange ? 'Attenzione MODIFICA attiva!' : ''"
                        >
                        </v-checkbox>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col>
                        <v-form :disabled="!isChange" :loading="loading">
                            <v-text-field label="titolo"  v-model="props.product.name" variant="solo-filled"></v-text-field>
                            <v-textarea label="descrizione"  v-model="props.product.description" variant="solo-filled"></v-textarea>
                            <v-select
                                label="categoria"
                                :items="categories"
                                variant="solo-filled"
                                v-model="props.product.category.name"
                            >
                            </v-select>
                            <VNumberInput
                                :reverse="false"
                                controlVariant="default"
                                v-model="props.product.stock_quantity"
                                label="stock"
                                :hideInput="false"
                                :inset="false"
                                variant="solo-filled"
                            />
                            <v-container>
                                <v-row>
                                    <v-col
                                        v-for="image in props.product.images"
                                        :key="image.id"
                                        class="d-flex child-flex"
                                        cols="4"
                                    >
                                    <v-skeleton-loader
                                        v-if="loading"
                                        class="mx-auto"
                                        elevation="12"
                                        max-width="400"
                                        type="image"
                                    ></v-skeleton-loader>
                                    <v-img
                                        v-else
                                        :src="image.image_url"
                                        aspect-ratio="1"
                                        class="bg-grey-lighten-2 rounded"
                                        elevation="18"
                                        cover
                                    >
                                        <v-btn size="small" color="danger" class="my-5 mx-5" :disabled="!isChange">
                                            <span class="mdi mdi-delete-alert-outline"></span>
                                        </v-btn>
                                    </v-img>
                                    </v-col>
                                </v-row>
                            </v-container>
                            <v-file-input
                            label="Carica Immagini"
                            variant="solo-filled"
                            prepend-icon="mdi-camera"
                            ></v-file-input>
                            <div class="d-flex justify-end">
                                <v-btn color="success" type="submit" class="me-2" :disabled="!isChange">
                                    Salva
                                </v-btn>
                                <v-btn color="info">
                                    Annulla
                                </v-btn>
                            </div>
                        </v-form>
                    </v-col>
                </v-row>
            </v-container>
        </template>
    </v-lazy>
</template>

<script setup>
import { ref } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { VNumberInput } from 'vuetify/labs/VNumberInput'
import RatingStars from '@/Components/Reviews/RatingStars.vue';
const { props } = usePage();
const categories = ref([]);
const isChange = ref(false);
const loading = ref(false);
function fetchCategories(){
    axios.get('/api/categories')
    .then((res) => {
        res.data.forEach(category => {
            categories.value.push(category.name);
        });
        console.log(res.data)
    }).catch((e) => {
        console.error(e)
    })
}

fetchCategories();
console.log(props)
</script>

<style scoped>
/* Aggiungi il tuo stile qui se necessario */
</style>
