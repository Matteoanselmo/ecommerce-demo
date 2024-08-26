import { defineStore } from "pinia";
import axios from "axios";

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
        getProducts(page = null) {
            if (page) {
                this.page = page;  // Aggiorna la pagina corrente nello store
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
                    console.log(res);
                })
                .catch((err) => {
                    console.error(err);
                });
        },
        setCategory(category) {
            this.category = category;
        },
        resetPage() {
            this.page = 1;
        }
    },
    persist: true,
});
