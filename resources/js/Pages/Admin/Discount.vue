<template>
    <Head title="Sconti"/>
    <v-container fluid>
        <v-row>
            <v-col cols="3" class="pb-0 mt-5">
                <div class="d-flex justify-between">
                    <!-- Cicliamo su searchFields per generare i campi di input -->
                    <v-text-field
                        v-for="(field, index) in searchFields"
                        :key="index"
                        variant="solo-filled"
                        :prepend-inner-icon="field.icon"
                        v-model="field.value"
                        :label="field.label"
                        clearable
                        @input="debouncedfetchDiscounts"
                        class="px-2"
                    ></v-text-field>
                </div>
            </v-col>
            <v-col cols="12">
                <TableServer
                    :totalItems="totalItems"
                    :items="discounts"
                    :itemsPerPage="itemsPerPage"
                    :headers="headers"
                    :loading="loading"
                    :search-fields="searchFields"
                    :page="page"
                    @updateItems="fetchDiscounts"
                    :type="'discount'"
                    :crud="['show', 'delete', 'store']"
                />
            </v-col>
        </v-row>
    </v-container>
</template>

<script setup>
import TableServer from '@/Components/Tables/TableServer.vue';
import { ref } from 'vue';
import { debounce } from 'lodash';
import { Head } from '@inertiajs/vue3';

const discounts = ref([]);
const loading = ref(true);
const totalItems = ref(0);
const itemsPerPage = ref(10);
const sortDirection = ref('asc');
const page = ref(1);

const searchFields = ref([
    { key: 'name', value: '', label: 'Nome', icon: 'mdi-magnify' },
]);
const headers = ref([
    {
        title: 'Name',
        key: 'name',
        type: 'text',
        aling: 'start',
        isEditable: true
    },
    {
        title: 'Tipo',
        key: 'discount_type',
        type: 'select',
        model: 'discount_type',
        items: [
            { name: 'percentage', value: 'percentage' },
            { name: 'fixed', value: 'fixed' },
        ],
        isEditable: true,
        aling: 'start'
    },
    {
        title: 'Valore',
        key: 'discount_value',
        type: 'number',
        sortable: false,
        isEditable: true,
        aling: 'start'
    },
    {
        title: 'Data Inizio',
        key: 'start_date',
        type: 'date',
        sortable: false,
        isEditable: true,
        aling: 'start'
    },
    {
        title: 'Data Fine',
        key: 'end_date',
        type: 'date',
        isEditable: true,
        sortable: false,
        aling: 'start'
    },
    {
        title: 'Collegamenti',
        key: 'links',
        type: 'text',
        sortable: false,
        isEditable: false,
        align: 'start'
    },
    {
        title: "Azioni",
        key: "actions",
        aling: 'end',
        sortable: false
    },
]);

function fetchDiscounts() {
    const searchQuery = searchFields.value.reduce((acc, field) => {
        acc[field.key] = field.value;
        return acc;
    }, {});

    axios.get('/api/discounts', {
        params: {
            page: page.value,
            itemsPerPage: itemsPerPage.value,
            sort_direction: sortDirection.value,
            search_name: searchQuery.name,
        }
    })
    .then(res => {
        discounts.value = res.data.data.map(discount => {
            const productNames = discount.products?.map(product => product.name).join(', ') || '';
            const categoryNames = discount.categories?.map(category => category.name).join(', ') || '';

            return {
                ...discount,
                links: `${productNames} ${categoryNames}`,
            };
        });
        totalItems.value = res.data.total;
        page.value = res.data.current_page;
        loading.value = false;
    })
    .catch(error => {
        console.log(error);
    });
}


const debouncedfetchDiscounts = debounce(fetchDiscounts, 500);
// fetchDiscounts();
</script>
