<template>
    <div class="w-full">
        <div class="sm:hidden">
            <template v-if="props.backLink">
                <div class="flex mb-4">
                    <Button :href="props.backLink" color="muted">
                        <FontAwesomeIcon :icon="'fa-solid fa-long-arrow-left text-sm'" />
                        Back
                    </Button>
                </div>
            </template>

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
            <nav class="isolate flex divide-x divide-gray-200 bg-white shadow rounded-md overflow-hidden border" aria-label="Tabs">
                <template v-if="props.backLink">
                    <Link
                        :href="props.backLink"
                        class="text-gray-500 hover:text-gray-700 bg-white max-w-[25%] group relative min-w-0 overflow-hidden px-6 py-2.5 text-center text-sm font-medium focus:z-10 font-medium whitespace-nowrap transition-all ease-in-out duration-300">
                        <div class="py-0.25">
                            <FontAwesomeIcon :icon="'fa-solid fa-long-arrow-left text-sm'" />
                        </div>
                    </Link>
                </template>

                <template v-for="(menuItem, menuItemIndex) in props.menuItems" :key="'menuItem_' + menuItemIndex">
                    <MenuTabItem :href="menuItem.href">
                        {{ menuItem.label }}
                    </MenuTabItem>
                </template>
            </nav>
        </div>
    </div>
</template>

<script setup lang="ts">
import { usePage, router, Link } from "@inertiajs/vue3";
import Button from "./button.vue";
import MenuTabItem from "./menu-tab-item.vue";
import Paragraph from "@/components/paragraph.vue";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";

const page = usePage();

const props = defineProps({
    menuItems: {
        type: Object,
        required: true
    },
    backLink: {
        type: String,
        required: false,
    },
});

const isActive = (href: string) => {
    const currentUrl = new URL(window.location.origin + page.url);
    const linkUrl = new URL(href, currentUrl.origin);

    return (currentUrl.pathname === linkUrl.pathname);
};

const onSelectChange = (data: any) => {
    router.get(data.target.value);
};
</script>
