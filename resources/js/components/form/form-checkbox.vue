<script setup lang="ts">
const modelValue = defineModel();
</script>

<template>
    <input
        type="checkbox"
        class="v-form-input w-5 h-5 border-gray-200 rounded-sm focus:ring-0 focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
        v-model="modelValue"
    />
</template>
