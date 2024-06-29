<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import ContentBody from "@/components/content-body.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGroup from "@/components/section-group.vue";
import Section from "@/components/section.vue";
import UptimeCard from "@/components/application/widgets/uptime-card.vue";
import UptimeTrendChart from "@/components/application/widgets/uptime-trend-chart.vue";
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
                <Heading :size="4">Uptime Summary</Heading>

                <dl class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    <template v-for="(uptimeCard, uptimeCardIndex) in props.uptimeCards" :key="'uptimeCard_' + uptimeCardIndex">
                        <UptimeCard
                            :title="uptimeCard.title"
                            :currentValue="uptimeCard.currentValue"
                            :previousValue="uptimeCard.previousValue"
                            :valueIncrease="uptimeCard.valueIncrease"
                            :valueDecrease="uptimeCard.valueDecrease"
                            :color="uptimeCard.color"
                        />
                    </template>
                </dl>
            </SectionGroup>

            <div class="grid grid-cols-6 gap-6">
                <SectionGroup class="col-span-full md:col-span-full xl:col-span-4">
                    <Heading :size="4">Uptime Trend (24H)</Heading>

                    <Card>
                        <ContentBody class="h-96">
                            <UptimeTrendChart :chart-data="props.uptimeTrend" />
                        </ContentBody>
                    </Card>
                </SectionGroup>
                    
                <SectionGroup class="col-span-full md:col-span-3 xl:col-span-2">
                    <Heading :size="4">Status Distribution</Heading>

                    <Card>
                        <ContentBody class="h-96">
                            <WebsiteStatusDistributionChart :chart-data="props.websiteStatusDistribution" />
                        </ContentBody>
                    </Card>
                </SectionGroup>
            </div>
        </Section>
    </Container>
</template>
