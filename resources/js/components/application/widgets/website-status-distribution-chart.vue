<script setup lang="ts">
import { ref } from "vue";
import ChartBar from "@/components/charts/chart-bar.vue";

const chartOptions = ref<any>({
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            displayColors: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 1
            }
        }
    },
});

const chartData = ref<any>({
    datasets: []
});
</script>

<template>
    <ChartBar 
        :chart-options="chartOptions"
        :chart-data="chartData"
    />
</template>
