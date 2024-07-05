<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import ContentBody from "@/components/content-body.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGroup from "@/components/section-group.vue";
import Section from "@/components/section.vue";
import UptimeCard from "@/components/application/widgets/uptime-card.vue";
import UptimeTrend from "@/components/application/widgets/uptime-trend.vue";
import WebsiteStatusDistributionChart from "@/components/application/widgets/website-status-distribution-chart.vue";

defineOptions({ 
    layout: PanelLayout,
});

const props = defineProps({
    uptimeCards: {
        type: Object,
        required: false,
        default: () => {},
    },
    uptimeTrend: {
        type: Object,
        required: false,
        default: () => {},
    },
    websiteStatusDistribution: {
        type: Object,
        required: false,
        default: () => {},
    },
});
</script>

<template>
    <Container>
        <Head title="Dashboard" />

        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold">Dashboard</Paragraph>
                <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm" class="hidden lg:block">Your most important information, at a glance.</Paragraph>
            </div>
        </MountedTeleport>

        <Section>
            <SectionGroup>
                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <UptimeCard :title="'Uptime (24H)'" :hours="24" />
                    <UptimeCard :title="'Uptime (7D)'" :hours="(24 * 7)" />
                    <UptimeCard :title="'Uptime (30D)'" :hours="(24 * 30)" />
                </dl>
            </SectionGroup>

            <div class="grid grid-cols-6 gap-6">
                <SectionGroup class="col-span-full md:col-span-full xl:col-span-4">
                    <Card>
                        <ContentBody>
                            <UptimeTrend :hours="24" />
                        </ContentBody>
                    </Card>
                </SectionGroup>
                    
                <SectionGroup class="col-span-full md:col-span-3 xl:col-span-2">
                    <Card>
                        <ContentBody>
                            <div class="grid grid-cols-1 gap-[2px]">
                                <p class="truncate text-sm font-medium text-gray-600 mb-4">
                                    Website Status Distribution
                                </p>

                                <div class="flex items-center justify-center max-h-[16rem]">
                                    <WebsiteStatusDistributionChart :chart-data="props.websiteStatusDistribution" />
                                </div>
                            </div>
                        </ContentBody>
                    </Card>
                </SectionGroup>
            </div>
        </Section>
    </Container>
</template>
