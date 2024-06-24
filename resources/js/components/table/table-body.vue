<template>
    <tbody :class="classes">
        <slot></slot>
    </tbody>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-table-body relative divide-y divide-gray-200";
const classes = ref(`${baseClasses}`);
</script>
