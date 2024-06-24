<template>
    <thead :class="classes">
        <slot></slot>
    </thead>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-table-head bg-gray-50";
const classes = ref(`${baseClasses}`);
</script>
