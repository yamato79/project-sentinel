<template>
    <Line :data="parsedChartData" :options="parsedChartOptions" />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Line } from "vue-chartjs";
import { Chart as ChartJS, Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, TimeScale, Filler } from "chart.js";
import "chartjs-adapter-moment";
ChartJS.register(Title, Tooltip, Legend, LineElement, PointElement, CategoryScale, LinearScale, TimeScale, Filler);

const props = defineProps({
    chartData: {
        type: Object,
        required: true
    },
    chartOptions: {
        type: Object,
        required: false,
        default: () => {}
    }
});

const parsedChartData = ref<any>({
    ...props.chartData
});

const parsedChartOptions = ref({
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            displayColors: false
        }
    },
    ...props.chartOptions
});
</script>
