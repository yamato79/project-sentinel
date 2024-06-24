<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import Button from "@/components/button.vue";
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
import Paragraph from '@/components/paragraph.vue';
import Section from "@/components/section.vue";
import Spinner from "@/components/spinner.vue";
import Tabs from "./_tabs.vue";

const emit = defineEmits(["update-stack"]);

const props = defineProps({
    stack: {
        type: Object,
        required: false,
        default: () => {},
    },
});

defineOptions({ 
    layout: PanelLayout,
});

const form = useForm({
    name: props.stack.data.name,
    description: props.stack.data.description,
});

const deleteForm = useForm({
    // ...
});

const submitForm = () => {
    form.put(route("panel.stacks.update", { stack: props.stack.data.stack_id }), {
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
    if (!confirm(`Are you sure you want to delete ${props.stack.data.name}?`)) {
        console.log("TODO: Add cancelled toast notification.");
        return;
    }

    deleteForm.delete(route("panel.stacks.destroy", { stack: props.stack.data.stack_id }), {
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
    <Container>
        <Head title="Stack Settings" />
        
        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold">{{ props.stack.data.name }}</Paragraph>
                <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm" class="hidden lg:block">{{  props.stack.data.description }}</Paragraph>
            </div>
        </MountedTeleport>

        <MountedTeleport to="#breadcrumbs">
            <BreadcrumbItem :href="route('panel.stacks.index', { stack: props.stack.data.stack_id })">Stacks</BreadcrumbItem>
            <BreadcrumbItem :href="route('panel.stacks.edit', { stack: props.stack.data.stack_id })">{{ props.stack.data.name }}</BreadcrumbItem>
            <BreadcrumbItem :href="route('panel.stacks.edit.settings', { stack: props.stack.data.stack_id })">Settings</BreadcrumbItem>
        </MountedTeleport>

        <Section>
            <Tabs :stack-id="props.stack.data.stack_id" />

            <div class="space-y-10 divide-y-2 divide-gray-200 divide-dashed">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                    <div>
                        <Heading :size="5">General Information</Heading>
                        <Paragraph color="muted" size="sm">Basic details about the stack to be monitored.</Paragraph>
                    </div>

                    <Card class="md:col-span-2">
                        <Form @submit.prevent="submitForm">
                            <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <FormGroup>
                                    <FormLabel for="name" :required="true">Name</FormLabel>
                                    <FormInput type="text" id="name" name="name" v-model="form.name" placeholder="Acme - Marketing Stack" :disabled="form.processing" :required="true" />
                                    <FormError v-if="form.errors.name">{{ form.errors.name }}</FormError>
                                </FormGroup>

                                <FormGroup>
                                    <FormLabel for="description" :required="true">Description</FormLabel>
                                    <FormInput type="text" id="description" name="description" v-model="form.description" placeholder="https://acme.com" :disabled="form.processing" :required="true" />
                                    <FormError v-if="form.errors.description">{{ form.errors.description }}</FormError>
                                </FormGroup>
                            </ContentBody>

                            <ContentFoot>
                                <Button type="submit" color="primary" :disabled="form.processing">
                                    <template v-if="form.processing">
                                        <Spinner color="white" size="xs"></Spinner>
                                    </template>

                                    <template v-else>
                                        <FontAwesomeIcon :icon="'fa-solid fa-save'" :class="'text-gray-50'" aria-hidden="true" />
                                    </template>

                                    Save Changes
                                </Button>
                            </ContentFoot>
                        </Form>
                    </Card>
                </div>
                
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3 pt-10">
                    <div>
                        <Heading :size="5">Delete Stack</Heading>
                        <Paragraph color="muted" size="sm">Permanently remove this stack and all its information.</Paragraph>
                    </div>

                    <Card class="md:col-span-2">
                        <Form @submit.prevent="submitDeleteForm">
                            <ContentBody class="grid grid-cols-1 gap-6">
                                <FormGroup>
                                    <FormLabel>Delete Stack</FormLabel>
                                    <Paragraph class="italic" size="sm">
                                        <span class="font-semibold">Warning:</span>
                                        This is a permanent action, it cannot be undone. Please make sure you are sure you want to delete the stack, "{{ form.name }}", before performing any actions.
                                    </Paragraph>
                                </FormGroup>
                            </ContentBody>

                            <ContentFoot>
                                <Button type="submit" color="danger" :disabled="form.processing">
                                    <template v-if="deleteForm.processing">
                                        <Spinner color="white" size="xs"></Spinner>
                                    </template>

                                    <template v-else>
                                        <FontAwesomeIcon :icon="'fa-solid fa-trash'" aria-hidden="true" />
                                    </template>

                                    Delete Stack
                                </Button>
                            </ContentFoot>
                        </Form>
                    </Card>
                </div>
            </div>
        </Section>
    </Container>
</template>
