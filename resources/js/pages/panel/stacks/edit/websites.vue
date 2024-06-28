<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import StackEditIndexLayout from "./index.vue";
import Card from "@/components/card.vue";
import FormToggle from "@/components/form/form-toggle.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Link from "@/components/link.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGridContainer from "@/components/section-grid-container.vue";
import SectionGridContent from "@/components/section-grid-content.vue";
import SectionGridSidebar from "@/components/section-grid-sidebar.vue";
import SectionGrid from "@/components/section-grid.vue";
import TableBody from "@/components/table/table-body.vue";
import TableData from "@/components/table/table-data.vue";
import TableHead from "@/components/table/table-head.vue";
import TableHeader from "@/components/table/table-header.vue";
import TableRow from "@/components/table/table-row.vue";
import Table from "@/components/table/table.vue";

defineOptions({ 
    layout: [
        PanelLayout,
        StackEditIndexLayout,
    ],
});

const props = defineProps({
    availableWebsites: {
        type: Object,
        required: false,
        default: () => {},
    },
    canEdit: {
        type: Boolean,
        required: false,
        default: () => false,
    },
    stack: {
        type: Object,
        required: false,
        default: () => {},
    },
    websites: {
        type: Object,
        required: false,
        default: () => {},
    },
});

const toggleWebsite = (value: boolean, website_id: number) => {
    const target = (value)
        ? route("panel.stacks.websites.attach", { stack: props.stack.data.stack_id })
        : route("panel.stacks.websites.detach", { stack: props.stack.data.stack_id });

    useForm({ website_id })
        .post(target, {
            preserveScroll: true,
            onSuccess: (response) => {
                console.log("TODO: Add success toast notification.", response);
            },
            onError: (errors) => {
                Object.keys(errors).forEach((key) => {
                    const error = errors[key];
                    console.error("TODO: Add error toast notification.", error);
                });
            },
        });
};

const websiteIds = computed(
    () => (props.websites.data)
        ? props.websites.data.map(({ website_id }: any) => website_id)
        : []
);
</script>

<template>
    <Head title="Stack Websites" />
    
    <MountedTeleport to="#page-title">
        <div class="flex flex-wrap items-center gap-4">
            <Paragraph class="font-semibold">{{ props.stack.data.name }}</Paragraph>
            <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
            <Paragraph color="muted" size="sm" class="hidden lg:block">{{  props.stack.data.description }}</Paragraph>
        </div>
    </MountedTeleport>

    <SectionGridContainer>
        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">Stack Websites</Heading>
                <Paragraph color="muted" size="sm">Toggle which of your websites are included in the stack.</Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <Table>
                        <TableHead>
                            <TableRow>
                                <TableHeader>Name</TableHeader>
                                <TableHeader>Address</TableHeader>

                                <template v-if="canEdit">
                                    <TableHeader class="text-right">&nbsp;</TableHeader>
                                </template>
                            </TableRow>
                        </TableHead>
                        
                        <TableBody>
                            <template v-for="(website, websiteIndex) in availableWebsites.data" :key="'website_' + websiteIndex">
                                <TableRow>
                                    <TableData class="whitespace-nowrap">
                                        <Link :href="route('panel.websites.edit', { website: website.website_id })" class="line-clamp-1">
                                            {{ website.name }}
                                        </Link>
                                    </TableData>

                                    <TableData>
                                        {{ website.address || "Address unavailable." }}
                                    </TableData>

                                    <template v-if="canEdit">
                                        <TableData>
                                            <Form>
                                                <FormToggle
                                                    id="is_active"
                                                    name="is_active"
                                                    :model-value="websiteIds.includes(website.website_id)"
                                                    @toggle="($event) => toggleWebsite($event.value, website.website_id)"
                                                />
                                            </Form>
                                        </TableData>
                                    </template>
                                </TableRow>
                            </template>

                            <template v-if="!availableWebsites.data || !availableWebsites.data.length">
                                <TableRow>
                                    <TableData align="center" colspan="100%">
                                        <Paragraph size="sm" class="italic">
                                            No Websites Found.
                                        </Paragraph>
                                    </TableData>
                                </TableRow>
                            </template>
                        </TableBody>
                    </Table>
                </Card>
            </SectionGridContent>
        </SectionGrid>
    </SectionGridContainer>
</template>
