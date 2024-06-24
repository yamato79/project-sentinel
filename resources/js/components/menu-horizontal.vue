<template>
    <div :class="classes">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
const baseClasses: String = "v-menu-horizontal -mx-2 space-y-2";
const classes = ref(`${baseClasses}`);
</script>
