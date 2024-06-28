<template>
    <Link :href="props.href" :class="linkClasses(isActive)" v-bind="$attrs">
        <slot></slot>

        <template v-if="isActive">
            <span aria-hidden="true" class="absolute inset-x-0 bottom-0 h-0.5 bg-gray-700"></span>
        </template>
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

const linkClasses = (isActive: boolean) => [isActive ? "text-gray-900 bg-white" : "text-gray-500 hover:text-gray-700 bg-white", "max-w-[20%] group relative min-w-0 flex-1 overflow-hidden px-4 py-2.5 text-center text-sm font-medium focus:z-10 font-medium whitespace-nowrap transition-all ease-in-out duration-300"];
</script>
