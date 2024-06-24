<script setup lang="ts">
import { ref } from "vue";
import MenuTab from "@/components/menu-tab.vue";

const props = defineProps({
    websiteId: {
        type: [String, Number],
        required: true,
    },
});

const tabs = ref({
    summary: { label: "Summary", href: route("panel.websites.edit.summary", { website: props.websiteId }) },
    settings: { label: "Settings", href: route("panel.websites.edit.settings", { website: props.websiteId }) },
    back: { label: "Browse Websites", href: route("panel.websites.index") }
});
</script>

<template>
    <MenuTab :menu-items="tabs" />
</template>