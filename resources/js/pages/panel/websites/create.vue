<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import { router, useForm } from "@inertiajs/vue3";
import Button from "@/components/button.vue";
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import ContentBody from "@/components/content-body.vue";
import ContentFoot from "@/components/content-foot.vue";
import FormError from "@/components/form/form-error.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import FormLabel from "@/components/form/form-label.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import Section from "@/components/section.vue";
import Spinner from "@/components/spinner.vue";

defineOptions({ 
    layout: PanelLayout,
});

const form = useForm({
    name: "",
    address: "",
});

const submitForm = () => {
    form.post(route('panel.websites.store'), {
        onSuccess: (response) => {
            console.log("TODO: Add success toast notification.", response);
            console.log("TODO: Add redirect to created website.");
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

        <MountedTeleport to="#breadcrumbs">
            <BreadcrumbItem :href="route('panel.websites.index')"> Websites </BreadcrumbItem>
            <BreadcrumbItem :href="route('panel.websites.create')"> Create </BreadcrumbItem>
        </MountedTeleport>

        <Section>
            <div class="space-y-10 divide-y-2 divide-gray-200 divide-dashed">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                    <div>
                        <Heading :size="5">General Information</Heading>
                        <Paragraph color="muted" size="sm">Basic details about the website to be monitored.</Paragraph>
                    </div>

                    <Card class="md:col-span-2">
                        <Form @submit.prevent="submitForm">
                            <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
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
                            </ContentBody>

                            <ContentFoot>
                                <Button :href="route('panel.websites.index')" color="muted" :disabled="form.processing">
                                    Cancel
                                </Button>

                                <Button type="submit" color="primary" :disabled="form.processing">
                                    <template v-if="form.processing">
                                        <Spinner color="white" size="sm"></Spinner>
                                    </template>

                                    <template v-else>
                                        <FontAwesomeIcon :icon="'fa-solid fa-plus'" :class="'text-gray-50'" aria-hidden="true" />
                                    </template>

                                    Create Website
                                </Button>
                            </ContentFoot>
                        </Form>
                    </Card>
                </div>
            </div>
        </Section>
    </Container>
</template>
