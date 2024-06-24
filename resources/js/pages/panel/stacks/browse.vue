<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import AppModelTable from '@/components/application/app-model-table.vue';
import Badge from "@/components/badge.vue";
import Button from '@/components/button.vue';
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import Container from "@/components/container.vue";
import Link from "@/components/link.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import Section from "@/components/section.vue";

const props = defineProps({
    stacks: {
        type: Object,
        required: true,
    },
});

defineOptions({ 
    layout: PanelLayout,
});

const modelTable = {
    /**
     * Table Columns
     */
    columns: [
        { key: 'name',           title: 'Name',          align: 'left',  fullWidth: false},
        { key: 'description',    title: 'Description',   align: 'left',  fullWidth: true},
        { key: 'websites_count', title: '# of Websites', align: 'right', fullWidth: true},
        { key: 'users_count',    title: '# of Members',  align: 'right', fullWidth: true},
        { key: 'actions',        title: 'Actions',       align: 'right', fullWidth: true },
    ],

    /**
     * Table Rows
     */
    rows: props.stacks.data,

    /**
     * Table Meta
     */
    meta: props.stacks.meta,

    /**
     * Table Links
     */
    links: props.stacks.links,
};
</script>

<template>
    <Container>
        <Head title="Browse Stacks" />

        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold">Stacks</Paragraph>
                <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm" class="hidden lg:block">Group websites and users within stacks.</Paragraph>
            </div>
        </MountedTeleport>

        <MountedTeleport to="#breadcrumbs">
            <BreadcrumbItem :href="route('panel.stacks.index')"> Stacks </BreadcrumbItem>
        </MountedTeleport>

        <Section>
            <AppModelTable :columns="modelTable.columns" :rows="modelTable.rows" :meta="modelTable.meta" :links="modelTable.links">
                <template #actions>
                    <Button :href="route('panel.stacks.create')" color="primary">
                        <FontAwesomeIcon :icon="'fa-solid fa-plus'" :class="'text-gray-50'" aria-hidden="true" />
                        New Stack
                    </Button>
                </template>

                <template #column_name="{ row: stack }">
                    <Link :href="route('panel.stacks.edit', { stack: stack.stack_id })" class="line-clamp-1">
                        {{ stack.name }}
                    </Link>
                </template>

                <template #column_actions="{ row: stack }">
                    <div class="flex justify-end gap-4">
                        <Link :href="route('panel.stacks.edit', { stack: stack.stack_id })">
                            Manage<span class="sr-only">, {{ stack.name }}</span>
                        </Link>
                    </div>
                </template>
            </AppModelTable>
        </Section>
    </Container>
</template>
