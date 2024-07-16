<script setup lang="ts">
import { computed, onMounted } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import WebsiteEditIndexLayout from "./index.vue";
import Badge from "@/components/badge.vue";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
import ContentBody from "@/components/content-body.vue";
import ContentFoot from "@/components/content-foot.vue";
import FormError from "@/components/form/form-error.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import FormLabel from "@/components/form/form-label.vue";
import FormToggle from "@/components/form/form-toggle.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGridContainer from "@/components/section-grid-container.vue";
import SectionGridContent from "@/components/section-grid-content.vue";
import SectionGridSidebar from "@/components/section-grid-sidebar.vue";
import SectionGrid from "@/components/section-grid.vue";

defineOptions({ 
    layout: [PanelLayout, WebsiteEditIndexLayout],
});

const props = defineProps<{
    website: {
        data: WebsiteModel,
    },
    monitorLocations: {
        data: MonitorLocationModel[],
    },
    notificationChannelDrivers: {
        data: NotificationChannelDriverModel[],
    },
    notificationTypes: {
        data: NotificationTypeModel[],
    },
    tabs: {
        label: string;
        href: string;
    }[],
}>();

const notificationSettingsForm = useForm({
    notification_type_ids: props.notificationTypes.data.reduce((carry, notificationType) => {
        carry[notificationType.notification_type_id] = false;
        return carry;
    }, {} as { [key: string]: boolean }),
});

const notificationChannelsForm = useForm({
    notification_channels: props.notificationChannelDrivers.data.reduce((carry, notificationChannelDriver) => {
        carry[notificationChannelDriver.notification_channel_driver_id] = {
            field_values: notificationChannelDriver.fields.reduce((carry, field) => {
                carry[field.name] = "";
                return carry;
            }, {} as any),
            is_active: false,
        };
        return carry;
    }, {} as any),
});

const isLoading = computed(() => 
    notificationSettingsForm.processing || 
    notificationChannelsForm.processing
);

const submitNotificationSettingsForm = () => {
    notificationSettingsForm.patch(route("panel.websites.edit.notifications.settings", { website: props.website.data.website_id }), {
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

const submitNotificationChannelsForm = () => {
    notificationChannelsForm.patch(route("panel.websites.edit.notifications.channels", { website: props.website.data.website_id }), {
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

const getErrorField = (channelDriverId: number, fieldName: string) => {
    const key = `notification_channels.${channelDriverId}.field_values.${fieldName}` as string;
    // @ts-ignore
    return notificationChannelsForm.errors[key];
};

onMounted(() => {
    props.website.data.notification_types?.forEach((notificationType) => {
        notificationSettingsForm.notification_type_ids[notificationType.notification_type_id] = true;
    });

    props.website.data.notification_channels?.forEach((notificationChannel) => {
        const payload = {
            ...notificationChannelsForm.notification_channels[notificationChannel.notification_channel_driver_id],
            field_values: notificationChannel.field_values,
            is_active: notificationChannel.is_active,
        };

        notificationChannelsForm.notification_channels[notificationChannel.notification_channel_driver_id] = payload;
    });
});
</script>

<template>
    <Head title="Website Summary" />
    
    <MountedTeleport to="#page-title">
        <div class="flex flex-wrap items-center gap-4">
            <Paragraph class="font-semibold">{{ props.website.data.name }}</Paragraph>
            <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
            <template v-if="props.website.data.website_status">
                <Badge :color="props.website.data.website_status.color">
                    {{ props.website.data.website_status.name }}
                </Badge>
            </template>
        </div>
    </MountedTeleport>

    <SectionGridContainer>
        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">Notification Settings</Heading>
                <Paragraph color="muted" size="sm">Control what notifications are enabled for this website.</Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <Form @submit.prevent="submitNotificationSettingsForm">
                        <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <template v-for="(notificationType, notificationTypeIndex) in notificationTypes.data" :key="'notificationType_' + notificationTypeIndex">
                                <FormGroup class="col-span-full sm:col-span-2">
                                    <FormToggle v-model="notificationSettingsForm.notification_type_ids[notificationType.notification_type_id]" :disabled="isLoading">
                                        <div class="line-clamp-2">
                                            <FormLabel :for="notificationType.slug">{{ notificationType.name }}</FormLabel>
                                            {{ notificationType.description }}
                                        </div>
                                    </FormToggle>
                                    <FormError v-if="notificationSettingsForm.errors.notification_type_ids">{{ notificationSettingsForm.errors.notification_type_ids }}</FormError>
                                </FormGroup>
                            </template>
                        </ContentBody>

                        <ContentFoot>
                            <Button type="submit" color="primary" :is-loading="notificationSettingsForm.processing" :disabled="isLoading">
                                <template #icon>
                                    <FontAwesomeIcon icon="fa-solid fa-save" />
                                </template>

                                Save Changes
                            </Button>
                        </ContentFoot>
                    </Form>
                </Card>
            </SectionGridContent>
        </SectionGrid>

        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">Notification Channels</Heading>
                <Paragraph color="muted" size="sm">Control where you receive your notifications.</Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <Form @submit.prevent="submitNotificationChannelsForm">
                        <div class="divide-y divide-gray-100">
                            <template v-for="(notificationChannelDriver, notificationChannelDriverIndex) in notificationChannelDrivers.data" :key="'notificationChannelDriver_' + notificationChannelDriverIndex">
                                <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <FormGroup class="col-span-full">
                                        <div class="flex items-center justify-between">
                                            <div class="">
                                                <Heading :size="5">
                                                    {{ notificationChannelDriver.name }}
                                                </Heading>

                                                <Paragraph color="muted" size="sm">
                                                    {{ notificationChannelDriver.description || "--" }}
                                                </Paragraph>
                                            </div>

                                            <div class="h-full flex flex-col items-start">
                                                <FormToggle v-model="notificationChannelsForm.notification_channels[notificationChannelDriver.notification_channel_driver_id].is_active" :disabled="isLoading" />
                                            </div>
                                        </div>
                                    </FormGroup>

                                    <template v-for="(field, fieldIndex) in notificationChannelDriver.fields" :key="'field_' + fieldIndex">
                                        <FormGroup :class="field.container_class">
                                            <FormLabel :for="field.name" :required="field.required">{{ field.label }}</FormLabel>

                                            <template v-if="field.type === 'text'">
                                                <FormInput type="text" :name="field.name" v-model="notificationChannelsForm.notification_channels[notificationChannelDriver.notification_channel_driver_id].field_values[field.name]" :placeholder="field.placeholder" :disabled="isLoading" :required="field.required" />
                                                <FormError v-if="getErrorField(notificationChannelDriver.notification_channel_driver_id, field.name)">
                                                    {{ getErrorField(notificationChannelDriver.notification_channel_driver_id, field.name) }}
                                                </FormError>
                                            </template>

                                            <template v-else>
                                                <FormError>
                                                    The field type '{{ field.type }}' is not supported.
                                                </FormError>
                                            </template>
                                        </FormGroup>
                                    </template>
                                </ContentBody>
                            </template>
                        </div>

                        <ContentFoot>
                            <Button type="submit" color="primary" :is-loading="notificationChannelsForm.processing" :disabled="isLoading">
                                <template #icon>
                                    <FontAwesomeIcon icon="fa-solid fa-save" />
                                </template>

                                Save Changes
                            </Button>
                        </ContentFoot>
                    </Form>
                </Card>
            </SectionGridContent>
        </SectionGrid>
    </SectionGridContainer>
</template>
