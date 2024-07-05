<script setup lang="ts">
import { ref } from "vue";
import ChartLine from "@/components/charts/chart-line.vue";
import Spinner from "@/components/spinner.vue";

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

const isLoading = ref(true);

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

(async () => {
    isLoading.value = true;

    try {
        const response = await fetch(route("api.widgets.response-time-trend", {
            website_id: props.websiteId,
            hours: props.hours,
        }));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        setTimeout(async () => {
            const { data } = await response.json();
            chartData.value.datasets = data.datasets || [];
            isLoading.value = false;
        }, 1000);
    } catch (error: any) {
        console.error(error);
        isLoading.value = false;
    }
})();
</script>

<template>
    <div class="grid grid-cols-1 gap-[2px]">
        <p class="truncate text-sm font-medium text-gray-600">
            Response Time Trend ({{ props.hours }}H)
        </p>

        <template v-if="isLoading">
            <div class="w-full h-auto sm:h-48 flex items-center justify-center">
                <Spinner size="xs" />
            </div>
        </template>

        <template v-else>
            <div>
                <ChartLine
                    :chart-options="chartOptions"
                    :chart-data="chartData"
                    class="h-auto sm:h-48"
                />
            </div>
        </template>
    </div>
</template>
