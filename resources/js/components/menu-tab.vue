<template>
    <div class="w-full">
        <div class="sm:hidden">
            <div class="flex flex-wrap items-center mb-1">
                <Paragraph size="sm">Navigation</Paragraph>
            </div>

            <label for="tabs" class="sr-only">Select a tab</label>
            
            <select class="block w-full w-full block text-sm leading-6 rounded-md text-gray-700 border-gray-200 focus:border-primary-600 focus:ring-primary-600 shadow-sm disabled:opacity-50 disabled:cursor-not-allowed" @change="onSelectChange($event)">
                <template v-for="(menuItem, menuItemIndex) in props.menuItems" :key="'menuItem_' + menuItemIndex">
                    <option :selected="isActive(menuItem.href)" :value="menuItem.href">{{ menuItem.label }}</option>
                </template>
            </select>
        </div>

        <div class="hidden sm:block">
            <nav class="isolate flex divide-x divide-gray-200 bg-white shadow rounded-md overflow-hidden" aria-label="Tabs">
                <template v-for="(menuItem, menuItemIndex) in props.menuItems" :key="'menuItem_' + menuItemIndex">
                    <MenuTabItem :href="menuItem.href" class="last:ml-auto">
                        {{ menuItem.label }}
                    </MenuTabItem>
                </template>
            </nav>
        </div>
    </div>
</template>

<script setup lang="ts">
import { defineProps } from "vue";
import { usePage, router } from "@inertiajs/vue3";
import MenuTabItem from "./menu-tab-item.vue";
import Paragraph from '@/components/paragraph.vue';

const page = usePage();

const props = defineProps({
    menuItems: {
        type: Object,
        required: true
    }
});

const isActive = (href: string) => {
    const currentUrl = new URL(window.location.origin + page.url);
    const linkUrl = new URL(href, currentUrl.origin);

    return (currentUrl.pathname === linkUrl.pathname);
};

const onSelectChange = (data: any) => {
    router.get(data.target.value);
}
</script>
