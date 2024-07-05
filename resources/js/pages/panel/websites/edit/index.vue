<script setup lang="ts">
import Container from "@/components/container.vue";
import MenuTab from "@/components/menu-tab.vue";
import Section from "@/components/section.vue";

const props = defineProps({
    tabs: {
        type: Array,
        required: false,
        default: () => [],
    },
});
</script>

<template>
    <Container>
        <Section>
            <!--
  This example requires some changes to your config:
  
  ```
  // tailwind.config.js
  module.exports = {
    // ...
    plugins: [
      // ...
      require('@tailwindcss/forms'),
    ],
  }
  ```
-->
            <div class="">
                <div class="sm:hidden">
                    <label for="tabs" class="sr-only">Select a tab</label>
                    <!-- Use an "onChange" listener to redirect the user to the selected tab URL. -->
                    <select id="tabs" name="tabs" class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <option>My Account</option>
                    <option>Company</option>
                    <option selected>Team Members</option>
                    <option>Billing</option>
                    </select>
                </div>
                <div class="hidden sm:block">
                    <nav class="flex gap-px" aria-label="Tabs">
                    <!-- Current: "bg-gray-100 text-gray-700", Default: "text-gray-500 hover:text-gray-700" -->
                    <a href="#" class="rounded-md bg-gray-200 px-6 py-2 text-sm font-medium text-gray-500 hover:text-primary-500 rounded-r-none">
                        <FontAwesomeIcon icon="fa-solid fa-long-arrow-left text-sm" />
                    </a>
                    <a href="#" class="bg-gray-300 px-6 py-2 text-sm font-medium text-gray-700" aria-current="page">Summary</a>
                    <a href="#" class="rounded-md bg-gray-200 px-6 py-2 text-sm font-medium text-gray-500 hover:text-primary-500 rounded-l-none">Settings</a>
                    </nav>
                </div>
            </div>

            <MenuTab
                :menu-items="props.tabs"
                :backLink="route('panel.websites.index')"
                v-if="false"
            />
            <slot></slot>
        </Section>
    </Container>
</template>
