<template>
    <svg :class="classes" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
    </svg>
</template>

<script setup lang="ts">
import { ref } from "vue";

const props = defineProps({
    color: {
        type: String,
        default: "default",
        required: false,
        validator: (value: string) => ["default", "primary", "white"].includes(value)
    },
    size: {
        type: String,
        default: "md",
        required: false,
        validator: (value: string) => ["xs", "sm", "md", "lg", "xl"].includes(value)
    }
});

const baseClasses: String = "v-spinner animate-spin";

const colors: { [key: string]: string } = {
    default: "text-gray-700",
    primary: "text-primary-500",
    white: "text-gray-50"
};

const sizes: { [key: string]: string } = {
    xs: "w-4 h-4",
    sm: "w-5 h-5",
    md: "w-6 h-6",
    lg: "w-7 h-7",
    xl: "w-8 h-8"
};

const classes = ref(`${baseClasses} ${colors[props.color]} ${sizes[props.size]}`);
</script>
