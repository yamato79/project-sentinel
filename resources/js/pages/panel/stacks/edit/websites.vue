<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import FormToggle from "@/components/form/form-toggle.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Link from "@/components/link.vue";
import Paragraph from '@/components/paragraph.vue';
import Section from "@/components/section.vue";
import TableBody from "@/components/table/table-body.vue";
import TableData from "@/components/table/table-data.vue";
import TableHead from "@/components/table/table-head.vue";
import TableHeader from "@/components/table/table-header.vue";
import TableRow from "@/components/table/table-row.vue";
import Table from "@/components/table/table.vue";
import Tabs from "./_tabs.vue";

const emit = defineEmits(["update-stack"]);

const props = defineProps({
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

defineOptions({ 
    layout: PanelLayout,
});
</script>

<template>
    <Container>
        <Head title="Stack Websites" />
        
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
            <BreadcrumbItem :href="route('panel.stacks.edit.websites', { stack: props.stack.data.stack_id })">Websites</BreadcrumbItem>
        </MountedTeleport>

        <Section>
            <Tabs :stack-id="props.stack.data.stack_id" />

            <div class="space-y-10 divide-y-2 divide-gray-200 divide-dashed">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                    <div>
                        <Heading :size="5">Stack Websites</Heading>
                        <Paragraph color="muted" size="sm">Toggle which of your websites are included in the stack.</Paragraph>
                    </div>

                    <Card class="md:col-span-2">
                        <Table>
                            <TableHead>
                                <TableRow>
                                    <TableHeader>Name</TableHeader>
                                    <TableHeader class="text-right">&nbsp;</TableHeader>
                                </TableRow>
                            </TableHead>
                            
                            <TableBody>
                                <template v-for="(website, websiteIndex) in websites.data" :key="'website_' + websiteIndex">
                                    <TableRow>
                                        <TableData class="w-full whitespace-nowrap">
                                            <Link :href="route('panel.websites.edit', { website: website.website_id })" class="line-clamp-1">
                                                {{ website.name }}
                                            </Link>

                                            <Paragraph color="muted" size="sm" class="line-clamp-1">
                                                {{ website.address || "Address unavailable." }}
                                            </Paragraph>
                                        </TableData>

                                        <TableData>
                                            <Form>
                                                <FormToggle id="is_active" name="is_active" />
                                            </Form>
                                        </TableData>
                                    </TableRow>
                                </template>

                                <template v-if="!websites.data || !websites.data.length">
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
                </div>
            </div>
        </Section>
    </Container>
</template>
