<template>
    <tr :class="classes">
        <slot></slot>
    </tr>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-table-row transition-all ease-in-out duration-300";
const classes = ref(`${baseClasses}`);
</script>
