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
                <Address
                    @address-selected="handleAddressSelected"
                />
                <BillingAddresses
                    @billing-address-selected="handleAddressSelected"
                />
                <form @submit.prevent="pay">
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
                </form>
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
import Address from '@/Components/User/Address.vue';
import BillingAddresses from '@/Components/User/BillingAddresses.vue';
import { useNotificationStore } from '@/stores/notification.store';

const notification = useNotificationStore();
const { proxy } = getCurrentInstance();
const page = usePage()
const cartStore = useCartStore();
const subtotal = computed(() => cartStore.totalAmount);
const shipping = 500; // Importo fisso o calcolato separatamente
const selectedAddress = ref(null);
const orderTotal = computed(() => subtotal.value + shipping);
const clientSecret = ref("");

let elements = ref(null);
let paymentElement = ref(null);

const stripePromise = loadStripe(import.meta.env.VITE_STRIPE_KEY);

// Funzione per gestire l'evento address-selected
function handleAddressSelected(address) {
    selectedAddress.value = address; // Salva l'indirizzo selezionato
    console.log(selectedAddress.value)
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
                        "address": {
                            "city": selectedAddress.value.city,
                            "country": selectedAddress.value.country,
                            "line1": selectedAddress.value.address + ' ' + selectedAddress.value.house_number,
                            "postal_code": selectedAddress.value.postal_code,
                            "state": selectedAddress.value.state
                        },
                        name: selectedAddress.value.recipient_name,
                        email: page.props.auth.user.email,
                        phone: selectedAddress.value.phone_number,
                    },
                },
            },
        });

        if (error) {
            // Qua gestirei l'errore con notification store senza router
            notification.notify(error, 'danger')
            console.error(error)
        } else if (paymentIntent.status === 'succeeded') {
            // Qua userei axios e tramite api un check sullo status poi creare il nuovo Order, e rimandare alla pagina dedicata nella then con router successivamente
            axios.post('/api/payment-response', {
                status: 'confirmed',
                message: 'Pagamento effettuato con successo!',
                paymentIntent: paymentIntent,
                products : cartStore.items
            }).then((res) => {
                cartStore.clearCart();
                router.get(route('home'));
            }).catch((e) => {
                console.error(e)
            });
        } else {
            // Qua userei axios e tramite api e fare un check sullo status e rimandare alla pagina iniziale nella then con router successivamente
            notification.notify(`Lo stato del pagamento è: ${paymentIntent.status}`, 'info')

        }
    } catch (error) {
        console.error("Errore durante il processo di pagamento:", error);
        // qua usa notification store!!
        notification.notify(error, 'danger')
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
            amount: orderTotal.value, // Amount in cents
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

console.log(cartStore.items);
</script>
