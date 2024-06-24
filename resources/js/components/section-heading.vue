<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-section-heading border-b-2 border-gray-2 pb-5";
const classes = ref(`${baseClasses}`);
</script>
