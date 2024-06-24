<template>
    <SwitchGroup as="div" :class="[($slots.default ? 'bg-gray-50 px-4 py-2.5' : ''), 'flex items-center justify-between  rounded-md']">
        <span class="flex flex-grow flex-col">
            <SwitchDescription as="span" class="text-sm text-gray-500 line-clamp-1">
                <slot></slot>
            </SwitchDescription>
        </span>

        <Switch v-model="modelValue" :class="[modelValue ? 'bg-primary-600' : 'bg-gray-200', 'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none']">
            <span aria-hidden="true" :class="[modelValue ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']" />
        </Switch>
    </SwitchGroup>
</template>

<script setup lang="ts">
import { Switch, SwitchDescription, SwitchGroup, SwitchLabel } from "@headlessui/vue";
const modelValue = defineModel();
</script>
