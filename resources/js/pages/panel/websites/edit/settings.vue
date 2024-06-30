<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import WebsiteEditIndexLayout from "./index.vue";
import Badge from "@/components/badge.vue";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
import ContentBody from "@/components/content-body.vue";
import ContentFoot from "@/components/content-foot.vue";
import FormCheckbox from "@/components/form/form-checkbox.vue";
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

const props = defineProps({
    website: {
        type: Object,
        required: false,
        default: () => {},
    },
    monitorLocations: {
        type: Object,
        required: true,
    },
    tabs: {
        type: Array,
        required: false,
        default: () => [],
    },
});

const form = useForm({
    name: props.website.data.name,
    address: props.website.data.address,
    is_monitor_active: props.website.data.is_monitor_active,
    monitor_location_ids: props.website.data.monitor_location_ids,
});

const deleteForm = useForm({
    // ...
});

const submitForm = () => {
    form.put(route("panel.websites.update", { website: props.website.data.website_id }), {
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

const submitDeleteForm = () => {
    if (!confirm(`Are you sure you want to delete ${props.website.data.name}?`)) {
        console.log("TODO: Add cancelled toast notification.");
        return;
    }

    deleteForm.delete(route("panel.websites.destroy", { website: props.website.data.website_id }), {
        onSuccess: () => {
            console.log("TODO: Add success toast notification.");
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
    <Head title="Website Summary" />
    
    <MountedTeleport to="#page-title">
        <div class="flex flex-wrap items-center gap-4">
            <Paragraph class="font-semibold">{{ props.website.data.name }}</Paragraph>
            <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
            <Badge :color="props.website.data.website_status.color">{{ props.website.data.website_status.name }}</Badge>
        </div>
    </MountedTeleport>

    <SectionGridContainer>
        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">General Information</Heading>
                <Paragraph color="muted" size="sm">Basic details about the website to be monitored.</Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <Form @submit.prevent="submitForm">
                        <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <FormGroup>
                                <FormLabel for="name" :required="true">Name</FormLabel>
                                <FormInput type="text" id="name" name="name" v-model="form.name" placeholder="Acme - Marketing Website" :disabled="form.processing || deleteForm.processing" :required="true" />
                                <FormError v-if="form.errors.name">{{ form.errors.name }}</FormError>
                            </FormGroup>

                            <FormGroup>
                                <FormLabel for="address" :required="true">Address</FormLabel>
                                <FormInput type="text" id="address" name="address" v-model="form.address" placeholder="https://acme.com" :disabled="form.processing" :required="true" />
                                <FormError v-if="form.errors.address">{{ form.errors.address }}</FormError>
                            </FormGroup>

                            <FormGroup class="col-span-full sm:col-span-1">
                                <FormLabel for="is_active" :required="true">Monitor Active</FormLabel>
                                <FormToggle id="is_active" name="is_active" v-model="form.is_monitor_active" :disabled="form.processing || deleteForm.processing">Turn the monitor on or off.</FormToggle>
                                <FormError v-if="form.errors.is_monitor_active">{{ form.errors.is_monitor_active }}</FormError>
                            </FormGroup>

                            <FormGroup class="col-span-full">
                                <FormLabel for="address" :required="true">Monitor Locations</FormLabel>
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-2">
                                    <template v-for="(monitorLocation, monitorLocationIndex) in props.monitorLocations.data" :key="'monitorLocation_' + monitorLocationIndex">
                                        <FormLabel class="inline-flex items-center justify-start gap-2 cursor-pointer">
                                            <FormCheckbox :value="monitorLocation.monitor_location_id" v-model="form.monitor_location_ids" :disabled="form.processing" />
                                            {{ monitorLocation.name }}
                                        </FormLabel>
                                    </template>
                                </div>
                                <FormError v-if="form.errors.monitor_location_ids">{{ form.errors.monitor_location_ids }}</FormError>
                            </FormGroup>
                        </ContentBody>

                        <ContentFoot>
                            <Button type="submit" color="primary" :is-loading="form.processing" :disabled="form.processing || deleteForm.processing">
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
                <Heading :size="5">Delete Website</Heading>
                <Paragraph color="muted" size="sm">Permanently remove this website and all its information.</Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <Form @submit.prevent="submitDeleteForm">
                        <ContentBody>
                            <FormGroup>
                                <FormLabel>Delete Website</FormLabel>
                                <Paragraph class="italic" size="sm">
                                    <span class="font-semibold">Warning:</span>
                                    This is a permanent action, it cannot be undone. Please make sure you are sure you want to delete the website, "{{ form.name }}", before performing any actions.
                                </Paragraph>
                            </FormGroup>
                        </ContentBody>

                        <ContentFoot>
                            <Button type="submit" color="danger" :is-loading="deleteForm.processing" :disabled="form.processing || deleteForm.processing">
                                <template #icon>
                                    <FontAwesomeIcon icon="fa-solid fa-trash" />
                                </template>

                                Delete Website
                            </Button>
                        </ContentFoot>
                    </Form>
                </Card>
            </SectionGridContent>
        </SectionGrid>
    </SectionGridContainer>
</template>
