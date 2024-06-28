<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import MenuHorizontal from "@/components/menu-horizontal.vue";
import MenuHorizontalItem from "@/components/menu-horizontal-item.vue";

const navigation = [
    { name: "Dashboard", href: route("panel.dashboard"),      icon: "fa-solid fa-display" },
    { name: "Stacks",    href: route("panel.stacks.index"),  icon: "fa-solid fa-layer-group" },
    { name: "Websites",  href: route("panel.websites.index"), icon: "fa-solid fa-globe" },
];
</script>

<template>
    <div class="hidden lg:fixed lg:inset-y-0 lg:z-50 lg:flex lg:w-72 lg:flex-col">
        <!-- Sidebar component, swap this element with another sidebar if you like -->
        <div class="flex grow flex-col gap-y-5 overflow-y-auto border-r border-gray-200 bg-white px-6 pb-4">
            <div class="flex h-16 shrink-0 items-center">
                <Link :href="'/'" class="w-full inline-block max-h-[50%]">
                    <img class="h-6 w-auto" src="./../../../../assets/logo.png" alt="Your Company" />
                </Link>
            </div>

            <nav class="flex flex-1 flex-col">
                <ul role="list" class="flex flex-1 flex-col gap-y-7">
                    <li>
                        <MenuHorizontal>
                            <template v-for="(menuItem, menuItemIndex) in navigation" :key="'menuItem_' + menuItemIndex">
                                <MenuHorizontalItem :name="menuItem.name" :href="menuItem.href" :icon="menuItem.icon" />
                            </template>
                        </MenuHorizontal>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</template>
