<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import { Dialog, DialogPanel, TransitionChild, TransitionRoot } from "@headlessui/vue";
import { XMarkIcon } from "@heroicons/vue/24/outline";
import MenuHorizontal from "@/components/menu-horizontal.vue";
import MenuHorizontalItem from "@/components/menu-horizontal-item.vue";

const emit = defineEmits(["toggleSidebar"]);

const props = defineProps({
    sidebarOpen: {
        type: Boolean,
        required: true
    }
});

const navigation = [
    { name: "Dashboard", href: route("panel.dashboard"), icon: "fa-solid fa-display" },
    { name: "Stacks",    href: route("panel.stacks.index"),  icon: "fa-solid fa-layer-group" },
    { name: "Websites", href: route("panel.websites.index"), icon: "fa-solid fa-globe" },
    { name: "My Profile", href: route("panel.me.profile"), icon: "fa-solid fa-user-gear" },
    { name: "Sign out", href: route("logout"), icon: "fa-solid fa-right-from-bracket" }
];

const onMenuItemClick = () => {
    emit("toggleSidebar");
};
</script>

<template>
    <TransitionRoot as="template" :show="props.sidebarOpen">
        <Dialog class="relative z-50 lg:hidden" @close="emit('toggleSidebar')">
            <TransitionChild as="template" enter="transition-opacity ease-linear duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="transition-opacity ease-linear duration-300" leave-from="opacity-100" leave-to="opacity-0">
                <div class="fixed inset-0 bg-gray-900/80" />
            </TransitionChild>

            <div class="fixed inset-0 flex">
                <TransitionChild as="template" enter="transition ease-in-out duration-300 transform" enter-from="-translate-x-full" enter-to="translate-x-0" leave="transition ease-in-out duration-300 transform" leave-from="translate-x-0" leave-to="-translate-x-full">
                    <DialogPanel class="relative mr-16 flex w-full max-w-xs flex-1">
                        <TransitionChild as="template" enter="ease-in-out duration-300" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in-out duration-300" leave-from="opacity-100" leave-to="opacity-0">
                            <div class="absolute left-full top-0 flex w-16 justify-center pt-5">
                                <button type="button" class="-m-2.5 p-2.5" @click="emit('toggleSidebar')">
                                    <span class="sr-only">Close sidebar</span>
                                    <XMarkIcon class="h-6 w-6 text-white" aria-hidden="true" />
                                </button>
                            </div>
                        </TransitionChild>

                        <!-- Sidebar component, swap this element with another sidebar if you like -->
                        <div class="flex grow flex-col gap-y-5 overflow-y-auto bg-gray-50 px-6 pb-4">
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
                                                <MenuHorizontalItem :name="menuItem.name" :href="menuItem.href" :icon="menuItem.icon" @click.stop="onMenuItemClick" />
                                            </template>
                                        </MenuHorizontal>
                                    </li>
                                </ul>
                            </nav>
                        </div>
                    </DialogPanel>
                </TransitionChild>
            </div>
        </Dialog>
    </TransitionRoot>
</template>
