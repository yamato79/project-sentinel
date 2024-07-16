<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { computed } from "vue";
const baseClasses: String = "v-content-foot flex items-center justify-end bg-gray-50 px-4 py-4 sm:px-6 flex gap-x-4 border-t border-gray-100";
const classes = computed(() => (`${baseClasses}`));
</script>
