<script setup lang="ts">
import { ref } from "vue";
import Card from "@/components/card.vue";
import Spinner from "@/components/spinner.vue";

const props = defineProps({
    websiteId: {
        type: Number,
        required: false,
        default: () => null
    },
});

const expiresIn = ref(null);
const isValid = ref(null);
const isLoading = ref(true);

const getData = async () => {
    try {
        const params = {
            website_id: props.websiteId,
        } as { [key: string]: string | number };

        const response = await fetch(route("api.widgets.domain-status", params));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        const { data } = await response.json();

        if (data) {
            expiresIn.value = data.expires_in;
            isValid.value = data.is_valid;
        }
    } catch (error: any) {
        console.error(error);
    }

    isLoading.value = false;
};

const refreshData = async () => {
    isLoading.value = true;

    try {
        await fetch(route("api.widgets.domain-status.execute", { website_id: props.websiteId }));
        await getData();
    } catch (error) {
        console.error(error);
    }

    isLoading.value = false;
}

getData();
</script>

<template>
    <Card>
        <div class="flex flex-col justify-center p-4 sm:p-6">
            <dt>
                <div :class="['absolute rounded-md bg-gray-700 p-3']">
                    <div class="w-6 h-6 flex items-center justify-center text-white text-lg">
                        <FontAwesomeIcon :icon="'fa-solid fa-times'" class="text-gray-500" v-if="isValid === null || isLoading" />
                        <FontAwesomeIcon :icon="'fa-solid fa-check'" class="text-success-500" v-else-if="isValid" />
                        <FontAwesomeIcon :icon="'fa-solid fa-times'" class="text-danger-500" v-else-if="!isValid" />
                    </div>
                </div>

                <div class="ml-16 flex items-center justify-between">
                    <p class="truncate text-sm font-medium text-gray-500">
                    Domain Status
                    </p>

                    <div class="absolute right-6">
                        <a href="#" class="text-xs text-gray-400 hover:text-primary-600" @click.stop="refreshData">
                            <FontAwesomeIcon icon="fa-solid fa-rotate" :class="[isLoading ? 'fa-spin' : '', 'w-4 h-4']" />
                        </a>
                    </div>
                </div>
            </dt>

            <dd class="ml-16 flex items-baseline">
                <template v-if="isLoading">
                    <p class="flex items-center text-2xl font-semibold text-gray-900">
                        <Spinner size="xs" />
                        &nbsp;
                    </p>
                </template>

                <template v-else>
                    <p class="flex items-center text-2xl font-semibold text-gray-900">
                        <span class="text-gray-500 font-semibold" v-if="isValid === null">--</span>
                        <span class="text-success-500 font-semibold" v-else-if="isValid">Active</span>
                        <span class="text-danger-500 font-semibold" v-else-if="!isValid">Invalid</span>
                    </p>

                    <p :class="['ml-2 text-sm font-normal text-gray-500']">
                        <template v-if="expiresIn === null">
                            N/A
                        </template>

                        <template v-else-if="expiresIn > 0">
                            Expires in {{ expiresIn }} days
                        </template>

                        <template v-else-if="expiresIn < 0">
                            Expired {{ expiresIn * -1 }} days ago
                        </template>
                    </p>
                </template>
            </dd>
        </div>
    </Card>
</template>
