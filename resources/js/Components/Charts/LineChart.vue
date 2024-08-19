
<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, LineController, LineElement, PointElement, LinearScale, Title, CategoryScale } from 'chart.js';

Chart.register(LineController, LineElement, PointElement, LinearScale, Title, CategoryScale);

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
    type: 'line',
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
