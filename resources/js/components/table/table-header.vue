<template>
    <th :class="classes">
        <slot></slot>
    </th>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-table-header px-6 py-4 text-left text-xs uppercase font-semibold text-gray-500 whitespace-nowrap";
const classes = ref(`${baseClasses}`);
</script>
