

<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import WebsiteEditIndexLayout from "./index.vue";
import Badge from "@/components/badge.vue";
import Card from "@/components/card.vue";
import ContentBody from "@/components/content-body.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGroup from "@/components/section-group.vue";
import DomainNameserversTable from "@/components/application/widgets/domain-nameservers-table.vue";
import DomainStatusCard from "@/components/application/widgets/domain-status-card.vue";
import ResponseTimeTrend from "@/components/application/widgets/response-time-trend.vue";
import SSLStatusCard from "@/components/application/widgets/ssl-status-card.vue";
import UptimeCard from "@/components/application/widgets/uptime-card.vue";
import UptimeFeed from "@/components/application/widgets/uptime-feed.vue";
import UptimeTrend from "@/components/application/widgets/uptime-trend.vue";

defineOptions({ 
    layout: [PanelLayout, WebsiteEditIndexLayout],
});

const props = defineProps({
    website: {
        type: Object,
        required: false,
        default: () => {},
    },
    tabs: {
        type: Array,
        required: false,
        default: () => [],
    },
    uptimeCards: {
        type: Object,
        required: false,
        default: () => {},
    },
    uptimeFeed: {
        type: Object,
        required: false,
        default: () => {},
    },
    uptimeTrend: {
        type: Object,
        required: false,
        default: () => {},
    },
});
</script>

<template>
    <Head title="Website Summary" />

    <MountedTeleport to="#page-title">
        <div class="flex flex-wrap items-center gap-4">
            <Paragraph class="font-semibold">{{ props.website.data.name }}</Paragraph>
            <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
            <Badge :color="props.website.data.website_status.color">{{ props.website.data.website_status.name }}</Badge>
        </div>
    </MountedTeleport>
    
    <SectionGroup>
        <div class="col-span-full sm:col-span-4 grid grid-cols-1 gap-10">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-full sm:col-span-3 xl:col-span-2">
                    <div class="grid grid-cols-1 gap-4">
                        <SectionGroup>
                            <dl class="grid grid-cols-1 gap-4">
                                <UptimeCard :website-id="props.website.data.website_id" :title="'Uptime (24H)'" :hours="24" />
                                <UptimeCard :website-id="props.website.data.website_id" :title="'Uptime (7D)'"  :hours="(24 * 7)" />
                                <UptimeCard :website-id="props.website.data.website_id" :title="'Uptime (30D)'" :hours="(24 * 30)" />
                                <SSLStatusCard :website-id="props.website.data.website_id" />
                                <DomainStatusCard :website-id="props.website.data.website_id" />
                            </dl>
                        </SectionGroup>
                    </div>
                </div>

                <div class="col-span-full sm:col-span-3 xl:col-span-4">
                    <div class="grid grid-cols-1 gap-4">
                        <SectionGroup>
                            <Card>
                                <ContentBody>
                                    <UptimeFeed :website-id="props.website.data.website_id" :minutes="60" />
                                </ContentBody>
                            </Card>
                        </SectionGroup>

                        <SectionGroup>
                            <Card>
                                <ContentBody>
                                    <UptimeTrend :website-id="props.website.data.website_id" :hours="24" />
                                </ContentBody>
                            </Card>
                        </SectionGroup>

                        <SectionGroup>
                            <Card>
                                <ContentBody>
                                    <ResponseTimeTrend :website-id="props.website.data.website_id" :hours="24" />
                                </ContentBody>
                            </Card>
                        </SectionGroup>

                        <SectionGroup>
                            <Card class="overflow-hidden">
                                <DomainNameserversTable :website-id="props.website.data.website_id" />
                            </Card>
                        </SectionGroup>
                    </div>
                </div>
            </div>
        </div>
    </SectionGroup>
</template>
