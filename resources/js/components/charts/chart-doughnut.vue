<template>
    <Doughnut :data="parsedChartData" :options="parsedChartOptions" />
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Doughnut } from "vue-chartjs";
import { Chart as ChartJS, Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale } from "chart.js";
ChartJS.register(Title, Tooltip, Legend, ArcElement, CategoryScale, LinearScale);

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

const parsedChartData = ref({
    ...props.chartData
});

const parsedChartOptions = ref({
    responsive: true,
    plugins: {
        legend: {
            // labels: {
            //     padding: 50,
            // },
            // title: {
            //     padding: 10,
            // },
        },
        tooltip: {
            displayColors: false
        }
    },
    borderAlign: "inner",
    ...props.chartOptions
});
</script>
