<script setup lang="ts">
import Container from "@/components/container.vue";
import MenuTab from "@/components/menu-tab.vue";
import Section from "@/components/section.vue";

const props = defineProps({
    tabs: {
        type: Array,
        required: false,
        default: () => [],
    },
});
</script>

<template>
    <Container>
        <Section>
            <MenuTab
                :menu-items="props.tabs"
                :backLink="route('panel.stacks.index')"
            />
            <slot></slot>
        </Section>
    </Container>
</template>
