<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-section-group grid grid-cols-1 gap-2";
const classes = ref(`${baseClasses}`);
</script>
