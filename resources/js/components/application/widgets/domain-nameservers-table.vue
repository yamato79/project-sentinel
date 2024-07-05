<script setup lang="ts">
import { ref } from "vue";
import Badge from "@/components/badge.vue";
import ContentBody from "@/components/content-body.vue";
import Paragraph from "@/components/paragraph.vue";
import Spinner from "@/components/spinner.vue";
import TableBody from "@/components/table/table-body.vue";
import TableData from "@/components/table/table-data.vue";
import TableHead from "@/components/table/table-head.vue";
import TableHeader from "@/components/table/table-header.vue";
import TableRow from "@/components/table/table-row.vue";
import Table from "@/components/table/table.vue";

const props = defineProps({
    websiteId: {
        type: Number,
        required: true,
    },
    days: {
        type: Number,
        required: false,
        default: () => 7,
    },
});

const isLoading = ref(true);

const nameservers = ref<any>([
    // ...
]);

(async () => {
    try {
        const response = await fetch(route("api.widgets.domain-nameservers-table", {
            website_id: props.websiteId,
            days: props.days,
        }));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        setTimeout(async () => {
            const { data } = await response.json();
            nameservers.value = data;
            isLoading.value = false;
        }, 1000);
    } catch (error: any) {
        console.error(error);
        isLoading.value = false;
    }
})();
</script>

<template>
    <ContentBody>
        <p class="truncate text-sm font-medium text-gray-600">
            Domain Nameservers History ({{ days }}D)
        </p>
    </ContentBody>

    <Table class="rounded-none border-t border-gray-200">
        <TableHead>
            <TableRow>
                <TableHeader>Location</TableHeader>
                <TableHeader>Nameservers</TableHeader>
                <TableHeader class="text-right">Date Checked</TableHeader>
            </TableRow>
        </TableHead>
        
        <TableBody>
            <template v-if="isLoading">
                <TableRow>
                    <TableData align="center" colspan="100%">
                        <Spinner size="xs" />
                    </TableData>
                </TableRow>
            </template>

            <template v-else>
                <template v-for="(row, rowIndex) in nameservers" :key="'row_' + rowIndex">
                    <TableRow>
                        <TableData>{{ row.app_location }}</TableData>

                        <TableData>
                            <div class="flex flex-wrap items-center gap-1">
                                <template v-for="(nameserver, nameserverIndex) in JSON.parse(row.nameservers)" :key="'nameserver_' + nameserverIndex">
                                    <Badge color="muted" :icon="false">{{ nameserver }}</Badge>
                                </template>
                            </div>
                        </TableData>

                        <TableData align="right" class="whitespace-nowrap">{{ row.created_at }}</TableData>
                    </TableRow>
                </template>

                <template v-if="!nameservers || !nameservers.length">
                    <TableRow>
                        <TableData align="center" colspan="100%">
                            <Paragraph size="sm" class="italic">
                                No nameservers Found.
                            </Paragraph>
                        </TableData>
                    </TableRow>
                </template>
            </template>
        </TableBody>
    </Table>
</template>
