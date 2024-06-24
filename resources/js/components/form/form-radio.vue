<template>
    <input type="radio" :class="classes" />
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-form-radio";
const classes = ref(`${baseClasses}`);
</script>
