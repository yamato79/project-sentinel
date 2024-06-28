<template>
    <div>
        <!-- -->
        <SidebarMobile :sidebarOpen="sidebarOpen" @toggle-sidebar="sidebarOpen = !sidebarOpen" />

        <!-- -->
        <SidebarDesktop />

        <div class="lg:pl-72">
            <div class="bg-white border-b border-gray-200">
                <div class="sticky top-0 z-40 lg:mx-auto lg:max-w-7xl lg:px-8">
                    <div class="flex h-16 items-center gap-x-4 bg-transparent px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-0 lg:shadow-none">
                        <div class="flex items-center gap-4 lg:block ">
                            <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                                <span class="sr-only">Open sidebar</span>
                                <Bars3Icon class="h-6 w-6" aria-hidden="true" />
                            </button>

                            <div id="page-title"></div>
                        </div>

                        <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                            <div class="flex-1">
                                <!-- -->
                            </div>

                            <div class="flex items-center gap-x-4 lg:gap-x-6">
                                <Link :href="route('panel.profile.edit')" class="group hidden sm:flex items-center gap-2 font-medium leading-6 text-sm text-gray-500 hover:text-gray-700 transition-all ease-in-out duration-300">
                                    {{ $page.props.auth.user.name }}
                                </Link>

                                <div class="hidden sm:block w-px h-6 bg-gray-200"></div>

                                <Link :href="route('logout')" method="POST" as="button" class="group flex items-center gap-2 font-medium leading-6 text-sm text-gray-500 hover:text-gray-700 transition-all ease-in-out duration-300">
                                    <FontAwesomeIcon :icon="'fa-solid fa-sign-out-alt'" aria-hidden="true" />
                                    Logout
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <nav class="flex border-b border-gray-200 bg-white" aria-label="Breadcrumb">
                <Breadcrumb>
                    <template v-for="(breadcrumbItem, breadcrumbItemIndex) in props.breadcrumbs" :key="'breadcrumbItem_' + breadcrumbItemIndex">
                        <BreadcrumbItem :href="breadcrumbItem.href">
                            {{ breadcrumbItem.label }}
                        </BreadcrumbItem>
                    </template>
                    
                    <div id="breadcrumbs" class="flex"></div>
                </Breadcrumb>
            </nav>

            <main class="">
                <slot></slot>
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8"></div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from "vue";
import { Link } from "@inertiajs/vue3";
import { Bars3Icon } from "@heroicons/vue/24/outline";
import Breadcrumb from "@/components/breadcrumb.vue";
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import SidebarDesktop from "./_sidebar_desktop.vue";
import SidebarMobile from "./_sidebar_mobile.vue";

const sidebarOpen = ref(false);

const props = defineProps<{
    breadcrumbs: {
        label: string;
        href: string;
    }[]
}>();
</script>
