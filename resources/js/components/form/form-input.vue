<template>
    <input :class="classes" v-model="modelValue" />
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-form-input w-full block text-sm leading-6 rounded-md text-gray-700 border-gray-200 focus:border-primary-600 focus:ring-primary-600 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed";
const classes = ref(`${baseClasses}`);
const modelValue = defineModel();
</script>
