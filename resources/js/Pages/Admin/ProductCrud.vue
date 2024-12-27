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
                        <v-card class="px-2 py-1" rounded="xl" elevation="0">
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
                                    <v-card class="px-2 py-2" :title="'Prodotto: '+ props.product.name" rounded="xl" elevation="0">
                                        <v-text-field label="titolo"  v-model="props.product.name" variant="solo-filled"></v-text-field>
                                        <v-textarea label="descrizione"  v-model="props.product.description" variant="solo-filled"></v-textarea>
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
                                                    v-for="(image, index) in props.product.images"
                                                    :key="index"
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
                                                    <v-btn size="small" color="danger" class="my-5 mx-5" :disabled="!isChange" @click="removeImage(index)">
                                                        <v-icon icon="mdi mdi-close" size="small"></v-icon>
                                                    </v-btn>
                                                </v-img>
                                                </v-col>
                                            </v-row>
                                        </v-container>
                                        <v-file-input
                                            label="Carica Immagini"
                                            variant="solo-filled"
                                            prepend-icon="mdi-camera"
                                            :show-size="1000"
                                            counter
                                            color="primary"
                                            multiple
                                            v-model="productImagesInput"
                                            accept="image/*"
                                            :loading="loading"
                                        ></v-file-input>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success" class="me-2" :disabled="!isChange" @click="updateProduct()">
                                                Salva
                                            </v-btn>
                                            <v-btn color="danger">
                                                Annulla
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                            <v-col cols="12">
                                <v-card elevation="0" rounded="xl">
                                    <v-card-text>
                                        <div  class="d-flex mb-3" width="100%">
                                            <v-card
                                                rounded="xl"
                                                elevation="0"
                                                border="md"
                                                width="150"
                                                prepend-icon="mdi mdi-file-pdf-box"
                                                v-for="(datasheet, index) in productDataSheet"
                                                :key="index"
                                                :subtitle="datasheet.name"
                                                class="mr-2"
                                            >
                                            <v-card-actions>
                                                <v-btn color="info" icon="mdi mdi-download" :href="datasheet.path"  target="blank" download></v-btn>
                                                <v-btn color="danger" icon="mdi mdi-delete-outline" @click="deleteDataSheet(datasheet.id)"></v-btn>
                                            </v-card-actions>
                                            </v-card>
                                        </div>
                                        <v-file-input
                                            label="Carica Certificati PDF"
                                            variant="solo-filled"
                                            prepend-icon="mdi-file-pdf-box"
                                            :show-size="true"
                                            counter
                                            color="primary"
                                            multiple
                                            v-model="productDataSheetInput"
                                            accept="application/pdf"
                                            :loading="loading"
                                            :disabled="!isChange"
                                            @update:model-value="console.log(productDataSheetInput)"
                                        ></v-file-input>
                                    </v-card-text>
                                    <v-card-actions class="justify-end">
                                        <v-btn color="success" text="salva" :disabled="!isChange" @click="saveDataSheets()"></v-btn>
                                        <v-btn color="danger" text="annulla"></v-btn>
                                    </v-card-actions>
                                </v-card>

                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="4">
                        <v-row class="flex-column">
                            <v-col>
                                <v-form>
                                    <v-card title="Categoria" class="px-2" rounded="xl" elevation="0">
                                        <v-chip-group v-model="selectedCategory" filter :disabled="!isChange" @update:model-value="getSizesByCategory()">
                                            <v-chip v-for="category in categories" :key="category" :value="category.id">
                                                {{ category.name }}
                                            </v-chip>
                                        </v-chip-group>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success" @click=" updateProductCategory()" :disabled="props.product.category_id === selectedCategory">
                                                <v-icon icon="mdi mdi-check" size="small"></v-icon>
                                            </v-btn>
                                            <v-btn color="danger" @click="cancelCategoryUpdate()" :disabled="props.product.category_id === selectedCategory">
                                                <v-icon icon="mdi mdi-close" size="small"></v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                            <v-col>
                                <v-form>
                                    <v-card title="Sotto-Categoria" class="px-2" rounded="xl" elevation="0">
                                        <v-chip-group v-model="selectedSubCategory" filter :disabled="!isChange">
                                            <v-chip v-for="subcategory in subCategories" :key="subcategory" :value="subcategory">
                                                {{ subcategory }}
                                            </v-chip>
                                        </v-chip-group>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success">
                                                <v-icon icon="mdi mdi-check" size="small"></v-icon>
                                            </v-btn>
                                            <v-btn color="danger">
                                                <v-icon icon="mdi mdi-close" size="small"></v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                            <v-col>
                                <v-form>
                                    <v-card title="Taglie" class="px-2" rounded="xl" elevation="0">
                                        <v-chip-group v-model="productSizes" multiple :disabled="!isChange">
                                            <v-chip v-for="size in categorySizes" :key="size.id" :value="size.id" filter>
                                                {{ size.name }}
                                            </v-chip>
                                        </v-chip-group>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success" @click="updateProductSizes()">
                                                <v-icon icon="mdi mdi-check" size="small"></v-icon>
                                            </v-btn>
                                            <v-btn color="danger">
                                                <v-icon icon="mdi mdi-close" size="small"></v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                        </v-row>
                        <!-- Inputs per lo stock di ogni taglia selezionata -->
                        <v-row v-if="selectedSizes.length">
                            <v-col >
                                <v-card rounded="xl" title="Stock Taglie">
                                    <v-card-text v-for="size in selectedSizes" :key="size.id" class="pb-0" elevation="0">
                                        <v-text-field
                                        variant="solo-filled"
                                        :label="`Stock per taglia ${size.name}`"
                                        type="number"
                                        v-model="size.stock"
                                        :disabled="!isChange"
                                        ></v-text-field>
                                    </v-card-text>
                                    <v-card-actions class="justify-end">
                                        <v-btn color="success" @click="updateProductSizesWithStock()">
                                            <v-icon icon="mdi mdi-check" size="small"></v-icon>
                                        </v-btn>
                                        <v-btn color="danger" >
                                            <v-icon icon="mdi mdi-close"  size="small"></v-icon>
                                        </v-btn>
                                    </v-card-actions>
                                </v-card>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-form>
                                    <v-card title="Certificazioni" class="px-2" rounded="xl" elevation="0">
                                        <v-chip-group v-model="productCertifications" multiple :disabled="!isChange">
                                            <v-chip v-for="certification in certifications" :key="certification.id" :value="certification.id" filter>
                                                {{ certification.name }}
                                            </v-chip>
                                        </v-chip-group>
                                        <v-card-actions class="justify-end">
                                            <v-btn color="success" @click="updateProductCertifications()">
                                                <v-icon icon="mdi mdi-check" size="small"></v-icon>
                                            </v-btn>
                                            <v-btn color="danger">
                                                <v-icon icon="mdi mdi-close" size="small"></v-icon>
                                            </v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-form>
                            </v-col>
                        </v-row>
                    </v-col>
                    <v-col cols="4" v-if="props.product.discounts.length" v-for="(discount, i) in props.product.discounts" :key="i" >
                        <v-form >
                            <v-card class="px-2"  title="Sconto Prodotto"  rounded="xl" elevation="0">
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
                                        <v-icon icon="mdi mdi-check" size="small"></v-icon>
                                    </v-btn>
                                    <v-btn color="danger">
                                        <v-icon icon="mdi mdi-close" size="small"></v-icon>
                                    </v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-form>
                    </v-col>
                    <v-col >
                        <v-form :disabled="!isChange" :loading="loading" >
                            <v-card class="px-2" rounded="xl" elevation="0">
                                <v-card-title class="d-flex justify-space-between">
                                    FAQS
                                    <v-btn
                                        icon
                                        color="info"
                                        @click="addFaq"
                                        :disabled="!isChange"
                                        variant="text"
                                    >
                                        <v-icon icon="mdi mdi-plus"></v-icon>
                                    </v-btn>
                                </v-card-title>
                                <v-card-text v-for="(faq, i) in props.product.faqs" :key="i" class=" mb-3 py-2">
                                    <div class="d-flex align-center justify-space-between">
                                        <div class="d-flex flex-column w-100 mr-5">
                                            <v-text-field label="Domanda"  v-model="faq.question" variant="solo-filled"></v-text-field>
                                            <v-text-field label="Risposta"  v-model="faq.answer" variant="solo-filled"></v-text-field>
                                        </div>
                                        <v-btn
                                            icon
                                            @click="deleteFaq(faq.id)"
                                            :disabled="!isChange"
                                            size="small"
                                            color="red"
                                            variant="text"
                                        >
                                            <v-icon icon="mdi mdi-delete"  size="small"></v-icon>
                                        </v-btn>
                                    </div>
                                </v-card-text>
                                <v-card-actions class="justify-end">
                                    <v-btn color="success" @click="saveFaqs()">
                                        <v-icon icon="mdi mdi-check" size="small"></v-icon>
                                    </v-btn>
                                    <v-btn color="danger" @click="resetFaqs">
                                        <v-icon icon="mdi mdi-close" size="small"></v-icon>
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
import { data } from 'autoprefixer';

const notificationStore = useNotificationStore();
const { props } = usePage();
// Prova per riutilizzare il componente per Create Product
const productData = ref({
    id: props.product?.id || '',
    name: props.product?.name || '',
    description: props.product?.description || '',
    stock_quantity: props.product?.stock_quantity || 0,
    price: props.product?.price || 0,
    category: props.product?.category || { id: '', name: '' },
    subcategory: props.product?.subcategory || '',
    images: props.product?.images || [],
    discounts: props.product?.discounts || [],
    faqs: props.product?.faqs || []
});


const categories = ref([]);
const subCategories = ref([]);
const isChange = ref(false);
const loading = ref(false);
const selectedCategory = ref(props.product.category.id ? props.product.category.id : "")
const selectedSubCategory = ref(props.product.subcategory ? props.product.subcategory.name : "");
const productSizes = ref([]);
const productDataSheet = ref([]);
const productDataSheetInput = ref([]);
const categorySizes = ref([]);
const selectedSizes = ref([]);
const productImagesInput = ref([]);
const productCertifications = ref([]);
const certifications = ref([]);
// Porodotto
function updateProduct() {
    const formData = new FormData();
    formData.append('_method', 'put');
    formData.append('name', props.product.name);
    formData.append('description', props.product.description);
    formData.append('price', props.product.price);

     // Aggiungi le immagini selezionate a FormData
    if (productImagesInput.value && productImagesInput.value.length) {
        productImagesInput.value.forEach((file, index) => {
            formData.append(`images[${index}]`, file);
        });
    }

    axios.post(`/api/product/${props.product.id}`, formData)
    .then(res => {
        console.log(res.data)
        notificationStore.notify(res.data.message, res.data.color);
        // // Aggiorna i dati locali con la risposta dell'API
        props.product = res.data.product;
        productImagesInput.value = [];
    })
    .catch(error => {
        console.error(error.response ? error.response.data : error);
        notificationStore.notify(error.response?.data?.message || 'Errore durante l\'aggiornamento del prodotto', 'danger');
    });
}


function removeImage(index){
    const image = props.product.images[index];
    console.log(image)
    if (image.id){
        loading.value = true;
        axios.delete(`/api/product-images/${image.id}`)
        .then((res) => {
            loading.value = false;
        }).catch((e) => {
            loading.value = false;
            notificationStore.notify(e, "danger");
            console.error(e)
        })
    }
    props.product.images.splice(index, 1);
    notificationStore.notify("Immagine rimossa con successo", "success");
}

// Certificazioni
function fetchDataSheets(){
    axios.get('/api/datasheets/' + props.product.id)
    .then((res) => {
        console.log(res);
        productDataSheet.value = res.data;
    }).catch((e) => {
        console.error(e)
    })
}

function saveDataSheets() {
    if (!productDataSheetInput.value || productDataSheetInput.value.length === 0) {
        console.error("Nessun file selezionato.");
        return;
    }

    loading.value = true; // Imposta lo stato di caricamento

    // Prepara i file per il caricamento
    const formData = new FormData();
    productDataSheetInput.value.forEach((file) => {
        formData.append('file[]', file); // Aggiungi i file al form data
    });

    // Endpoint con il product ID
    const endpoint = `/api/datasheets/${props.product.id}`;

    axios.post(endpoint, formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then((res) => {
            notificationStore.notify(res.data.message, res.data.color);
            fetchDataSheets(); // Richiama la funzione per aggiornare i datasheets
            productDataSheetInput.value = []; // Reset dell'input file
        })
        .catch((e) => {
            console.error("Errore durante il caricamento dei file:", e);
            notificationStore.notify("Errore durante il caricamento dei file", 'danger');
        })
        .finally(() => {
            loading.value = false; // Reset dello stato di caricamento
        });
}

function deleteDataSheet(id){
    if(window.confirm("Sei sicuro di voler eliminare questo file?")){
        loading.value = true;
        axios.delete(`/api/datasheets/${id}`)
        .then((res) => {
            fetchDataSheets();
            notificationStore.notify(res.data.message, res.data.color);
            loading.value = false;
        }).catch((e) => {

            console.error(e)
            notificationStore.notify(e, "danger")
        })
    }
}


function fetchCertification(){
    axios.get('/api/certifications/')
    .then((res) => {
        certifications.value = res.data;
        getProductCertifications();
    }).catch((e) => {
        console.error(e)
    })
}

function getProductCertifications(){
    axios.get('/api/certifications/' + props.product.id)
    .then((res) =>{
        console.log(res);

        res.data.forEach(certification => {
            productCertifications.value.push(certification.id)
        });
    }).catch((e)=>{
        console.error(e)
    })
}

function updateProductCertifications() {
    axios.post(`/api/product/${props.product.id}/certifications`, {
        certification_ids: productCertifications.value
    })
    .then((res) => {
        getProductCertifications();
        notificationStore.notify(res.data.message, res.data.color);
    })
    .catch((e) => {
        console.error(e);
        notificationStore.notify(e, "danger");
    });
}


// Categorie
function fetchCategories(){
    loading.value = true;
    axios.get('/api/all-categories')
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
        notificationStore.notify(res.data.message, res.data.color);
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

// Taglie
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
        selectedSizes.value = res.data.map(size => ({
            id: size.id,
            name: size.name,
            stock: size.stock || 0
        }));
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
        getSizesByProduct();
        notificationStore.notify(res.data.message, res.data.color);
    })
    .catch((e) => {
        console.error(e);
        notificationStore.notify(e, "danger");
    });
}

// Aggiorna lo stock per le taglie selezionate
function updateProductSizesWithStock() {
    const sizesWithStock = selectedSizes.value.map(size => ({
        size_id: size.id,
        stock: size.stock
    }));

    axios.post(`/api/product/${props.product.id}/sizes-with-stock`, { sizes: sizesWithStock })
    .then((res) => {
        notificationStore.notify(res.data.message, res.data.color);
    }).catch((error) => {
            console.error(error);
            notificationStore.notify(error, 'danger');
    });
}

// FAQS
function addFaq (){
    props.product.faqs.unshift({
        question: '',
        answer: '',
    });
};

function saveFaqs() {
    axios.post(`/api/product/${props.product.id}/faqs`, { faqs: props.product.faqs })
        .then((res) => {
            notificationStore.notify(res.data.message, res.data.color);
        })
        .catch((error) => {
            console.error(error);
            notificationStore.notify('Errore durante il salvataggio delle FAQ', 'danger');
        });
};

function deleteFaq(faqId) {
    // Mostra un prompt di conferma prima di eliminare
    const isConfirmed = window.confirm("Sei sicuro di voler eliminare questa FAQ?");
    if (!isConfirmed) {
        return; // Se l'utente annulla, interrompi l'operazione
    }

    if (!faqId) {
        // Se l'ID è `null`, significa che la FAQ non è stata ancora salvata, quindi possiamo rimuoverla localmente
        props.product.faqs = props.product.faqs.filter(faq => faq.id !== faqId);
        return;
    }

    // Chiamata API per eliminare la FAQ dal backend
    axios.delete(`/api/faqs/${faqId}`)
        .then((res) => {
            props.product.faqs = props.product.faqs.filter(faq => faq.id !== faqId);
            notificationStore.notify(res.data.message, res.data.color);
        })
        .catch((error) => {
            console.error(error);
            notificationStore.notify('Errore durante l\'eliminazione della FAQ', 'danger');
        });
}

function resetFaqs() {
    // Ripristina le FAQ al loro stato originale copiando da props.product.faqs
    props.product.faqs = props.product.faqs.map(faq => ({
        ...faq // Copia ogni FAQ per evitare modifiche dirette all'array originale
    }));
    notificationStore.notify('FAQ ripristinate', 'info');
}




fetchCategories();
fetchSubCategories();
fetchCertification();
fetchDataSheets();

watch(() => props.product.category.name, (newValue) => {
    selectedCategory.value = newValue;
});
</script>

<style scoped>
/* Aggiungi il tuo stile qui se necessario */
</style>
