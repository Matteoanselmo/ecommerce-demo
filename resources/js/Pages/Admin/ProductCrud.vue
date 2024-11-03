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
                <v-row class="mb-5">
                    <v-col cols="10" class="d-flex align-center justify-start">
                        <div class="text-h3">{{ props.product.name }}</div>
                        <RatingStars
                            :ratings="props.product.rating_star"
                            :readOnly="true"
                        />
                    </v-col>
                    <v-col cols="2" class="d-flex justify-end position-fixed top-50 right-0" style="z-index: 5;">
                        <v-card class="px-2 py-1" rounded="xl">
                            <v-checkbox
                                class="d-flex flex-column align-center justify-center px-2"
                                color="warning"
                                v-model="isChange"
                                :label="isChange ? 'Disabilita la modifica' : 'Abilita la modifica'"
                                :messages="isChange ? 'Attenzione MODIFICA attiva!' : 'Premi per abilitare la modifica'"
                            >
                            </v-checkbox>
                        </v-card>
                    </v-col>
                </v-row>
                <v-row>
                    <v-col cols="8">
                        <v-row>
                            <v-col cols="12">
                                <v-form :disabled="!isChange" :loading="loading">
                                    <v-card class="px-2 py-2" :title="'Prodotto: '+ props.product.name" rounded="xl">
                                        <v-text-field label="titolo"  v-model="props.product.name" variant="solo-filled"></v-text-field>
                                        <v-textarea label="descrizione"  v-model="props.product.description" variant="solo-filled"></v-textarea>
                                        <VNumberInput
                                            :reverse="false"
                                            controlVariant="default"
                                            v-model="props.product.stock_quantity"
                                            label="stock"
                                            :hideInput="false"
                                            :inset="false"
                                            variant="solo-filled"
                                        />
                                        <VNumberInput
                                            :reverse="false"
                                            controlVariant="default"
                                            v-model="props.product.price"
                                            label="prezzo"
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
                                                        <span class="mdi mdi-close"></span>
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
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success" type="submit" class="me-2" :disabled="!isChange">
                                                Salva
                                            </v-btn>
                                            <v-btn color="info">
                                                Annulla
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="4">
                        <v-row class="flex-column">
                            <v-col>
                                <v-form>
                                    <v-card title="Categoria" class="px-2" rounded="xl">
                                        <v-chip-group v-model="selectedCategory" filter :disabled="!isChange" @update:model-value="getSizesByCategory()">
                                            <v-chip v-for="category in categories" :key="category" :value="category.id">
                                                {{ category.name }}
                                            </v-chip>
                                        </v-chip-group>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success" @click=" updateProductCategory()">
                                                <span class="mdi mdi-check"></span>
                                            </v-btn>
                                            <v-btn color="info" @click="cancelCategoryUpdate()">
                                                <span class="mdi mdi-close"></span>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                            <v-col>
                                <v-form>
                                    <v-card title="Sotto-Categoria" class="px-2" rounded="xl">
                                        <v-chip-group v-model="selectedSubCategory" filter :disabled="!isChange">
                                            <v-chip v-for="subcategory in subCategories" :key="subcategory" :value="subcategory">
                                                {{ subcategory }}
                                            </v-chip>
                                        </v-chip-group>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success">
                                                <span class="mdi mdi-check"></span>
                                            </v-btn>
                                            <v-btn color="info">
                                                <span class="mdi mdi-close"></span>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                            <v-col>
                                <v-form>
                                    <v-card title="Taglie" class="px-2" rounded="xl">
                                        <v-chip-group v-model="productSizes" multiple :disabled="!isChange">
                                            <v-chip v-for="size in categorySizes" :key="size.id" :value="size.id" filter>
                                                {{ size.name }}
                                            </v-chip>
                                        </v-chip-group>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success" @click="updateProductSizes()">
                                                <span class="mdi mdi-check"></span>
                                            </v-btn>
                                            <v-btn color="info">
                                                <span class="mdi mdi-close"></span>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>

                        </v-row>
                    </v-col>
                    <v-col v-if="props.product.discounts.length">
                                <v-form >
                                    <v-card class="px-2"  title="Sconto Prodotto"  v-for="(discount, i) in props.product.discounts" :key="i" rounded="xl">
                                        <v-card-title v-if="discount.pivot.discountable_type !== 'App\\Models\\Product'">
                                            Attenzione questo sconto è applicato a più prodotti!
                                        </v-card-title>
                                        <v-date-input :disabled="!isChange" v-model="discount.start_date" label="Date Inizio" variant="solo-filled"></v-date-input>
                                        <v-date-input :disabled="!isChange" v-model="discount.end_date" label="Date Fine" variant="solo-filled"></v-date-input>
                                        <v-select
                                            v-model="discount.discount_type"
                                            label="Tipo di Sconto"
                                            :items="['percentage', 'fixed']"
                                            :disabled="!isChange"
                                        ></v-select>
                                        <VNumberInput
                                            :reverse="false"
                                            controlVariant="default"
                                            :model-value="discount.discount_value"
                                            label="Valore Sconto"
                                            :hideInput="false"
                                            :inset="false"
                                            variant="solo-filled"
                                            :disabled="!isChange"
                                        />
                                        <v-text-field :disabled="!isChange" label="Codice Sconto"  v-model="discount.name" variant="solo-filled"></v-text-field>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success">
                                                <span class="mdi mdi-check"></span>
                                            </v-btn>
                                            <v-btn color="info">
                                                <span class="mdi mdi-close"></span>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                            <v-col >
                                <v-form :disabled="!isChange" :loading="loading" >
                                    <v-card title="FAQS" class="px-2" rounded="xl">
                                        <div v-for="(faq, i) in props.product.faqs" :key="i" class=" mb-3 py-2">
                                            <v-text-field label="Domanda"  v-model="faq.question" variant="solo-filled"></v-text-field>
                                            <v-text-field label="Risposta"  v-model="faq.answer" variant="solo-filled"></v-text-field>
                                        </div>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success">
                                                <span class="mdi mdi-check"></span>
                                            </v-btn>
                                            <v-btn color="info">
                                                <span class="mdi mdi-close"></span>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                </v-row>
            </v-container>
        </template>
    </v-lazy>
</template>

<script setup>
import { ref, watch } from 'vue';
import { usePage, Head } from '@inertiajs/vue3';
import { VNumberInput } from 'vuetify/labs/VNumberInput'
import RatingStars from '@/Components/Reviews/RatingStars.vue';
import { useNotificationStore } from '@/stores/notification.store';

const notificationStore = useNotificationStore();
const { props } = usePage();
const categories = ref([]);
const subCategories = ref([]);
const isChange = ref(false);
const loading = ref(false);
const selectedCategory = ref(props.product.category.id ? props.product.category.id : "")
const selectedSubCategory = ref(props.product.subcategory ? props.product.subcategory.name : "");
const productSizes = ref([]);
const categorySizes = ref([]);

function fetchCategories(){
    loading.value = true;
    axios.get('/api/categories')
    .then((res) => {
        res.data.forEach(category => {
            categories.value.push(category);
        });
        loading.value = false;
    }).catch((e) => {
        loading.value = false;
        console.error(e)
        notificationStore.notify(e, "danger")
    })
}

function updateProductCategory() {
    axios.post(`/api/product/${props.product.id}/category`, {
        category_id: selectedCategory.value
    })
    .then((res) => {
        getSizesByCategory();
        notificationStore.notify('Categoria aggiornata con successo!', "success");
    })
    .catch((e) => {
        console.error(e);
        notificationStore.notify('Errore durante l\'aggiornamento della categoria', "danger");
    });
}

function cancelCategoryUpdate() {
    selectedCategory.value = props.product.category.id; // Ripristina il valore originale in caso di annullamento
    updateProductCategory();
}

function fetchSubCategories(){
    loading.value = true;
    axios.get('/api/sub-categories')
    .then((res) => {
        subCategories.value = [];
        res.data.forEach(subcategory => {
            subCategories.value.push(subcategory.name);
        });
        loading.value = false;
        getSizesByCategory();
    }).catch((e) => {
        loading.value = false;
        console.error(e)
        notificationStore.notify(e, "danger")
    })
}

function getSizesByCategory(){
    axios.get('/api/size/' + selectedCategory.value)
    .then((res) => {
        categorySizes.value = res.data
        getSizesByProduct();
    }).catch((e)=>{
        console.error(e)
    })
}

function getSizesByProduct(){
    axios.get('/api/product/' + props.product.id + '/sizes')
    .then((res) => {
        productSizes.value = [];
        res.data.forEach(size => {
            productSizes.value.push(size.id)
        });
    }).catch((e)=>{
        console.error(e)
    })
}


function updateProductSizes() {
    axios.post(`/api/product/${props.product.id}/sizes`, {
        size_ids: productSizes.value
    })
    .then((res) => {
        notificationStore.notify(res.data.message, res.data.color);
    })
    .catch((e) => {
        console.error(e);
        notificationStore.notify(e, "danger");
    });
}



fetchCategories();
fetchSubCategories();


watch(() => props.product.category.name, (newValue) => {
    selectedCategory.value = newValue;
});
</script>

<style scoped>
/* Aggiungi il tuo stile qui se necessario */
</style>
