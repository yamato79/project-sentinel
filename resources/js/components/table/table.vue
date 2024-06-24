<template>
    <Card>
        <div class="v-table flow-root overflow-hidden">
            <div class="overflow-x-auto">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200">
                            <slot></slot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </Card>
</template>

<script setup lang="ts">
import Card from "@/components/card.vue";
</script>
