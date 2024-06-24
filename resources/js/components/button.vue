<template>
    <div>
        <template v-if="props.href">
            <span :class="props.disabled ? 'opacity-50 cursor-not-allowed' : ''">
                <Link :href="props.href" :class="[classes, props.disabled ? 'pointer-events-none' : '']" v-bind="$attrs">
                    <slot></slot>
                </Link>
            </span>
        </template>

        <template v-else>
            <button :class="[classes, props.disabled ? 'opacity-50 cursor-not-allowed' : '']" :disabled="props.disabled" v-bind="$attrs">
                <slot></slot>
            </button>
        </template>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link } from "@inertiajs/vue3";

defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    color: {
        type: String,
        default: "default",
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
        default: () => {},
        required: false
    }
});

const baseClasses: String = "v-button flex w-full items-center justify-center gap-2 rounded-md border capitalize transition-all ease-in-out duration-300";

const colors: { [key: string]: string } = {
    default: "bg-gray-700 hover:bg-gray-800 border-gray-700 hover:border-gray-800 text-gray-50",
    muted: "bg-gray-200 hover:bg-gray-300 text-gray-600 border-gray-200 hover:border-gray-300",
    primary: "bg-primary-600 hover:bg-primary-500 border-primary-600 hover:border-primary-500 text-gray-50",
    secondary: "",
    success: "",
    warning: "",
    danger: "bg-danger-600 hover:bg-danger-500 border-danger-600 hover:border-danger-500 text-gray-50"
};

const sizes: { [key: string]: string } = {
    xs: "",
    sm: "px-4 py-1.5 text-sm font-semibold leading-6",
    md: "px-4 py-2 text-sm font-semibold leading-6",
    lg: "",
    xl: ""
};

const classes = computed(() => `${baseClasses} ${colors[props.color]} ${sizes[props.size]}`);
</script>
