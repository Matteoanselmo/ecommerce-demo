<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, DoughnutController, ArcElement, CategoryScale, LinearScale, Title, Tooltip, Legend } from 'chart.js';

// Registrazione dei componenti necessari per il grafico doughnut e le tooltip/legende
Chart.register(DoughnutController, ArcElement, CategoryScale, LinearScale, Title, Tooltip, Legend);

const props = defineProps({
    chartData: {
        type: Object,
        required: true,
    },
});

const canvas = ref(null);
let chartInstance;

onMounted(() => {
    // Crea una nuova istanza di Chart con tipo "doughnut"
    canvas.value.height = 300;
    chartInstance = new Chart(canvas.value, {
        type: 'doughnut',  // Modifica il tipo di grafico a doughnut
        data: props.chartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                // Abilita le tooltip
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function (tooltipItem) {
                            let label = tooltipItem.label || '';
                            const value = tooltipItem.raw || 0;
                            return `${label}: ${value} MB`; // Personalizzazione del valore mostrato
                        },
                    },
                },
                // Abilita la legenda
                legend: {
                    display: true,
                    position: 'top', // Puoi scegliere 'top', 'bottom', 'left', 'right'
                },
            },
        },
    });
});

// Osserva i cambiamenti dei dati e aggiorna il grafico
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
