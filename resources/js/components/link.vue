<template>
    <span :class="props.disabled ? 'opacity-50 cursor-not-allowed' : ''">
        <Link :href="props.href" :class="classes" v-bind="$attrs">
            <slot></slot>
        </Link>
    </span>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";

defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    color: {
        type: String,
        default: "primary",
        required: false,
        validator: (value: string) => ["default", "muted", "primary", "secondary", "success", "warning", "danger"].includes(value)
    },
    disabled: {
        type: Boolean,
        default: () => false,
        required: false
    },
    size: {
        type: String,
        default: "md",
        required: false,
        validator: (value: string) => ["xs", "sm", "md", "lg", "xl"].includes(value)
    },
    href: {
        type: String,
        required: true
    }
});

const baseClasses: String = "v-link transition-all ease-in-out duration-300";

const colors: { [key: string]: string } = {
    default: "",
    muted: "",
    primary: "text-primary-600 hover:text-primary-500",
    secondary: "",
    success: "",
    warning: "",
    danger: ""
};

const sizes: { [key: string]: string } = {
    xs: "",
    sm: "",
    md: "font-semibold",
    lg: "",
    xl: ""
};

const classes = ref(`${baseClasses} ${colors[props.color]} ${sizes[props.size]} ${props.disabled ? "pointer-events-none" : ""}`);
</script>
