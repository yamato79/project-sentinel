<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from '@/components/paragraph.vue';
import Section from "@/components/section.vue";
import Tabs from "./_tabs.vue";

const emit = defineEmits(["update-stack"]);

const props = defineProps({
    stack: {
        type: Object,
        required: false,
        default: () => {},
    },
});

defineOptions({ 
    layout: PanelLayout,
});
</script>

<template>
    <Container>
        <Head title="Stack Members" />
        
        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold">{{ props.stack.data.name }}</Paragraph>
                <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm" class="hidden lg:block">{{  props.stack.data.description }}</Paragraph>
            </div>
        </MountedTeleport>

        <MountedTeleport to="#breadcrumbs">
            <BreadcrumbItem :href="route('panel.stacks.index', { stack: props.stack.data.stack_id })">Stacks</BreadcrumbItem>
            <BreadcrumbItem :href="route('panel.stacks.edit', { stack: props.stack.data.stack_id })">{{ props.stack.data.name }}</BreadcrumbItem>
            <BreadcrumbItem :href="route('panel.stacks.edit.members', { stack: props.stack.data.stack_id })">Members</BreadcrumbItem>
        </MountedTeleport>

        <Section>
            <Tabs :stack-id="props.stack.data.stack_id" />

            <div class="space-y-10 divide-y-2 divide-gray-200 divide-dashed">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                    <div>
                        <Heading :size="5">Stack Members</Heading>
                        <Paragraph color="muted" size="sm">Manage which of your members are included in the stack.</Paragraph>
                    </div>

                    <Card class="md:col-span-2">
                        [ MEMBERS ]
                    </Card>
                </div>
            </div>
        </Section>
    </Container>
</template>
