import { defineStore } from 'pinia';

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [],
    }),
    actions: {
        addItem(newItem) {
            const existingItem = this.items.find(item => item.id === newItem.id);
            if (existingItem) {
                existingItem.quantity += newItem.quantity || 1;
                existingItem.price = newItem.price; // Aggiorna il prezzo se il prodotto esiste già
                existingItem.totalPrice = existingItem.quantity * existingItem.price; // Ricalcola il prezzo totale
            } else {
                this.items.push({
                    ...newItem,
                    quantity: newItem.quantity || 1,
                    totalPrice: (newItem.quantity || 1) * newItem.price, // Calcola il prezzo totale iniziale
                });
            }
        },
        removeItem(id) {
            this.items = this.items.filter(item => item.id !== id);
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
        totalAmount: (state) => state.items.reduce((total, item) => total + item.totalPrice, 0),
    },
    persist: true,
});
