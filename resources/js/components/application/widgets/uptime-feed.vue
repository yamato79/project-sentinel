<script setup lang="ts">
import { ref, onMounted, onUnmounted } from "vue";
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
    website_id: number;
}[]>([]);

// Create a ref to hold the interval ID
const intervalId = ref<any>(null);

const getData = async () => {
    try {
        const params = {
            website_id: props.websiteId,
            minutes: props.minutes,
        } as { [key: string]: string | number };

        if (props.websiteId) {
            params.website = props.websiteId;
        } else {
            delete params.website;
        }

        const response = await fetch(route("api.widgets.uptime-feed", params));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        const { data } = await response.json();

        if (data) {
            uptimeData.value = data;
        }
    } catch (error: any) {
        console.error(error);
    }

    isLoading.value = false;
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
    <div>
        <div class="w-full flex items-center gap-[2px]">
            <template v-if="isLoading">
                <div class="w-full h-full flex items-center justify-center py-2">
                    <Spinner size="xs"/>
                </div>
            </template>

            <template v-else>
                <template v-for="(uptimeTick, uptimeTickIndex) in uptimeData" :key="'uptimeTick_' + uptimeTickIndex">
                    <div :class="[
                        getClass(uptimeTick.avg_uptime_percent), 
                        'w-full flex-1 group hover:opacity-80 relative h-8 bg-gray-200 rounded-sm cursor-pointer'
                    ]">
                        <span class="group-hover:opacity-100 transition-opacity bg-gray-900 px-2 py-1 text-xs text-gray-100 rounded-md absolute left-1/2 -translate-x-1/2 translate-y-6 opacity-0 m-4 mx-auto whitespace-nowrap pointer-events-none z-50">
                            <span class="font-bold text-white border-b border-gray-300">
                                {{ uptimeTick.minute }}
                            </span>

                            <template v-if="uptimeTick.avg_uptime_percent === null">
                                No Data
                            </template>

                            <template v-else>
                                <template v-for="(uptimePercent, monitorLocationSlug) in uptimeTick.app_locations" :key="'uptimeLocation_' + monitorLocationSlug">
                                    <div class="flex items-center justify-between gap-x-1">
                                        <div>{{ monitorLocationSlug }}: </div>
                                        <div class="font-semibold">{{ uptimePercent }}%</div>
                                    </div>
                                </template>

                                <div class="border-t border-gray-300 text-right font-semibold">
                                    {{ uptimeTick.avg_uptime_percent }}%
                                </div>
                            </template>
                        </span>
                    </div>
                </template>
            </template>
        </div>
    </div>
</template>
