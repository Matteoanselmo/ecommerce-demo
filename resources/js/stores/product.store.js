import { defineStore } from "pinia";
import axios from "axios";
import { router } from '@inertiajs/vue3';

export const useProductStore = defineStore({
    id: "product",
    state: () => ({
        products: [],
        category: null,
        subCategory: null,
        ratingtar: null,
        searchString: null, // Stringa di ricerca globale
        pagination: {
            total: 0,
            current_page: 1,
            last_page: 1
        },
        per_page: 10,
        page: 1,
        loading: false,
        productsToSearch: [], // Prodotti per la barra di ricerca
        selectedProductId: null, // ID prodotto selezionato
    }),
    actions: {
        // Metodo esistente per la ricerca
        performSearch() {
            this.loading = true; // Set loading state
            axios.post('/api/search', {
                query: this.searchString,
                per_page: this.per_page,
                page: this.page
            }).then((res) => {
                this.setProducts(res.data.products.data);
                if (res.data.selected_category && res.data.selected_category.name) {
                    this.setCategory(res.data.selected_category.name);
                    const category = res.data.selected_category.name;
                    router.get(route('products.list', { category }));
                }
                this.loading = false; // Unset loading state
            }).catch((err) => {
                console.error(err);
                this.loading = false; // Unset loading state on error
            });
        },

        getProducts(page = null) {
            if (page) {
                this.page = page;  // Update current page in the store
            }
            this.loading = true;
            axios.get(`/api/get-products`, {
                params: {
                    category: this.category,
                    per_page: this.per_page,
                    page: this.page
                },
            })
                .then((res) => {
                    this.products = res.data.data;
                    this.pagination.total = res.data.total;
                    this.pagination.last_page = res.data.last_page;
                    this.pagination.current_page = res.data.current_page;
                    this.loading = false;
                })
                .catch((err) => {
                    console.error(err);
                    this.loading = false;
                });
        },

        // Metodo per gestire i risultati della ricerca in tempo reale
        fetchSearchResults(query) {
            this.loading = true; // Set loading state
            axios.get('/api/search-products', {
                params: { query }  // Passa la query di ricerca al server
            }).then((res) => {
                this.productsToSearch = res.data;  // Popola i risultati
                this.loading = false; // Unset loading state
            }).catch((err) => {
                console.error(err);
                this.loading = false; // Unset loading state on error
            });
        },

        // Naviga verso il prodotto selezionato
        navigateToProduct(productId) {
            if (productId) {
                router.get(route('product.detail', { product: productId }));
            }
        },

        // Imposta l'ID del prodotto selezionato
        setSelectedProductId(productId) {
            this.selectedProductId = productId;
        },

        // Imposta i prodotti in stato
        setProducts(items) {
            this.products = items;
        },

        setCategory(category) {
            this.category = category;
        },

        resetPage() {
            this.page = 1;
        },
    },
    persist: true,
});
