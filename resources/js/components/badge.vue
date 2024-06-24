<template>
    <span :class="containerClasses">
        <svg :class="iconClasses" viewBox="0 0 6 6" aria-hidden="true">
            <circle cx="3" cy="3" r="3" />
        </svg>
        <slot></slot>
    </span>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
    color: {
        type: String,
        default: "default",
        required: false,
        validator: (value: string) => ["default", "muted", "primary", "secondary", "success", "warning", "danger"].includes(value)
    },
    size: {
        type: String,
        default: "md",
        required: false,
        validator: (value: string) => ["sm", "md"].includes(value)
    }
});

const iconColors: { [key: string]: string } = {
    default: "fill-gray-700",
    muted: "fill-gray-500",
    primary: "fill-primary-500",
    secondary: "fill-secondary-500",
    success: "fill-success-500",
    warning: "fill-warning-500",
    danger: "fill-danger-500"
};

const containerColors: { [key: string]: string } = {
    default: "bg-gray-300 text-gray-900",
    muted: "bg-gray-100 text-gray-700",
    primary: "bg-primary-100 text-primary-700",
    secondary: "bg-secondary-100 text-secondary-700",
    success: "bg-success-100 text-success-700",
    warning: "bg-warning-100 text-warning-700",
    danger: "bg-danger-100 text-danger-700"
};

const iconSizes: { [key: string]: string } = {
    sm: "h-1.5 w-1.5",
    md: "h-1.5 w-1.5"
};

const containerSizes: { [key: string]: string } = {
    sm: "gap-x-1.5 px-3 py-1.5 text-xs font-medium",
    md: "gap-x-1.5 px-3 py-1.5 text-xs font-medium"
};

const iconClasses = computed(() => `${iconColors[props.color]} ${iconSizes[props.size]}`);
const containerClasses = computed(() => `inline-flex items-center rounded-md ${containerColors[props.color]} ${containerSizes[props.size]}`);
</script>
