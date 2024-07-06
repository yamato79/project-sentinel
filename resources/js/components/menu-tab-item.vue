<template>
    <Link :href="props.href" :class="linkClasses(isActive)" v-bind="$attrs">
        <slot name="icon"></slot>
        <slot></slot>
    </Link>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

defineOptions({
    inheritAttrs: false
});

const page = usePage();

const props = defineProps({
    href: {
        type: String,
        required: true
    }
});

const isActive = computed(() => {
    const currentUrl = new URL(window.location.origin + page.url);
    const linkUrl = new URL(props.href, currentUrl.origin);

    return (currentUrl.pathname === linkUrl.pathname);
});

const linkClasses = (isActive: boolean) => [
    isActive ? "border-primary-500 text-primary-600" : "border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700", 
    "flex items-center gap-2 whitespace-nowrap border-b-2 py-4 text-sm font-medium transition-all ease-in-out duration-300"
];
</script>
