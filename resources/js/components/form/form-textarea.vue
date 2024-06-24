<template>
    <textarea :class="classes"><slot></slot></textarea>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-form-textarea";
const classes = ref(`${baseClasses}`);
</script>
