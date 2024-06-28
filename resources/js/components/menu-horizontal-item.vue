<template>
    <li>
        <Link :href="props.href" :class="linkClasses(isActive)" v-bind="$attrs">
            <FontAwesomeIcon :icon="props.icon" :class="iconClasses(isActive)" aria-hidden="true" />
            {{ props.name }}
        </Link>
    </li>
</template>

<script setup lang="ts">
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    name: {
        type: String,
        required: true
    },
    href: {
        type: String,
        required: true
    },
    icon: {
        type: String,
        required: true
    }
});

const page = usePage();

const isActive = computed(() => {
    const currentUrl = new URL(window.location.origin + page.url);
    const linkUrl = new URL(props.href, currentUrl.origin);

    return (currentUrl.pathname === linkUrl.pathname) || currentUrl.pathname.startsWith(linkUrl.pathname);
});

const linkClasses = (isActive: boolean) => [isActive ? "bg-gray-700 text-gray-50 border-gray-700" : "text-gray-500 hover:text-gray-700 hover:bg-gray-200 border-transparent hover:border-gray-200", "group flex items-center gap-x-3 rounded-md px-4 p-2 border-2 text-sm leading-6 font-semibold transition-all ease-in-out duration-300"];
const iconClasses = (isActive: boolean) => [isActive ? "text-gray-50" : "text-gray-500 group-hover:text-gray-700", "w-[1.125rem] h-[1.125rem] shrink-0 transition-all ease-in-out duration-300"];
</script>
