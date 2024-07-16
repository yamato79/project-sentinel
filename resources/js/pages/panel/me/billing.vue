<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import { RadioGroup, RadioGroupOption } from "@headlessui/vue";
import PanelLayout from "@/components/layouts/panel/index.vue";
import MeIndexLayout from "./index.vue";
import Badge from "@/components/badge.vue";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
import ContentBody from "@/components/content-body.vue";
import ContentFoot from "@/components/content-foot.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormLabel from "@/components/form/form-label.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGridContainer from "@/components/section-grid-container.vue";
import SectionGridContent from "@/components/section-grid-content.vue";
import SectionGridSidebar from "@/components/section-grid-sidebar.vue";
import SectionGrid from "@/components/section-grid.vue";
import TableBody from "@/components/table/table-body.vue";
import TableData from "@/components/table/table-data.vue";
import TableHead from "@/components/table/table-head.vue";
import TableHeader from "@/components/table/table-header.vue";
import TableRow from "@/components/table/table-row.vue";
import Table from "@/components/table/table.vue";

defineOptions({ 
    layout: [
        PanelLayout,
        MeIndexLayout,
    ],
});

const props = defineProps({
    cancelUrl: {
        type: String,
        required: true,
    },
    prices: {
        type: Object,
        required: true,
    },
    subscription: {
        type: Object,
        required: true,
    },
    transactions: {
        type: Object,
        required: true,  
    },
    lastPayment: {
        type: Object,
        default: () => {},
        required: false,
    },
    nextPayment: {
        type: Object,
        default: () => {},
        required: false,
    },
    paymentMethodUpdateUrl: {
        type: String,
        required: true,
    },
});

const subscriptionForm = useForm({
    price_id: "",
});

const currentSubscriptionItem = props.subscription.items.find((item: any) => item.price_id);

if (currentSubscriptionItem) {
    subscriptionForm.price_id = currentSubscriptionItem.price_id;
}

const createSubscription = () => {
    subscriptionForm.post(route("panel.me.billing.subscriptions.store"), {
        preserveScroll: true,
        onSuccess: (response) => {
            console.log("TODO: Add success toast notification.", response);
            Paddle.Checkout.open(response.props.flash.paddleOptions);
        },
        onError: (errors) => {
            Object.keys(errors).forEach((key) => {
                const error = errors[key];
                console.error("TODO: Add error toast notification.", error);
            });
        },
    });
};

const updateSubscription = () => {
    subscriptionForm.patch(route("panel.me.billing.subscriptions.update"), {
        preserveScroll: true,
        onSuccess: (response) => {
            console.log("TODO: Add success toast notification.", response);
        },
        onError: (errors) => {
            Object.keys(errors).forEach((key) => {
                const error = errors[key];
                console.error("TODO: Add error toast notification.", error);
            });
        },
    });
};

const cancelSubscription = () => {
    subscriptionForm.delete(route("panel.me.billing.subscriptions.destroy"), {
        preserveScroll: true,
        onSuccess: (response) => {
            console.log("TODO: Add success toast notification.", response);
        },
        onError: (errors) => {
            Object.keys(errors).forEach((key) => {
                const error = errors[key];
                console.error("TODO: Add error toast notification.", error);
            });
        },
    });
};
</script>

<template>
    <Head title="Billing" />

    {{ subscription }}

    <MountedTeleport to="#page-title">
        <div class="flex flex-wrap items-center gap-4">
            <Paragraph class="font-semibold">Billing</Paragraph>
            <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
            <Paragraph color="muted" size="sm" class="hidden lg:block">Manage your billing information.</Paragraph>
        </div>
    </MountedTeleport>

    <SectionGridContainer>
        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">Website Monitoring Plans</Heading>
                <Paragraph color="muted" size="sm" class="mt-1">
                    For higher volume needs, please contact support for custom plans.
                </Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <ContentBody class="grid grid-cols-1 gap-6">
                        <fieldset aria-label="Server size">
                            <RadioGroup v-model="subscriptionForm.price_id" class="space-y-4">
                                <template v-for="(price, priceIndex) in prices" :key="'price_' + priceIndex">
                                    <RadioGroupOption as="template" :value="price.price.id" v-slot="{ checked }">
                                        <div :class="['relative block cursor-pointer rounded border bg-white px-6 py-4 sm:flex sm:justify-between']">
                                            <span class="flex items-center">
                                                <span class="flex flex-col text-sm">
                                                    <span class="font-medium text-gray-700">
                                                        {{ price.product.name }}
                                                    </span>
                                                    <span class="text-gray-500">
                                                        Up to {{ price.product.custom_data.websites_limit }} websites.
                                                    </span>
                                                </span>
                                            </span>

                                            <span class="mt-2 flex text-sm sm:ml-4 sm:mt-0 sm:text-right gap-1">
                                                <span class="font-medium text-gray-900">${{ price.price.unit_price.amount / 100 }}</span>
                                                <span class="ml-1 text-gray-500 sm:ml-0">/ {{ price.price.billing_cycle.interval }}</span>
                                            </span>

                                            <span :class="[checked ? 'border-primary-600' : 'border-transparent', 'border-2 pointer-events-none absolute -inset-px rounded']" aria-hidden="true" />
                                        </div>
                                    </RadioGroupOption>
                                </template>
                            </RadioGroup>
                        </fieldset>

                        <!-- @todo Do something about this. -->
                        <div class="border border-warning-200 bg-warning-100 rounded p-4" v-if="false">
                            <Paragraph color="muted" size="sm" class="">
                                <div class="font-medium mb-1">Caution</div>
                                Downgrading your plan will result in your websites being paused starting from the newest created until the limit of the plan is reached.
                            </Paragraph>
                        </div>
                    </ContentBody>

                    <ContentFoot>
                        <Button type="button" color="danger" :disabled="subscriptionForm.processing || subscription.ends_at" @click="cancelSubscription">
                            <template #icon>
                                <FontAwesomeIcon icon="fa-solid fa-times" />
                            </template>
                            
                            Cancel Subscription
                        </Button>

                        <template v-if="['active', 'past_due'].includes(subscription.status) && (subscription.ends_at === null)">
                            <Button type="button" color="primary" :disabled="subscriptionForm.processing" @click="updateSubscription">
                                <template #icon>
                                    <FontAwesomeIcon icon="fa-solid fa-save" />
                                </template>

                                Update Subscription
                            </Button>
                        </template>

                        <template v-else>
                            <Button type="button" color="primary" :disabled="subscriptionForm.processing" @click="createSubscription">
                                <template #icon>
                                    <FontAwesomeIcon icon="fa-solid fa-save" />
                                </template>

                                Subscribe
                            </Button>
                        </template>
                    </ContentFoot>
                </Card>
            </SectionGridContent>
        </SectionGrid>

        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">Payment Details</Heading>
                <Paragraph color="muted" size="sm">Change and/or modify your payment information.</Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <Form>
                        <ContentBody>
                            <FormGroup>
                                <FormLabel>Update Payment</FormLabel>
                                <Paragraph class="italic" size="sm">
                                    We've partnered with Paddle [ ... ]
                                </Paragraph>
                            </FormGroup>
                        </ContentBody>

                        <ContentFoot>
                            <Button :href="paymentMethodUpdateUrl" target="_blank" color="primary">
                                <template #icon>
                                    <FontAwesomeIcon icon="fa-solid fa-up-right-from-square" />
                                </template>

                                Update Payment Method
                            </Button>
                        </ContentFoot>
                    </Form>
                </Card>
            </SectionGridContent>
        </SectionGrid>

        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">Transaction History</Heading>
                <Paragraph color="muted" size="sm" class="mt-1">
                    The list of all payments made through subscriptions.
                </Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-x-4 gap-y-2">
                        <div>
                            <Heading :size="5">Last Payment</Heading>
                            <Paragraph size="sm" v-if="lastPayment">{{ lastPayment.amount }} ({{ lastPayment.date }})</Paragraph>
                            <Paragraph size="sm" v-else>--</Paragraph>
                        </div>

                        <div>
                            <Heading :size="5">Next Payment</Heading>
                            <Paragraph size="sm" v-if="nextPayment">{{ nextPayment.amount }} ({{ nextPayment.date }})</Paragraph>
                            <Paragraph size="sm" v-else>--</Paragraph>
                        </div>
                    </ContentBody>
                </Card>
                <Table class="rounded-t-none border-t border-gray-200">
                    <TableHead>
                        <TableRow>
                            <TableHeader>Transaction ID</TableHeader>
                            <TableHeader>Status</TableHeader>
                            <TableHeader class="text-right">Total</TableHeader>
                            <TableHeader>Currency</TableHeader>
                            <TableHeader class="text-right">Date</TableHeader>
                        </TableRow>
                    </TableHead>
                    
                    <TableBody>
                        <template v-for="(row, rowIndex) in props.transactions" :key="'row_' + rowIndex">
                            <TableRow>
                                <TableData>{{ row.paddle_id }}</TableData>
                                <TableData><Badge :icon="false" color="muted" class="capitalize">{{ row.status }}</Badge></TableData>
                                <TableData align="right">{{ row.total }}</TableData>
                                <TableData>{{ row.currency }}</TableData>
                                <TableData align="right" class="whitespace-nowrap">{{ row.billed_at }}</TableData>
                            </TableRow>
                        </template>

                        <template v-if="!props.transactions || !props.transactions.length">
                            <TableRow>
                                <TableData align="center" colspan="100%">
                                    <Paragraph size="sm" class="italic">
                                        No transactions Found.
                                    </Paragraph>
                                </TableData>
                            </TableRow>
                        </template>
                    </TableBody>
                </Table>
            </SectionGridContent>
        </SectionGrid>
    </SectionGridContainer>
</template>
