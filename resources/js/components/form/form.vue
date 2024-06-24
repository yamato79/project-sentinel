<template>
    <form :class="classes">
        <slot></slot>
    </form>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-form";
const classes = ref(`${baseClasses}`);
</script>
