<template>
    <td :class="classes" v-bind="$attrs">
        <slot></slot>
    </td>
</template>

<script setup lang="ts">
import { computed } from "vue";
const baseClasses: String = "v-table-data px-6 py-4 text-sm first:font-semibold text-gray-500";
const classes = computed(() => `${baseClasses}`);
</script>
