<template>
    <template v-if="isMounted">
        <Teleport :to="props.to">
            <slot></slot>
        </Teleport>
    </template>
</template>

<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";

const props = defineProps({
    to: {
        type: String,
        required: true
    }
});

let isMounted = ref(false);

onMounted(() => {
    nextTick(() => {
        isMounted.value = true;
    });
});
</script>
