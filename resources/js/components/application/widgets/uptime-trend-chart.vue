<script setup lang="ts">
import { ref } from "vue";
import ChartLine from "@/components/charts/chart-line.vue";

const chartOptions = ref({
    scales: {
        x: {
            type: "time",
            time: {
                unit: "hour"
            }
        },
        y: {
            beginAtZero: true,
            ticks: {
                stepSize: 10
            }
        }
    },
});

const chartData = ref({
    datasets: [],
});
</script>

<template>
    <ChartLine
        :chart-options="chartOptions"
        :chart-data="chartData"
    />
</template>
