<template>
    <input type="checkbox" :class="classes" />
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-form-checkbox";
const classes = ref(`${baseClasses}`);
</script>
