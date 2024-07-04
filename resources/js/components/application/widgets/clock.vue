<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
import Paragraph from "@/components/paragraph.vue";

// Bro, we're setting up the time reference
const currentTime = ref(new Date());

// Function to update the time, bro
const updateTime = () => {
    currentTime.value = new Date();
};

// Setting up the interval, bro
onMounted(() => {
    const interval = setInterval(updateTime, 1000);

    // Clear the interval when the component is unmounted, bro
    onUnmounted(() => {
        clearInterval(interval);
    });
});
</script>

<template>
    <Paragraph size="sm" color="muted">{{ currentTime.toLocaleTimeString('en-US', { timeZone: 'Asia/Manila' }) }}</Paragraph>
</template>
