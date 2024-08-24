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
            per_page: 10,
            current_page: 1,
            last_page: 1,
            from: 1,
            to: 10,
        },
    }),
    actions: {
        async getProducts(page = 1) {
            try {
                const res = await axios.get(`/api/get-products?page=${page}`);
                this.products = res.data.data;
                this.pagination = res.data.pagination;
            } catch (err) {
                console.error(err);
            }
        },
    },
    persist: false,
});
