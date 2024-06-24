<template>
    <component :is="component" :class="classes">
        <slot></slot>
    </component>
</template>

<script setup lang="ts">
import { computed } from "vue";

const props = defineProps({
    color: {
        type: String,
        default: "default",
        required: false,
        validator: (value: string) => ["default", "muted"].includes(value)
    },
    size: {
        type: Number,
        default: 1,
        required: false,
        validator: (value: number) => [1, 2, 3, 4, 5, 6].includes(value)
    }
});

const baseClasses: String = "v-heading";

const colors: { [key: string]: string } = {
    default: "text-gray-700",
    muted: "text-gray-500"
};

const sizes: { [key: number]: string } = {
    1: "text-4xl font-bold leading-9 tracking-tight uppercase",
    2: "text-3xl font-extrabold tracking-tight uppercase",
    3: "text-2xl font-semibold tracking-tight uppercase",
    4: "text-base font-semibold tracking-tight capitalize",
    5: "text-base font-medium tracking-tight",
    6: "text-base"
};

const component = computed(() => `h${props.size}`);
const classes = computed(() => (`${baseClasses} ${colors[props.color]} ${sizes[props.size]}`));
</script>
