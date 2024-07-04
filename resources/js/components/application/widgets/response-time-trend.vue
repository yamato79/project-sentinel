<script setup lang="ts">
import { ref } from "vue";
import ChartLine from "@/components/charts/chart-line.vue";

const props = defineProps({
    websiteId: {
        type: Number,
        required: false,
        default: () => null
    },
    hours: {
        type: Number,
        required: true,
    },
});

const chartOptions = ref({
    plugins: {
        legend: {
            display: true,
            position: "top",
            align: "center",
        },
        tooltip: {
            displayColors: false
        },
    },
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
                stepSize: 20
            }
        }
    },
});

const chartData = ref({
    labels: [],
    datasets: [],
});

const isLoading = ref(true);

const getData = async () => {
    try {
        const response = await fetch(route("api.widgets.response-time-trend", {
            website_id: props.websiteId,
            hours: props.hours,
        }));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        const { data } = await response.json();

        if (data) {
            chartData.value.datasets = data.datasets;
        }

        isLoading.value = false;
    } catch (error: any) {
        console.error(error);
        isLoading.value = false;
    }
};

getData();
</script>

<template>
    <ChartLine
        v-if="!isLoading"
        :chart-options="chartOptions"
        :chart-data="chartData"
        class="h-auto sm:h-48"
    />
</template>
