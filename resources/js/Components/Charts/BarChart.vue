<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, BarController, BarElement, CategoryScale, LinearScale, Title } from 'chart.js';

Chart.register(BarController, BarElement, CategoryScale, LinearScale, Title);

const props = defineProps({
    chartData: {
    type: Object,
    required: true,
    },
});

const canvas = ref(null);
let chartInstance;

onMounted(() => {
    chartInstance = new Chart(canvas.value, {
    type: 'bar',
    data: props.chartData,
    options: {
        responsive: true,
        maintainAspectRatio: false,
    },
    });
});

watch(
    () => props.chartData,
    (newData) => {
    if (chartInstance) {
        chartInstance.data = newData;
        chartInstance.update();
    }
    }
);
</script>

<template>
    <v-responsive>
    <canvas ref="canvas"></canvas>
    </v-responsive>
</template>


