<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import { useForm } from "@inertiajs/vue3";
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
import Paragraph from "@/components/paragraph.vue";
import SectionGridContainer from "@/components/section-grid-container.vue";
import SectionGridContent from "@/components/section-grid-content.vue";
import SectionGridSidebar from "@/components/section-grid-sidebar.vue";
import SectionGrid from "@/components/section-grid.vue";
import Section from "@/components/section.vue";

defineOptions({ 
    layout: PanelLayout,
});

const form = useForm({
    name: "",
    description: "",
});

const submitForm = () => {
    form.post(route("panel.stacks.store"), {
        onSuccess: (response) => {
            console.log("TODO: Add success toast notification.", response);
            console.log("TODO: Add redirect to created stack.");
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
        <Head title="Create Stack" />

        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold"> Create Stack </Paragraph>
                <div class="w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm"> Use the form below to create a new stack. </Paragraph>
            </div>
        </MountedTeleport>

        <Section>
            <SectionGridContainer>
                <SectionGrid>
                    <SectionGridSidebar>
                        <Heading :size="5">General Information</Heading>
                        <Paragraph color="muted" size="sm">Basic details about your stack.</Paragraph>
                    </SectionGridSidebar>

                    <SectionGridContent>
                        <Card>
                            <Form @submit.prevent="submitForm">
                                <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <FormGroup>
                                        <FormLabel for="name" :required="true">Name</FormLabel>
                                        <FormInput type="text" id="name" name="name" v-model="form.name" placeholder="Acme Websites" :disabled="form.processing" :required="true" />
                                        <FormError v-if="form.errors.name">{{ form.errors.name }}</FormError>
                                    </FormGroup>

                                    <FormGroup>
                                        <FormLabel for="description" :required="true">Description</FormLabel>
                                        <FormInput type="text" id="description" name="description" v-model="form.description" placeholder="Short description of your stack." :disabled="form.processing" :required="true" />
                                        <FormError v-if="form.errors.description">{{ form.errors.description }}</FormError>
                                    </FormGroup>
                                </ContentBody>

                                <ContentFoot>
                                    <Button :href="route('panel.stacks.index')" color="muted" :disabled="form.processing">
                                        Cancel
                                    </Button>

                                    <Button type="submit" color="primary" :is-loading="form.processing" :disabled="form.processing">
                                        <template #icon>
                                            <FontAwesomeIcon icon="fa-solid fa-plus" />
                                        </template>

                                        Create Stack
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
