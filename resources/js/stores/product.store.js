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
        searchString: null,
        pagination: {
            total: 0,
            current_page: 1,
            last_page: 1
        },
        per_page: 10,
        page: 1,
        loading: false
    }),
    actions: {
        // Existing methods...

        performSearch() {
            this.loading = true; // Set loading state
            axios.post('/api/search', {
                query: this.searchString,
                per_page: this.per_page,
                page: this.page
            }).then((res) => {
                this.setProducts(res.data.products.data);
                console.log(this.products)
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
