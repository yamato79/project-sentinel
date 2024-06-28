<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import StackEditIndexLayout from "./index.vue";
import Badge from "@/components/badge.vue";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
import ContentBody from "@/components/content-body.vue";
import FormError from "@/components/form/form-error.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import FormToggle from "@/components/form/form-toggle.vue";
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
        StackEditIndexLayout,
    ],
});

const props = defineProps({
    canEdit: {
        type: Boolean,
        required: false,
        default: () => false,
    },
    stack: {
        type: Object,
        required: false,
        default: () => {},
    },
    users: {
        type: Object,
        required: false,
        default: () => {},
    },
});

const attachUserForm = useForm({
    email: "",
});

const detachUserForm = useForm<{
    user_id: number | null
}>({
    user_id: null,
});

const updateUserForm = useForm<{
    user_id: number | null
    can_view: boolean,
    can_edit: boolean,
}>({
    user_id: null,
    can_view: false,
    can_edit: false,
});

const formsProcessing = computed(() => 
    attachUserForm.processing || 
    detachUserForm.processing ||
    updateUserForm.processing
);

const attachUser = () => {
    attachUserForm
        .post(route("panel.stacks.users.attach", { stack: props.stack.data.stack_id }), {
            preserveScroll: true,
            onSuccess: (response) => {
                console.log("TODO: Add success toast notification.", response);
                attachUserForm.email = "";
            },
            onError: (errors) => {
                Object.keys(errors).forEach((key) => {
                    const error = errors[key];
                    console.error("TODO: Add error toast notification.", error);
                });
            },
        });
};

const detachUser = (user_id: number, user_name: string) => {
    if (!confirm(`Are you sure you want to remove ${user_name} from the stack?`)) {
        console.log("TODO: Add cancelled toast notification.");
        return;
    }

    detachUserForm.user_id = user_id;

    detachUserForm
        .post(route("panel.stacks.users.detach", { stack: props.stack.data.stack_id }), {
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
            onFinish: () => {
                detachUserForm.user_id = null;
            },
        });
};

const updateUser = ({ user_id, can_view, can_edit }: { user_id: number, can_view: boolean, can_edit: boolean }) => {
    updateUserForm.user_id = user_id;
    updateUserForm.can_view = can_view;
    updateUserForm.can_edit = can_edit;

    updateUserForm
        .post(route("panel.stacks.users.update", { stack: props.stack.data.stack_id }), {
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
            onFinish: () => {
                updateUserForm.user_id = null;
            },
        });
};
</script>

<template>
    <Head title="Stack Members" />
    
    <MountedTeleport to="#page-title">
        <div class="flex flex-wrap items-center gap-4">
            <Paragraph class="font-semibold">{{ props.stack.data.name }}</Paragraph>
            <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
            <Paragraph color="muted" size="sm" class="hidden lg:block">{{  props.stack.data.description }}</Paragraph>
        </div>
    </MountedTeleport>

    <SectionGridContainer>
        <SectionGrid>
            <SectionGridSidebar>
                <Heading :size="5">Stack Members</Heading>
                <Paragraph color="muted" size="sm">Manage which of your members are included in the stack.</Paragraph>
            </SectionGridSidebar>

            <SectionGridContent>
                <Card>
                    <template v-if="canEdit">
                        <ContentBody class="border-b border-gray-200">
                            <Form @submit.prevent="attachUser">
                                <FormGroup class="w-full">
                                    <div class="w-full flex items-center justify-between">
                                        <div class="w-full">
                                            <FormInput type="text" name="email" id="email" class="rounded-r-none" placeholder="john.doe@example.com" v-model="attachUserForm.email" autofocus />
                                        </div>

                                        <Button type="submit" color="primary" class="rounded-l-none" :is-loading="attachUserForm.processing" :disabled="formsProcessing">
                                            <template #icon>
                                                <FontAwesomeIcon icon="fa-solid fa-paper-plane" />
                                            </template>

                                            Invite
                                        </Button>
                                    </div>

                                    <FormError v-if="attachUserForm.errors.email">{{ attachUserForm.errors.email }}</FormError>
                                </FormGroup>
                            </Form>
                        </ContentBody>
                    </template>

                    <Table class="rounded-none">
                        <TableHead>
                            <TableRow>
                                <TableHeader>Name</TableHeader>
                                <TableHeader>Status</TableHeader>
                                
                                <template v-if="canEdit">
                                    <TableHeader class="text-center">Can Edit</TableHeader>
                                    <TableHeader class="text-right">&nbsp;</TableHeader>
                                </template>
                            </TableRow>
                        </TableHead>
                        
                        <TableBody>
                            <template v-for="(user, userIndex) in users.data" :key="'user_' + userIndex">
                                <TableRow>
                                    <TableData class="w-full whitespace-nowrap">
                                        {{ user.name }}
                                    </TableData>
                                    
                                    <TableData>
                                        <template v-if="props.stack.data.created_by_user_id == user.user_id">
                                            <Badge color="primary">Owner</Badge>
                                        </template>

                                        <template v-else-if="user.pivot && !user.pivot.joined_at">
                                            <Badge color="warning">Invited</Badge>
                                        </template>

                                        <template v-else>
                                            <Badge color="muted">Member</Badge>
                                        </template>
                                    </TableData>

                                    <template v-if="canEdit">
                                        <TableData align="center">
                                            <div class="flex items-center justify-center">
                                                <template v-if="user.pivot && ('can_view' in user.pivot) && ('can_edit' in user.pivot)">
                                                    <FormToggle
                                                        v-model="(user.pivot.can_edit as boolean)"
                                                        :disabled="props.stack.data.created_by_user_id == user.user_id"
                                                        @toggle="updateUser({
                                                            user_id: user.user_id,
                                                            can_view: user.pivot.can_view,
                                                            can_edit: user.pivot.can_edit
                                                        })"
                                                    />
                                                </template>

                                                <template v-else>
                                                    <FormToggle :disabled="true" />
                                                </template>
                                            </div>
                                        </TableData>

                                        <TableData>
                                            <Button type="button" color="muted" size="sm" :disabled="detachUserForm.processing || props.stack.data.created_by_user_id == user.user_id" @click.stop="detachUser(user.user_id, user.name)">
                                                <FontAwesomeIcon :icon="'fa-solid fa-trash'" />
                                            </Button>
                                        </TableData>
                                    </template>
                                </TableRow>
                            </template>

                            <template v-if="!users.data || !users.data.length">
                                <TableRow>
                                    <TableData align="center" colspan="100%">
                                        <Paragraph size="sm" class="italic">
                                            No Members Found.
                                        </Paragraph>
                                    </TableData>
                                </TableRow>
                            </template>
                        </TableBody>
                    </Table>
                </Card>
            </SectionGridContent>
        </SectionGrid>
    </SectionGridContainer>
</template>
