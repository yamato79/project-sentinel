<template>
    <section :class="classes">
        <slot></slot>
    </section>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-section grid grid-cols-1 gap-12 py-6 sm:py-10";
const classes = ref(`${baseClasses}`);
</script>
