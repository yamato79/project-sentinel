<script setup lang="ts">
import { computed, ref } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import Button from "@/components/button.vue";
import Form from "@/components/form/form.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import Paragraph from "@/components/paragraph.vue";
import TableBody from "@/components/table/table-body.vue";
import TableData from "@/components/table/table-data.vue";
import TableFoot from "@/components/table/table-foot.vue";
import TableHead from "@/components/table/table-head.vue";
import TableHeader from "@/components/table/table-header.vue";
import TableRow from "@/components/table/table-row.vue";
import Table from "@/components/table/table.vue";

const page = usePage();

const props = defineProps<{
    columns: ModelTableColumn[],
    rows: ModelTableRow[],
    meta: ModelTableMeta,
    links: ModelTableLinks,
}>();

const tableHeaderAlignmentClasses = {
    left: "text-left",
    center: "text-center",
    right: "text-right"
};

/**
 * ------------------------------------------------------------------------------------------------
 * | Filter Form                                                                                  |
 * ------------------------------------------------------------------------------------------------
 */
const filterForm = ref({
    searchQuery: page.props.query.searchQuery,
});

const submitFilterForm = () => {
    router.get(route(route().current() as string), {
        ...page.props.query,
        searchQuery: filterForm.value.searchQuery || undefined,
        page: undefined,
    }, {
        preserveScroll: true,
    });
};

const clearFilterForm = () => {
    filterForm.value.searchQuery = "";
    submitFilterForm();
};
</script>

<template>
    <div class="grid grid-cols-1 gap-8">
        <!-- Header -->
        <div class="w-full flex flex-col-reverse gap-4 sm:flex-row items-center justify-between">
            <Form class="w-full sm:w-auto" @submit.prevent="submitFilterForm">
                <div class="w-full flex items-center justify-between">
                    <FormGroup class="w-full sm:w-auto">
                        <FormInput type="text" class="rounded-r-none" placeholder="Search ..." v-model="filterForm.searchQuery" autofocus />
                    </FormGroup>

                    <template v-if="filterForm.searchQuery">
                        <div class="border-r border-gray-800">
                            <Button type="button" class="rounded-none h-full" @click.stop="clearFilterForm">
                                <div class="py-0.25">
                                    <FontAwesomeIcon :icon="'fa-solid fa-times'" :class="'text-gray-50'" aria-hidden="true" />
                                </div>
                            </Button>
                        </div>
                    </template>

                    <Button type="submit" class="rounded-l-none">
                        <span class="py-0.25">
                            <FontAwesomeIcon :icon="'fa-solid fa-search'" :class="'text-gray-50'" aria-hidden="true" />
                        </span>
                        <span class="hidden sm:block">
                            Search
                        </span>
                    </Button>
                </div>
            </Form>

            <div class="w-full sm:w-auto flex items-center justify-end gap-2">
                <slot name="actions">
                </slot>
            </div>
        </div>
        
        <Table>
            <TableHead>
                <TableRow>
                    <template v-for="(column, columnIndex) in props.columns" :key="'column_' + columnIndex">
                        <TableHeader :class="[tableHeaderAlignmentClasses[column.align || 'left']]">
                            {{ column.title }}
                        </TableHeader>
                    </template>
                </TableRow>
            </TableHead>
            
            <TableBody>
                <template v-for="(row, rowIndex) in props.rows" :key="'row_' + rowIndex">
                    <TableRow>
                        <template v-for="(column, columnIndex) in columns" :key="'rowColumn_' + columnIndex">
                            <TableData :align="column.align || 'left'" :class="[((column.fullWidth) ? 'w-full' : ''), 'first:whitespace-nowrap first:max-w-[50%]']">
                                <slot :name="'column_' + column.key" :row="row" :column="column">
                                    {{ row[column.key] }}
                                </slot>
                            </TableData>
                        </template>
                    </TableRow>
                </template>

                <template v-if="!props.rows || !props.rows.length">
                    <TableRow>
                        <TableData align="center" colspan="100%">
                            <Paragraph size="sm" class="italic">
                                No Results Found.
                            </Paragraph>
                        </TableData>
                    </TableRow>
                </template>
            </TableBody>

            <template v-if="meta.total">
                <TableFoot>
                    <TableRow>
                        <TableHeader colspan="100%">
                            <nav class="flex flex-col sm:flex-row items-center justify-center sm:justify-between gap-4" aria-label="Pagination">
                                <div class="w-full text-center sm:text-left normal-case">
                                    <Paragraph size="sm">
                                        Showing
                                        <span class="font-medium">{{ meta.from }}</span>
                                        to
                                        <span class="font-medium">{{ meta.to }}</span>
                                        of
                                        <span class="font-medium">{{ meta.total }}</span>
                                        results
                                    </Paragraph>
                                </div>

                                <div class="w-full md:w-auto grid grid-cols-2 sm:flex items-end justify-end gap-2">
                                    <Button :href="links.prev" color="muted" size="sm" :disabled="!links.prev">
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd"></path>
                                        </svg>
                                        Previous
                                    </Button>

                                    <Button :href="links.next" color="muted" size="sm" :disabled="!links.next">
                                        Next
                                        <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M2 10a.75.75 0 01.75-.75h12.59l-2.1-1.95a.75.75 0 111.02-1.1l3.5 3.25a.75.75 0 010 1.1l-3.5 3.25a.75.75 0 11-1.02-1.1l2.1-1.95H2.75A.75.75 0 012 10z" clip-rule="evenodd"></path>
                                        </svg>
                                    </Button>
                                </div>
                            </nav>
                        </TableHeader>
                    </TableRow>
                </TableFoot>
            </template>
        </Table>
    </div>
</template>
