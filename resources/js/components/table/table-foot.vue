<template>
    <tfoot :class="classes">
        <slot></slot>
    </tfoot>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-table-foot bg-gray-50";
const classes = ref(`${baseClasses}`);
</script>
