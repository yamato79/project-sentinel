<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm, usePage } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import StackEditIndexLayout from "./index.vue";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
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

defineOptions({ 
    layout: [
        PanelLayout,
        StackEditIndexLayout,
    ],
});

const page = usePage();
const isOwnerAuthed = computed(() => (props.stack.data.created_by_user_id == page.props.auth.user.user_id));

const props = defineProps({
    stack: {
        type: Object,
        required: false,
        default: () => {},
    },
});

const form = useForm({
    name: props.stack.data.name,
    description: props.stack.data.description,
});

const deleteForm = useForm({
    // ...
});

const leaveForm = useForm({
    // ...
});

const formsProcessing = computed(() => (
    form.processing ||
    deleteForm.processing ||
    leaveForm.processing
));

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

const submitLeaveForm = () => {
    if (!confirm(`Are you sure you want to leave ${props.stack.data.name}?`)) {
        console.log("TODO: Add cancelled toast notification.");
        return;
    }

    leaveForm.delete(route("panel.stacks.users.leave", { stack: props.stack.data.stack_id }), {
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
    <Head title="Stack Settings" />
    
    <MountedTeleport to="#page-title">
        <div class="flex flex-wrap items-center gap-4">
            <Paragraph class="font-semibold">{{ props.stack.data.name }}</Paragraph>
            <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
            <Paragraph color="muted" size="sm" class="hidden lg:block">{{  props.stack.data.description }}</Paragraph>
        </div>
    </MountedTeleport>

    <SectionGridContainer>
        <template v-if="isOwnerAuthed">
            <SectionGrid>
                <SectionGridSidebar>
                    <Heading :size="5">General Information</Heading>
                    <Paragraph color="muted" size="sm">Basic details about the stack to be monitored.</Paragraph>
                </SectionGridSidebar>

                <SectionGridContent>
                    <Card>
                        <Form @submit.prevent="submitForm">
                            <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <FormGroup>
                                    <FormLabel for="name" :required="true">Name</FormLabel>
                                    <FormInput type="text" id="name" name="name" v-model="form.name" placeholder="Acme - Marketing Stack" :disabled="formsProcessing" :required="true" />
                                    <FormError v-if="form.errors.name">{{ form.errors.name }}</FormError>
                                </FormGroup>

                                <FormGroup>
                                    <FormLabel for="description" :required="true">Description</FormLabel>
                                    <FormInput type="text" id="description" name="description" v-model="form.description" placeholder="https://acme.com" :disabled="formsProcessing" :required="true" />
                                    <FormError v-if="form.errors.description">{{ form.errors.description }}</FormError>
                                </FormGroup>
                            </ContentBody>

                            <ContentFoot>
                                <Button type="submit" color="primary" :is-loading="form.processing" :disabled="formsProcessing">
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
        </template>
        
        <template v-if="!isOwnerAuthed">
            <SectionGrid>
                <SectionGridSidebar>
                    <Heading :size="5">Leave Stack</Heading>
                    <Paragraph color="muted" size="sm">Revoke your membership from this stack including access to its exclusive websites.</Paragraph>
                </SectionGridSidebar>

                <SectionGridContent>
                    <Card>
                        <Form @submit.prevent="submitLeaveForm">
                            <ContentBody class="grid grid-cols-1 gap-6">
                                <FormGroup>
                                    <FormLabel>Leave Stack</FormLabel>
                                    <Paragraph class="italic" size="sm">
                                        <span class="font-semibold">Warning:</span>
                                        You are goign to leave the stack. Websites that you have access to exclusively through the stack will be revoked. Please make sure you are sure you want to leave the stack, "{{ form.name }}", before performing any actions.
                                    </Paragraph>
                                </FormGroup>
                            </ContentBody>

                            <ContentFoot>
                                <Button type="submit" color="default" is-loading="leaveForm.processing" :disabled="formsProcessing">
                                    <template #icon>
                                        <FontAwesomeIcon icon="fa-solid fa-trash" />
                                    </template>

                                    Leave Stack
                                </Button>
                            </ContentFoot>
                        </Form>
                    </Card>
                </SectionGridContent>
            </SectionGrid>
        </template>

        <template v-else>
            <SectionGrid>
                <SectionGridSidebar>
                    <Heading :size="5">Delete Stack</Heading>
                    <Paragraph color="muted" size="sm">Permanently remove this stack and all its information.</Paragraph>
                </SectionGridSidebar>

                <SectionGridContent>
                    <Card>
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
                                <Button type="submit" color="danger" :is-loading="deleteForm.processing" :disabled="formsProcessing">
                                    <template #icon>
                                        <FontAwesomeIcon icon="fa-solid fa-trash" />
                                    </template>

                                    Delete Stack
                                </Button>
                            </ContentFoot>
                        </Form>
                    </Card>
                </SectionGridContent>
            </SectionGrid>
        </template>
    </SectionGridContainer>
</template>
