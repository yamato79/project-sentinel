

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import Badge from "@/components/badge.vue"
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import ContentBody from "@/components/content-body.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from '@/components/paragraph.vue';
import SectionGroup from "@/components/section-group.vue";
import Section from "@/components/section.vue";
import Tabs from "./_tabs.vue";
import UptimeCard from "@/components/application/widgets/uptime-card.vue";
import UptimeTrendChart from "@/components/application/widgets/uptime-trend-chart.vue";

const emit = defineEmits(["update-website"]);

const props = defineProps({
    website: {
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
        <Head title="Website Summary" />

        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold">{{ props.website.data.name }}</Paragraph>
                <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
                <Badge :color="props.website.data.website_status.color">{{ props.website.data.website_status.name }}</Badge>
            </div>
        </MountedTeleport>

        <MountedTeleport to="#breadcrumbs">
            <BreadcrumbItem :href="route('panel.websites.index', { website: props.website.data.website_id })">Websites</BreadcrumbItem>
            <BreadcrumbItem :href="route('panel.websites.edit', { website: props.website.data.website_id })">{{ props.website.data.name }}</BreadcrumbItem>
            <BreadcrumbItem :href="route('panel.websites.edit.summary', { website: props.website.data.website_id })">Summary</BreadcrumbItem>
        </MountedTeleport>
        
        <Section>
            <Tabs :website-id="props.website.data.website_id" />

            <SectionGroup>
                <div class="col-span-full sm:col-span-4 grid grid-cols-1 gap-10">
                    <div class="grid grid-cols-6 gap-6">
                        <div class="col-span-full sm:col-span-3 xl:col-span-2">
                            <SectionGroup>
                                <Heading :size="4">Uptime Summary</Heading>

                                <dl class="grid grid-cols-1 gap-6">
                                    <UptimeCard :title="'Uptime (24H)'" />
                                    <UptimeCard :title="'Uptime (7D)'" />
                                    <UptimeCard :title="'Uptime (30D)'" />
                                </dl>
                            </SectionGroup>
                        </div>

                        <div class="col-span-full sm:col-span-3 xl:col-span-4">
                            <SectionGroup>
                                <Heading :size="4">Uptime Trend (24H)</Heading>

                                <Card>
                                    <ContentBody>
                                        <UptimeTrendChart />
                                    </ContentBody>
                                </Card>
                            </SectionGroup>
                        </div>
                    </div>
                </div>
            </SectionGroup>
        </Section>
    </Container>
</template>
