

<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue';
import { Head, router } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import WebsiteEditIndexLayout from "./index.vue";
import Badge from "@/components/badge.vue";
import Card from "@/components/card.vue";
import ContentBody from "@/components/content-body.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGroup from "@/components/section-group.vue";
import UptimeCard from "@/components/application/widgets/uptime-card.vue";
import UptimeTrendChart from "@/components/application/widgets/uptime-trend-chart.vue";

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

// Create a ref to hold the interval ID
const intervalId = ref<any>(null);

// Function to fetch uptimeFeed data
const fetchUptimeFeed = () => {
    // Replace with your fetch logic to update uptimeFeed
    console.log("Refreshing Uptime Feed ...");
    router.reload({ only: [
        'uptimeCards',
        'uptimeFeed'
    ]});
};

// Function to start reloading uptimeFeed every 30 seconds
const startReloading = () => {
    intervalId.value = setInterval(fetchUptimeFeed, 30000); // 30 seconds
};

// Hook to start interval on component mount
onMounted(() => {
    startReloading();
});

// Hook to clear interval on component unmount
onUnmounted(() => {
    if (intervalId.value) {
        clearInterval(intervalId.value);
    }
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
                    <SectionGroup>
                        <Heading :size="4">Uptime Summary</Heading>

                        <dl class="grid grid-cols-1 gap-6">
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
                </div>

                <div class="col-span-full sm:col-span-3 xl:col-span-4 grid grid-cols-1 gap-6">
                    <SectionGroup>
                        <Heading :size="4">Uptime Feed (1H)</Heading>

                        <Card>
                            <ContentBody>
                                <div class="w-full flex items-center justify-center gap-[2px] rounded-sm overflow-hidden">
                                    <template v-for="(tick, tickIndex) in props.uptimeFeed" :key="'tick_' + tickIndex">
                                        <template v-if="tick.is_online == null">
                                            <div :class="['bg-gray-300', 'flex-1 min-w-[1px] h-8 rounded-sm']"></div>
                                        </template>

                                        <template v-else>
                                            <div :class="[(tick.is_online) ? 'bg-green-500' : 'bg-red-500', 'flex-1 min-w-[1px] h-8 rounded-sm']"></div>
                                        </template>
                                    </template>
                                </div>
                            </ContentBody>
                        </Card>
                    </SectionGroup>

                    <SectionGroup>
                        <Heading :size="4">Uptime Trend (24H)</Heading>

                        <Card>
                            <ContentBody>
                                <UptimeTrendChart :chart-data="props.uptimeTrend" />
                            </ContentBody>
                        </Card>
                    </SectionGroup>
                </div>
            </div>
        </div>
    </SectionGroup>
</template>
