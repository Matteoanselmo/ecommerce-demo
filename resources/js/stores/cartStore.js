import { defineStore } from 'pinia';
import { useNotificationStore } from './notification.store';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
    }),
    actions: {
        addItem(newItem) {
            const notificationStore = useNotificationStore();
            // Trova la taglia selezionata usando `productSizes` come ID
            const selectedSize = newItem.sizes.find(size => size.id === newItem.productSizes);

            // Verifica che la taglia selezionata sia valida
            if (!selectedSize) {
                notificationStore.notify("Taglia selezionata non trovata.", "danger");
                return;
            }

            // Controlla se il prodotto con la taglia selezionata esiste già nel carrello
            const existingItem = this.items.find(
                item => item.id === newItem.id && item.sizeId === selectedSize.id
            );

            // Verifica che la quantità richiesta (usando `selectedSize.quantity`) sia disponibile
            if (selectedSize.quantity > selectedSize.stock) {
                notificationStore.notify("Quantità richiesta oltre lo stock disponibile.", "danger");
                return;
            }

            if (existingItem) {
                // Verifica che la quantità totale non superi lo stock disponibile
                if (existingItem.quantity + selectedSize.quantity > selectedSize.stock) {
                    notificationStore.notify("Quantità totale richiesta oltre lo stock disponibile.", "danger");
                    return;
                }
                // Se l'articolo esiste già, aggiorna la quantità e il prezzo totale
                existingItem.quantity += selectedSize.quantity;
                existingItem.totalPrice = existingItem.quantity * existingItem.price;
            } else {
                // Se l'articolo non esiste, aggiungilo al carrello con la taglia selezionata
                this.items.push({
                    ...newItem,
                    sizeId: selectedSize.id,
                    sizeName: selectedSize.name,
                    quantity: selectedSize.quantity,
                    totalPrice: selectedSize.quantity * newItem.price,
                });
            }
        },
        removeItem(item) {
            const index = this.items.indexOf(item);
            if (index !== -1) {
                this.items.splice(index, 1);
            }
        },
        clearCart() {
            this.items = [];
        },
        incrementQuantity(id) {
            const notificationStore = useNotificationStore();
            const item = this.items.find(item => item.id === id);
            if (item && item.quantity < item.stock) { // Verifica che la quantità non superi lo stock
                item.quantity++;
                item.totalPrice = item.quantity * item.price; // Aggiorna il prezzo totale
            } else {
                notificationStore.notify("Quantità massima raggiunta per questo articolo.", "danger");
            }
        },
        decrementQuantity(id) {
            const item = this.items.find(item => item.id === id);
            if (item && item.quantity > 1) {
                item.quantity--;
                item.totalPrice = item.quantity * item.price; // Aggiorna il prezzo totale
            }
        },
    },
    getters: {
        itemCount: (state) => state.items.reduce((count, item) => count + item.quantity, 0),
        totalAmount: (state) => {
            const total = state.items.reduce((total, item) => total + item.totalPrice, 0);
            return parseFloat(total.toFixed(2));
        },
    },
    persist: true,
});
