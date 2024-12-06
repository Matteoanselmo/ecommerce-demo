<template>
    <Head title="Checkout" />
    <v-container>
        <v-row>
            <v-col md="6" cols="12">
                <div class="text-h6 mb-4">
                    Sommario Ordine
                </div>
                <v-card v-for="(product, i) in cartStore.items" :key="i"
                width="90%"
                :title="product.name + ' x' + product.quantity" class="mb-5 py-2" rounded="xl" elevation="0" >
                    <template v-slot:prepend>
                        <v-avatar :image="product.cover_image_url" rounded="0">
                        </v-avatar>
                    </template>
                    <v-card-text v-html="product.description" :opacity="0.5" style="max-height: 100px;">
                    </v-card-text>
                    <v-card-subtitle>
                        <p>({{ product.sizeName }}) <span>{{ $formatPrice(product.totalPrice) }}</span></p>
                    </v-card-subtitle>
                </v-card>
                <v-card  class="px-4 mb-5" rounded="xl" elevation="0" width="90%">
                    <div class="d-flex align-center justify-space-between py-4 border-b-md">
                        <p>Subtotale</p>
                        <p>{{ $formatPrice(subtotal) }}</p>
                    </div>
                    <div class="d-flex align-center justify-space-between py-4 border-b-md">
                        <p>Spedizione</p>
                        <p>{{ $formatPrice(shipping) }}</p>
                    </div>
                    <div class="d-flex align-center justify-space-between py-4 border-b-md">
                        <p>Tasse</p>
                        <p>{{ $formatPrice(tax) }}</p>
                    </div>
                    <div class="d-flex align-center justify-space-between py-4 ">
                        <p>Totale Ordine</p>
                        <p>{{ $formatPrice(orderTotal) }}</p>
                    </div>
                </v-card>
            </v-col>
            <v-col md="6" cols="12">
                <div class="text-h6 mb-4">
                    Informazioni Contatto
                </div>
                <v-row>
                    <v-col cols="12" lg="6" >
                        <v-list density="compact" selectable="true" v-model:selected="selectedAddress">
                            <v-list-subheader>Indirizzi</v-list-subheader>
                            <v-list-item v-for="(address, index) in addresses" :key="address.id">
                                <v-card
                            rounded="xl"
                            class="py-4 px-4"
                            border="0"
                            elevation="0"
                            :title="address.address + ' - ' + address.house_number"
                        >
                            <v-card-text>
                                <p class="text-body-2 mb-1">{{ address.city }}, {{ address.state }}, {{ address.country }}</p>
                                <p class="text-body-2 mb-1">{{ address.postal_code }}</p>
                                <p v-if="address.company_name">{{ address.company_name }}</p>
                                <p>{{ address.recipient_name }}</p>
                                <p>{{ address.phone_number }}</p>
                            </v-card-text>
                            <v-card-actions class="justify-end">
                                <v-btn
                                    icon="mdi mdi-pencil"
                                    color="warning"
                                    @click="openDialog(index)"
                                ></v-btn>
                                <v-btn
                                    icon="mdi mdi-delete"
                                    color="red"
                                    @click="deleteAddress(address.id)"
                                ></v-btn>
                            </v-card-actions>
                        </v-card>
                            </v-list-item>
                        </v-list>
                    </v-col>
                </v-row>

                <!-- <form @submit.prevent="pay">
                    <v-card rounded="xl" elevation="0" class="mb-5 py-4 px-4">
                        <v-text-field
                            v-model.trim="name"
                            label="Nome Completo"

                            prepend-inner-icon="mdi-account"
                            :error-messages="nameError"
                            @blur="validateName"
                        ></v-text-field>
                        <v-text-field
                            v-model.trim="email"
                            label="Email"
                            :rules="emailRules"
                            :error-messages="emailError"
                            @blur="validateEmail"
                            prepend-inner-icon="mdi-email"
                            type="email"
                        ></v-text-field>
                        <v-text-field
                            v-model.trim="phone"
                            label="Numero di Telefono"
                            :error-messages="phoneError"
                            prepend-inner-icon="mdi-phone"
                            @blur="validatePhone"
                        ></v-text-field>
                        <v-text-field
                            v-model.trim="provincia"
                            label="Provincia"
                            prepend-inner-icon="mdi-map-marker-outline"
                            type="text"
                        ></v-text-field>
                        <v-text-field
                            v-model.trim="comune"
                            label="Comune"
                            prepend-inner-icon="mdi-bank-outline"
                            type="text"
                        ></v-text-field>
                        <v-text-field
                            v-model.trim="address"
                            label="Via ( specificare interno se presente )"
                            prepend-inner-icon="mdi-home-city-outline"
                            type="text"
                        ></v-text-field>
                        <v-text-field
                            v-model.trim="cap"
                            label="Cap"
                            prepend-inner-icon="mdi-road-variant"
                            type="text"
                        ></v-text-field>
                    </v-card>
                    <v-card rounded="xl" elevation="0" class="mb-5 py-4 px-4">
                        <div id="payment-element"></div>
                        <div class="d-flex justify-center">
                            <v-btn
                                class="text-center mt-4"
                                type="submit"
                                >Paga</v-btn
                            >
                        </div>
                    </v-card>
                </form> -->
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import { useCartStore } from '@/stores/cartStore';
import { loadStripe } from "@stripe/stripe-js";
import { getCurrentInstance } from 'vue';

const { proxy } = getCurrentInstance();
const page = usePage()
const cartStore = useCartStore();
const addresses = ref([]);
const selectedAddress = ref(null);
const subtotal = computed(() => cartStore.totalAmount);
const shipping = 500; // Importo fisso o calcolato separatamente
const tax = computed(() => (subtotal.value + shipping) * 0.08); // 8% di imposta ipotetica
const orderTotal = computed(() => subtotal.value + shipping + tax.value);
const name = ref(page.props.auth.user.name || "");
const email = ref(page.props.auth.user.email || "");
const phone = ref("");
const address = ref("");
const cap = ref("");
const provincia = ref("");
const comune = ref("");
const nameError = ref("");
const emailError = ref("");
const phoneError = ref("");
const clientSecret = ref("");

let elements = ref(null);
let paymentElement = ref(null);

// Regola di validazione email con Vuetify
const emailRules = [
    v => !!v || "Email richiesta",
    v => /.+@.+\..+/.test(v) || "Email non valida",
];

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_KEY);


const validateName = () => {
    nameError.value = name.value ? "" : "Nome richiesto";
};

const validateEmail = () => {
    emailError.value =
        email.value && /.+@.+\..+/.test(email.value) ? "" : "Email non valida";
};

const validatePhone = () => {
    phoneError.value = phone.value ? "" : "Numero di telefono richiesto";
};


function fetchAddresses() {
    axios.get('/api/user-addresses')
    .then((res) => {
        addresses.value = res.data;
        // Imposta l'indirizzo predefinito se esiste
        const primaryAddress = addresses.value.find(address => address.is_primary);
        if (primaryAddress) {
            selectedAddress.value = primaryAddress;
        }
    })
    .catch((e) => {
        console.error(e);
    });
}


const pay = async () => {
    const stripe = await stripePromise;

    try {
        // Conferma del pagamento con Stripe
        const { error, paymentIntent } = await stripe.confirmPayment({
            elements,
            redirect: "if_required", // Utilizza il redirect solo se necessario
            confirmParams: {
                payment_method_data: {
                    billing_details: {
                        name: name.value,
                        email: email.value,
                        phone: phone.value,
                    },
                },
            },
        });

        if (error) {
            // Qua gestirei l'errore con notification store senza router
            router.post('/payment-response', {
                status: 'error',
                message: error.message,
            });
        } else if (paymentIntent.status === 'succeeded') {
            // Qua userei axios e tramite api un check sullo status poi creare il nuovo Order, e rimandare alla pagina dedicata nella then con router successivamente
            router.post('/payment-response', {
                status: 'success',
                message: 'Pagamento effettuato con successo!',
                paymentIntent: paymentIntent,
            });
        } else {
            // Qua userei axios e tramite api e fare un check sullo status e rimandare alla pagina iniziale nella then con router successivamente
            router.post('/payment-response', {
                status: 'pending',
                message: `Lo stato del pagamento è: ${paymentIntent.status}`,
                paymentIntent: paymentIntent,
            });
        }
    } catch (error) {
        console.error("Errore durante il processo di pagamento:", error);
        // qua usa notification store!!
        router.post('/payment-response', {
            status: 'error',
            message: error.message,
        });
    }
};


onMounted(async () => {
    try {
        // Creazione della descrizione da inviare al server
        const description = `
            ${cartStore.items.map(item => `- ${item.name} (Taglia: ${item.sizeName}, Quantità: ${item.quantity}, Prezzo: €${proxy.$formatPrice(item.totalPrice)})`).join('\n')}
            Prezzo Totale: € ${proxy.$formatPrice(cartStore.totalAmount)}
        `;

        // Ottieni il clientSecret dal server
        const response = await axios.post("/api/create-payment-intent", {
            amount: cartStore.totalAmount, // Amount in cents
            description: description, // Passa la descrizione al server
        });

        const data = response.data;
        clientSecret.value = data.clientSecret;

        const stripe = await stripePromise;
        elements = stripe.elements({ clientSecret: clientSecret.value });
        paymentElement = elements.create("payment");
        paymentElement.mount("#payment-element");

    } catch (error) {
        console.error("Errore durante la creazione del payment intent:", error);
    }
});
fetchAddresses();
console.log(cartStore.items)
</script>
