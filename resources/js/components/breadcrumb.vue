<template>
    <div :class="classes">
        <div class="flex items-center">
            <Link :href="route('panel.dashboard')" class="text-gray-400 hover:text-gray-500 transition-all ease-in-out duration-300">
                <svg class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M9.293 2.293a1 1 0 011.414 0l7 7A1 1 0 0117 11h-1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-3a1 1 0 00-1-1H9a1 1 0 00-1 1v3a1 1 0 01-1 1H5a1 1 0 01-1-1v-6H3a1 1 0 01-.707-1.707l7-7z" clip-rule="evenodd" />
                </svg>
                <span class="sr-only">Home</span>
            </Link>
        </div>

        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
const baseClasses: String = "v-breadcrumb mx-auto flex w-full max-w-screen-xl space-x-4 px-4 sm:px-6 lg:px-8";
const classes = ref(`${baseClasses}`);
</script>
