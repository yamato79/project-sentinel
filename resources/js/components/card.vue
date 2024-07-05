<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-card relative bg-white rounded shadow z-10";
const classes = ref(`${baseClasses}`);
</script>
