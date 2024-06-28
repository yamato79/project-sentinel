<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import AppModelTable from "@/components/application/app-model-table.vue";
import Badge from "@/components/badge.vue";
import Button from "@/components/button.vue";
import Container from "@/components/container.vue";
import Link from "@/components/link.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import Section from "@/components/section.vue";

defineOptions({ 
    layout: PanelLayout,
});

const props = defineProps({
    websites: {
        type: Object,
        required: true,
    },
});

const modelTable = {
    /**
     * Table Columns
     */
    columns: [
        { key: "name", title: "Name", align: "left"},
        { key: "website_statuses", title: "Status", align: "left"},
        { key: "stacks", title: "Stacks", align: "left"},
        { key: "actions", title: "Actions", align: "right", fullWidth: true },
    ] as ModelTableColumn[],

    /**
     * Table Rows
     */
    rows: props.websites.data,

    /**
     * Table Meta
     */
    meta: props.websites.meta,

    /**
     * Table Links
     */
    links: props.websites.links,
};
</script>

<template>
    <Container>
        <Head title="Browse Websites" />

        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold">Websites</Paragraph>
                <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm" class="hidden lg:block">Manage your website monitors from here.</Paragraph>
            </div>
        </MountedTeleport>

        <Section>
            <AppModelTable :columns="modelTable.columns" :rows="modelTable.rows" :meta="modelTable.meta" :links="modelTable.links">
                <template #actions>
                    <Button :href="route('panel.websites.create')" color="primary">
                        <FontAwesomeIcon :icon="'fa-solid fa-plus'" :class="'text-gray-50'" />
                        New Website
                    </Button>
                </template>

                <template #column_name="{ row: website }">
                    <Link :href="route('panel.websites.edit', { website: website.website_id })" class="line-clamp-1">
                        {{ website.name }}
                    </Link>

                    <Paragraph color="muted" size="sm" class="line-clamp-1">
                        {{ website.address || "Address unavailable." }}
                    </Paragraph>
                </template>

                <template #column_website_statuses="{ row: website }">
                    <div class="whitespace-nowrap">
                        <Badge :color="website.website_status.color">
                            {{  website.website_status.name }}
                        </Badge>
                    </div>
                </template>

                <template #column_stacks="{ row: website }">
                    <div class="flex items-center justify-center gap-2">
                        <template v-for="(stack, stackIndex) in website.stacks" :key="'stack_' + stackIndex">
                            <div class="whitespace-nowrap">
                                <Badge color="muted" :icon="false">
                                    {{ stack.name }}
                                </Badge>
                            </div>
                        </template>
                    </div>
                </template>

                <template #column_actions="{ row: website }">
                    <div class="flex justify-end gap-4">
                        <Link :href="route('panel.websites.edit', { website: website.website_id })">
                            Manage<span class="sr-only">, {{ website.name }}</span>
                        </Link>
                    </div>
                </template>
            </AppModelTable>
        </Section>
    </Container>
</template>
