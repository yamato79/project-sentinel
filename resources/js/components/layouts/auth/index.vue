<template>
    <div class="flex min-h-full flex-1 bg-gray-100">
        <div class="relative hidden w-0 flex-1 lg:block">
            <div class="w-full h-full max-h-full absolute top-0 left-0 flex flex-col items-center justify-center gap-8 px-12">
                <img class="w-full max-h-[50%]" src="./../../../../assets/auth-showcase.svg" alt="" />
                <div class="text-center max-w-2xl">
                    <Paragraph size="lg" color="muted" class="italic"> Focus on your business; Make an impact in peace knowing your website is monitored and online. </Paragraph>
                </div>
            </div>
        </div>

        <div class="flex flex-1 flex-col justify-center px-4 py-12 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-gray-50 border-l border-gray-200">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div class="pb-8">
                    <Link :href="route('panel.dashboard')" class="w-full inline-block max-h-[50%]">
                        <img class="h-10 w-auto" src="./../../../../assets/logo.png" alt="Your Company" />
                    </Link>
                </div>

                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import Paragraph from "@/components/paragraph.vue";
import { Link } from "@inertiajs/vue3";
</script>
