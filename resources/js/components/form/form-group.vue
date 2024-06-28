<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-form-group flex flex-col gap-2";
const classes = ref(`${baseClasses}`);
</script>
