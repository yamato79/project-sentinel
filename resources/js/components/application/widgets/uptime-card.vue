<script setup lang="ts">
import { computed, ref, onMounted, onUnmounted } from "vue";
import Card from "@/components/card.vue";
import Spinner from "@/components/spinner.vue";

const props = defineProps({
    websiteId: {
        type: Number,
        required: false,
        default: () => null
    },
    title: {
        type: String,
        required: false,
        default: () => "Uptime Card",
    },
    hours: {
        type: Number,
        required: true,
    },
});

const colors: { [key: string]: { [key: string]: string } } = {
    default: {
        pingClasses: "bg-gray-400",
        iconClasses: "bg-gray-500",
        tooltipClasses: "text-gray-600",
        tooltipIconClasses: "text-gray-500"
    },
    success: {
        pingClasses: "bg-success-400",
        iconClasses: "bg-success-500",
        tooltipClasses: "text-success-600",
        tooltipIconClasses: "text-success-500"
    },
    warning: {
        pingClasses: "bg-warning-400",
        iconClasses: "bg-warning-500",
        tooltipClasses: "text-warning-600",
        tooltipIconClasses: "text-warning-500"
    },
    danger: {
        pingClasses: "bg-danger-400",
        iconClasses: "bg-danger-500",
        tooltipClasses: "text-danger-600",
        tooltipIconClasses: "text-danger-500"
    }
};

const isLoading = ref(true);
const currentValue = ref("--");
const previousValue = ref("--");
const valueIncrease = ref(0);
const valueDecrease = ref(0);
const color = ref("default");
const tooltipColor = ref("default");

const pingClasses = computed(() => colors[color.value].pingClasses);
const iconClasses = computed(() => colors[color.value].iconClasses);
const tooltipClasses = computed(() => colors[tooltipColor.value].tooltipClasses);
const tooltipIconClasses = computed(() => colors[tooltipColor.value].tooltipIconClasses);

// Create a ref to hold the interval ID
const intervalId = ref<any>(null);

const getData = async () => {
    try {
        const params = {
            website_id: props.websiteId,
            hours: props.hours,
        } as { [key: string]: string | number };

        if (props.websiteId) {
            params.website = props.websiteId;
        } else {
            delete params.website;
        }

        const response = await fetch(route("api.widgets.uptime-card", params));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        const { data } = await response.json();

        if (data) {
            currentValue.value = `${data.currentValue.toFixed(0)}%`;
            previousValue.value = `${data.previousValue.toFixed(0)}%`;
            valueIncrease.value = data.valueIncrease;
            valueDecrease.value = data.valueDecrease;
            color.value = data.color;
            tooltipColor.value = data.tooltipColor;
        }
    } catch (error: any) {
        console.error(error);
    }

    isLoading.value = false;
};

const startReloading = () => {
    getData(); // Immediately fetch data for the first time
    intervalId.value = setInterval(getData, 60000); // 60 seconds
};

// Hook to start interval on component mount
onMounted(() => {
    startReloading();
});

// Hook to clear interval on component unmount
onUnmounted(() => {
    if (intervalId.value) {
        clearInterval(intervalId.value);
    }
});
</script>

<template>
    <Card>
        <div class="flex flex-col justify-center p-4 sm:p-6">
            <dt>
                <div class="absolute rounded bg-primary-900 p-4">
                    <div class="p-0.5">
                        <span class="relative flex h-3 w-3">
                            <span :class="[pingClasses, 'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75']"></span>
                            <span :class="[iconClasses, 'relative inline-flex rounded-full h-3 w-3']"></span>
                        </span>
                    </div>
                </div>

                <p class="ml-16 truncate text-sm font-medium text-gray-500">
                    {{ title }}
                </p>
            </dt>

            <dd class="ml-16 flex items-baseline">
                <p class="flex items-center text-2xl font-semibold text-gray-900">
                    <template v-if="isLoading">
                        <Spinner size="xs" /> &nbsp;
                    </template>

                    <template v-else>
                        {{ currentValue || "&nbsp;" }}
                    </template>
                </p>

                <template v-if="!isLoading">
                    <p :class="[tooltipClasses, 'ml-2 flex items-baseline text-sm font-semibold']">
                        <template v-if="valueIncrease && +valueIncrease > 1">
                            <svg :class="[tooltipIconClasses, 'h-5 w-5 flex-shrink-0 self-center']" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" />
                            </svg>

                            <span class="sr-only"> Increased by </span>
                            {{ valueIncrease.toFixed(0) }}
                        </template>

                        <template v-if="valueDecrease && +valueDecrease > 1">
                            <svg :class="[tooltipIconClasses, 'h-5 w-5 flex-shrink-0 self-center']" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" />
                            </svg>

                            <span class="sr-only"> Decreased by </span>
                            {{ valueDecrease.toFixed(0) }}
                        </template>
                    </p>
                </template>
            </dd>
        </div>
    </Card>
</template>
