<script setup lang="ts">
import { ref } from "vue";
import MenuTab from "@/components/menu-tab.vue";

const props = defineProps({
    stackId: {
        type: [String, Number],
        required: true,
    },
});

const tabs = ref({
    websites: { label: "Websites", href: route("panel.stacks.edit.websites", { stack: props.stackId }) },
    members: { label: "Members", href: route("panel.stacks.edit.members", { stack: props.stackId }) },
    settings: { label: "Settings", href: route("panel.stacks.edit.settings", { stack: props.stackId }) },
    back: { label: "Browse Stacks", href: route("panel.stacks.index") }
});
</script>

<template>
    <MenuTab :menu-items="tabs" />
</template>