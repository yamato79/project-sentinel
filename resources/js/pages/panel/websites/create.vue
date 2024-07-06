<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import ContentBody from "@/components/content-body.vue";
import ContentFoot from "@/components/content-foot.vue";
import FormCheckbox from "@/components/form/form-checkbox.vue";
import FormError from "@/components/form/form-error.vue";
import FormGrid from "@/components/form/form-grid.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import FormLabel from "@/components/form/form-label.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGridContainer from "@/components/section-grid-container.vue";
import SectionGridContent from "@/components/section-grid-content.vue";
import SectionGridSidebar from "@/components/section-grid-sidebar.vue";
import SectionGrid from "@/components/section-grid.vue";
import Section from "@/components/section.vue";

defineOptions({ 
    layout: PanelLayout,
});

const props = defineProps({
    monitorLocations: {
        type: Object,
        required: true,
    },
});

const form = useForm({
    name: "",
    address: "",
    monitor_location_ids: [],
});

const submitForm = () => {
    form.post(route("panel.websites.store"), {
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
    <Container>
        <Head title="Create Website" />

        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold"> Create Website </Paragraph>
                <div class="w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm"> Use the form below to create a new website. </Paragraph>
            </div>
        </MountedTeleport>

        <Section>
            <SectionGridContainer>
                <SectionGrid>
                    <SectionGridSidebar>
                        <Heading :size="5">General Information</Heading>
                        <Paragraph color="muted" size="sm">Basic details about the website to be monitored.</Paragraph>
                    </SectionGridSidebar>

                    <SectionGridContent>
                        <Card>
                            <Form @submit.prevent="submitForm">
                                <ContentBody>
                                    <FormGrid>
                                        <FormGroup>
                                            <FormLabel for="name" :required="true">Name</FormLabel>
                                            <FormInput type="text" id="name" name="name" v-model="form.name" placeholder="Acme - Marketing Website" :disabled="form.processing" :required="true" />
                                            <FormError v-if="form.errors.name">{{ form.errors.name }}</FormError>
                                        </FormGroup>

                                        <FormGroup>
                                            <FormLabel for="address" :required="true">Address</FormLabel>
                                            <FormInput type="text" id="address" name="address" v-model="form.address" placeholder="https://acme.com" :disabled="form.processing" :required="true" />
                                            <FormError v-if="form.errors.address">{{ form.errors.address }}</FormError>
                                        </FormGroup>

                                        <FormGroup class="col-span-full">
                                            <FormLabel for="address" :required="true">Monitor Locations</FormLabel>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-x-6 gap-y-2">
                                                <template v-for="(monitorLocation, monitorLocationIndex) in props.monitorLocations.data" :key="'monitorLocation_' + monitorLocationIndex">
                                                    <FormLabel class="inline-flex items-center justify-start gap-2 cursor-pointer">
                                                        <FormCheckbox :value="monitorLocation.monitor_location_id" v-model="form.monitor_location_ids" :disabled="form.processing" />
                                                        {{ monitorLocation.name }}
                                                    </FormLabel>
                                                </template>
                                            </div>
                                            <FormError v-if="form.errors.monitor_location_ids">{{ form.errors.monitor_location_ids }}</FormError>
                                        </FormGroup>
                                    </FormGrid>
                                </ContentBody>

                                <ContentFoot>
                                    <Button :href="route('panel.websites.index')" color="muted" :disabled="form.processing">
                                        Cancel
                                    </Button>

                                    <Button type="submit" color="primary" :is-loading="form.processing" :disabled="form.processing">
                                        <template #icon>
                                            <FontAwesomeIcon icon="fa-solid fa-plus" />
                                        </template>

                                        Create Website
                                    </Button>
                                </ContentFoot>
                            </Form>
                        </Card>
                    </SectionGridContent>
                </SectionGrid>
            </SectionGridContainer>
        </Section>
    </Container>
</template>
