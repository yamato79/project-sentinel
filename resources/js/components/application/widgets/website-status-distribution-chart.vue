<script setup lang="ts">
import { ref } from "vue";
import ChartDoughnut from "@/components/charts/chart-doughnut.vue";

const chartOptions = ref<any>({});

const chartData = ref<any>({
    datasets: []
});
</script>

<template>
    <ChartDoughnut 
        :chart-options="chartOptions"
        :chart-data="chartData"
    />
</template>
