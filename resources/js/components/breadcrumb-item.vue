<template>
    <div class="flex items-center mr-4">
        <svg class="h-full w-6 flex-shrink-0 text-gray-200" viewBox="0 0 24 44" preserveAspectRatio="none" fill="currentColor" aria-hidden="true">
            <path d="M.293 0l22 22-22 22h1.414l22-22-22-22H.293z" />
        </svg>

        <Link :href="props.href" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700 transition-all ease-in-out duration-300" v-bind="$attrs">
            <slot></slot>
        </Link>
    </div>
</template>

<script setup lang="ts">
import { Link } from "@inertiajs/vue3";

defineOptions({ inheritAttrs: false });
const props = defineProps<{href: string;}>();
</script>
