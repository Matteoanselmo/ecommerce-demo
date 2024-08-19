// useChart.js
import { reactive } from 'vue';

export function useChart(data) {
    return reactive({
        labels: data.labels,
        datasets: data.datasets.map(dataset => ({
            ...dataset,
            borderColor: dataset.backgroundColor,
            fill: false,
        })),
    });
}
