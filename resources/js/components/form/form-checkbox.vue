<script setup lang="ts">
const modelValue = defineModel();
</script>

<template>
    <input
        type="checkbox"
        class="v-form-input w-5 h-5 rounded border-gray-200 text-primary-600 focus:border-primary-600 focus:ring-primary-600 disabled:opacity-50 disabled:cursor-not-allowed"
        v-model="modelValue"
    />
</template>
