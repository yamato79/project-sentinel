<template>
    <p :class="classes">
        <slot></slot>
    </p>
</template>

<script setup lang="ts">
import { ref } from "vue";

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
        validator: (value: string) => ["xs", "sm", "md", "lg", "xl"].includes(value)
    }
});

const baseClasses: String = "v-paragraph";

const colors: { [key: string]: string } = {
    default: "text-gray-600",
    muted: "text-gray-500",
    mutedLight: "text-gray-200",
    primary: "text-primary-500",
    secondary: "text-secondary-500",
    success: "text-success-500",
    warning: "text-warning-500",
    danger: "text-danger-500"
};

const sizes: { [key: string]: string } = {
    xs: "text-xs",
    sm: "text-sm",
    md: "text-base",
    lg: "text-lg",
    xl: "text-2xl"
};

const classes = ref(`${baseClasses} ${colors[props.color]} ${sizes[props.size]}`);
</script>
