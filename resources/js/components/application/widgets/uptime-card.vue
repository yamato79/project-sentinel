<script setup lang="ts">
import { computed } from "vue";
import Card from "./../../../components/card.vue";

const props = defineProps({
    title: {
        type: String,
        required: false,
        default: () => "Uptime Card",
    },
    currentValue: {
        type: String,
        required: false,
        default: () => "100",
    },
    previousValue: {
        type: String,
        required: false,
        default: () => "0",
    },
    valueIncrease: {
        type: Number,
        required: false,
        default: () => +100,
    },
    valueDecrease: {
        type: Number,
        required: false,
        default: () => +0,
    },
    color: {
        type: String,
        required: false,
        default: () => "default",
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

const pingClasses = computed(() => colors[props.color].pingClasses);
const iconClasses = computed(() => colors[props.color].iconClasses);
const tooltipClasses = computed(() => colors[props.color].tooltipClasses);
const tooltipIconClasses = computed(() => colors[props.color].tooltipIconClasses);
</script>

<template>
    <Card>
        <div class="flex flex-col justify-center p-4 sm:p-6">
            <dt>
                <div class="absolute rounded-md bg-gray-700 p-4">
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
                    {{ currentValue || "&nbsp;" }}
                </p>

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
            </dd>
        </div>
    </Card>
</template>
