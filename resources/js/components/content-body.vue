<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-content-body p-4 sm:p-6";
const classes = ref(`${baseClasses}`);
</script>
