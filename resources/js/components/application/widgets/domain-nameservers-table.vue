<script setup lang="ts">
import { ref } from "vue";
import Badge from "@/components/badge.vue";
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
        required: false,
        default: () => null
    },
});

const nameservers = ref([]);
const isLoading = ref(true);

const getData = async () => {
    try {
        const params = {
            website_id: props.websiteId,
        } as { [key: string]: string | number };

        const response = await fetch(route("api.widgets.domain-nameservers-table", params));

        if (!response.ok) {
            throw new Error("HTTP error! Status: " + response.status);
        }

        const { data } = await response.json();

        if (data) {
            console.log(data);
            nameservers.value = data;
        }
    } catch (error: any) {
        console.error(error);
    }

    isLoading.value = false;
};

getData();
</script>

<template>
    <Table>
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
