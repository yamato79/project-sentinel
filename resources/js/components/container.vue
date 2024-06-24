<template>
    <section :class="classes">
        <slot></slot>
    </section>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-container w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8";
const classes = ref(`${baseClasses}`);
</script>
