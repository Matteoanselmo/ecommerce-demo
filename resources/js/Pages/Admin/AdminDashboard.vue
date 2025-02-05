<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useChart } from '../../useChart';
import LineChart from '../../Components/Charts/LineChart.vue';
import BarChart from '../../Components/Charts/BarChart.vue';
import DoughnutChart from '@/Components/Charts/DoughnutChart.vue';
import axios from 'axios';

const totalSales = ref(0);
const totalVisitors = ref(0);
const salesData = ref({});
const trafficData = ref({});
const topProductsData = ref({});
const diskSpaceData = ref({}); // Aggiunto per gestire lo spazio disco
const userCount = ref(0);
const orderCount = ref([]);
const paymenetUsage = ref([]);
// Funzione per recuperare lo spazio su disco

function fetchDiskSpace(){
    axios.get('/api/dashboard/disk-space')
        .then((res) => {
            const diskSpace = res.data;
            console.log(res.data)
            diskSpaceData.value = useChart({
                labels: ['Spazio Libero', 'Spazio Utilizzato'],
                datasets: [
                {
                    label: 'Spazio Disco',
                    backgroundColor: ['#36A2EB', '#FF6384'],
                    data: [diskSpace.free, diskSpace.used],
                    hoverOffset: 2
                },
                ],
            });
        }).catch((e) => {
            console.error(e)
        })
}

// Funzioni esistenti per vendite, traffico e prodotti
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

function getUserCount(){
    axios.get('/api/dashboard/user-count')
    .then((res) => {
        userCount.value = res.data
    }).catch((e) => {
        console.error(e)
    })
};
function getOrderCount(){
    axios.get('/api/dashboard/order-count')
    .then((res) => {
        orderCount.value = res.data
    }).catch((e) => {
        console.error(e)
    })
};
function getPaymentUsage(){
    axios.get('/api/dashboard/payment-usage')
    .then((res) => {
        console.log(res.data);
        paymenetUsage.value = res.data;
    }).catch((e) => {
        console.error(e)
    })
};

fetchSalesData();
fetchTrafficData();
fetchTopProductsData();
fetchDiskSpace();
getUserCount();
getOrderCount();
getPaymentUsage();
</script>

<template>
<Head title="Panoramica" />
<v-container fluid class="mt-5">
    <v-row>
        <v-col cols="4">
            <v-card rounded="xl" elevation="0" title="Utenti Registrati" :subtitle="'Numero Utenti registrati: ' +  userCount" height="176"></v-card>
        </v-col>
        <v-col cols="4">
            <v-card rounded="xl" elevation="0" title="Ordini Registrati" height="176">
                <v-card-subtitle v-for="(order, i) in orderCount" :key="i">
                    {{ order.status }} : {{ order.count }}
                </v-card-subtitle>
            </v-card>
        </v-col>
        <v-col cols="4">
            <v-card rounded="xl" elevation="0" title="Tipi di Pagamento" height="176">
                <v-card-subtitle v-for="(payment, i) in paymenetUsage" :key="i">
                    {{ payment.payment }} - {{ payment.count }} | {{ payment.percentage }}%
                </v-card-subtitle>
            </v-card>
        </v-col>
        <v-col cols="12" md="8">
            <v-card rounded="xl" elevation="0">
                <v-card-title>Vendite Totali</v-card-title>
                <v-card-subtitle>{{ totalSales }} €</v-card-subtitle>
                <v-card-text>
                    <line-chart :chart-data="salesData" />
                </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="4">
            <v-card rounded="xl" elevation="0">
                <v-card-title>Traffico sul Sito</v-card-title>
                <v-card-subtitle>{{ totalVisitors }} visitatori</v-card-subtitle>
                <v-card-text>
                    <line-chart :chart-data="trafficData" />
                </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="8">
            <v-card rounded="xl" elevation="0">
                <v-card-title>Prodotti più Venduti</v-card-title>
                <v-card-text>
                    <bar-chart :chart-data="topProductsData" />
                </v-card-text>
            </v-card>
        </v-col>

        <v-col cols="12" md="4">
            <v-card rounded="xl" elevation="0">
                <v-card-title>Spazio Disponibile</v-card-title>
                <v-card-text>
                    <!-- Usa un grafico per visualizzare lo spazio su disco -->
                    <doughnut-chart :chart-data="diskSpaceData" />
                </v-card-text>
            </v-card>
        </v-col>
    </v-row>
</v-container>
</template>
