<template>
    <div>
        <label :class="classes" v-bind="$attrs">
            <slot></slot>

            <template v-if="props.required">
                <span class="text-danger-500 ml-1">*</span>
            </template>
        </label>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";

defineOptions({
    inheritAttrs: false
});

const props = defineProps({
    required: {
        type: Boolean,
        default: () => false,
        required: false
    }
});

const baseClasses: String = "v-form-label inline-block font-medium text-sm text-gray-700";
const classes = ref(`${baseClasses}`);
</script>
