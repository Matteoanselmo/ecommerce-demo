import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
    }),
    actions: {
        addItem(newItem) {
            // Trova la taglia selezionata usando `productSizes` come ID
            const selectedSize = newItem.sizes.find(size => size.id === newItem.productSizes);

            // Verifica che la taglia selezionata sia valida
            if (!selectedSize) {
                console.error("Taglia selezionata non trovata.");
                return;
            }

            // Controlla se il prodotto con la taglia selezionata esiste già nel carrello
            const existingItem = this.items.find(
                item => item.id === newItem.id && item.sizeId === selectedSize.id
            );

            // Verifica che la quantità richiesta (usando `selectedSize.quantity`) sia disponibile
            if (selectedSize.quantity > selectedSize.stock) {
                console.error("Quantità richiesta oltre lo stock disponibile.");
                return;
            }

            if (existingItem) {
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
            const item = this.items.find(item => item.id === id);
            if (item) {
                item.quantity++;
                item.totalPrice = item.quantity * item.price; // Aggiorna il prezzo totale
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
