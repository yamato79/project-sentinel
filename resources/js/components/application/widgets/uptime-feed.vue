<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
import Paragraph from "@/components/paragraph.vue";
import Spinner from "@/components/spinner.vue";

const props = defineProps({
    websiteId: {
        type: Number,
        required: false,
        default: () => null
    },
    minutes: {
        type: Number,
        required: true,
    },
});

const isLoading = ref(true);

const uptimeData = ref<{
    app_location: string;
    app_locations: any[];
    avg_uptime_percent: number;
    minute: string;
    date: string;
    website_id: number;
}[]>([]);

// Create a ref to hold the interval ID
const intervalId = ref<any>(null);

const getData = async () => {
    try {
        const response = await fetch(route("api.widgets.uptime-feed", {
            website_id: props.websiteId || undefined,
            minutes: props.minutes,
        }));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        setTimeout(async () => {
            const { data } = await response.json();
            uptimeData.value = data;
            isLoading.value = false;
        }, 1000);
    } catch (error: any) {
        console.error(error);
        isLoading.value = false;
    }
};

const startReloading = () => {
    getData(); // Immediately fetch data for the first time
    intervalId.value = setInterval(getData, 60000); // 60 seconds
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

const getClass = (uptime: number) => {
    if (uptime === 100) {
        return "bg-success-400";
    }

    if (uptime > 0 && uptime < 100) {
        return "bg-warning-400";
    }

    if (uptime === 0) {
        return "bg-danger-400";
    }

    return "bg-gray-200";
};
</script>

<template>
    <div class="grid grid-cols-1 gap-4">
        <p class="truncate text-sm font-medium text-gray-600">
            Uptime Feed ({{ (props.minutes / 60) }}H)
        </p>

        <template v-if="isLoading">
            <div class="w-full h-full flex items-center justify-center py-2">
                <Spinner size="xs"/>
            </div>
        </template>
        
        <template v-else>
            <div class="w-full flex items-center gap-[2px]">
                <template v-for="(uptimeTick, uptimeTickIndex) in uptimeData" :key="'uptimeTick_' + uptimeTickIndex">
                    <VTooltip class="flex-1">
                        <div :class="[getClass(uptimeTick.avg_uptime_percent), 'w-full hover:opacity-80 h-8 rounded-sm cursor-pointer']">
                            <!-- -->
                        </div>

                        <template #popper>
                            <div class="flex flex-col gap-1">
                                <Paragraph color="mutedLight" size="xs" class="text-right font-bold">
                                    {{ uptimeTick.date }}
                                </Paragraph>

                                <div class="border-t border-b py-1 border-gray-900">
                                    <template v-if="uptimeTick.avg_uptime_percent === null">
                                        <Paragraph color="mutedLight" size="xs" class="text-right">
                                            No Data
                                        </Paragraph>
                                    </template>

                                    <template v-else>
                                        <template v-for="(uptimePercent, monitorLocationSlug) in uptimeTick.app_locations" :key="'uptimeLocation_' + monitorLocationSlug">
                                            <Paragraph color="mutedLight" size="xs" class="grid grid-cols-2">
                                                <div>{{ monitorLocationSlug }}: </div>
                                                <div class="text-right">{{ uptimePercent }}%</div>
                                            </Paragraph>
                                        </template>
                                    </template>
                                </div>

                                <Paragraph color="mutedLight" size="xs" class="text-right">
                                    {{ uptimeTick.avg_uptime_percent }}%
                                </Paragraph>
                            </div>
                        </template>
                    </VTooltip>
                </template>
            </div>
        </template>
    </div>
</template>
