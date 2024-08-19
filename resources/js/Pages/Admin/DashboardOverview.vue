<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useChart } from '../../useChart';
import LineChart from '../../Components/Charts/LineChart.vue';
import BarChart from '../../Components/Charts/BarChart.vue';

const totalSales = ref(0);
const totalVisitors = ref(0);
const salesData = ref({});
const trafficData = ref({});
const topProductsData = ref({});

const fetchSalesData = async () => {
const response = await axios.get('/api/dashboard/sales');
const sales = response.data;
salesData.value = useChart({
    labels: sales.map(item => `Mese ${item.month}`),
    datasets: [
    {
        label: 'Vendite',
        backgroundColor: '#4CAF50',
        data: sales.map(item => item.total_sales),
    },
    ],
});
totalSales.value = sales.reduce((acc, item) => acc + item.total_sales, 0);
};

const fetchTrafficData = async () => {
const response = await axios.get('/api/dashboard/traffic');
const traffic = response.data;
trafficData.value = useChart({
    labels: traffic.map(item => item.month),
    datasets: [
    {
        label: 'Visitatori',
        backgroundColor: '#FFC107',
        data: traffic.map(item => item.visitors),
    },
    ],
});
totalVisitors.value = traffic.reduce((acc, item) => acc + item.visitors, 0);
};

const fetchTopProductsData = async () => {
const response = await axios.get('/api/dashboard/top-products');
const products = response.data;
topProductsData.value = useChart({
    labels: products.map(item => item.name),
    datasets: [
    {
        label: 'Vendite',
        backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
        data: products.map(item => item.total_sales),
    },
    ],
});
};

onMounted(() => {
fetchSalesData();
fetchTrafficData();
fetchTopProductsData();
});
</script>

<template>
    <v-container fluid>
    <v-row>
        <v-col cols="12" md="4">
        <v-card>
            <v-card-title>Vendite Totali</v-card-title>
            <v-card-subtitle>{{ totalSales }} €</v-card-subtitle>
            <v-card-text>
            <line-chart :chart-data="salesData" />
            </v-card-text>
        </v-card>
        </v-col>

        <v-col cols="12" md="4">
        <v-card>
            <v-card-title>Traffico sul Sito</v-card-title>
            <v-card-subtitle>{{ totalVisitors }} visitatori</v-card-subtitle>
            <v-card-text>
            <line-chart :chart-data="trafficData" />
            </v-card-text>
        </v-card>
        </v-col>

        <v-col cols="12" md="4">
        <v-card>
            <v-card-title>Prodotti più Venduti</v-card-title>
            <v-card-text>
            <bar-chart :chart-data="topProductsData" />
            </v-card-text>
        </v-card>
        </v-col>
    </v-row>
    </v-container>
</template>


