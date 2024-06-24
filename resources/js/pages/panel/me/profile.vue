<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import BreadcrumbItem from "@/components/breadcrumb-item.vue";
import Container from "@/components/container.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import Section from "@/components/section.vue";
import UpdatePasswordForm from "./_update_password_form.vue";
import UpdateProfileForm from "./_update_profile_form.vue";

defineOptions({ 
    layout: PanelLayout,
});

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Container>
        <Head title="Profile" />

        <MountedTeleport to="#page-title">
            <div class="flex flex-wrap items-center gap-4">
                <Paragraph class="font-semibold">Profile</Paragraph>
                <div class="hidden lg:block w-px h-6 bg-gray-200"></div>
                <Paragraph color="muted" size="sm" class="hidden lg:block">Manage your account information.</Paragraph>
            </div>
        </MountedTeleport>

        <MountedTeleport to="#breadcrumbs">
            <BreadcrumbItem :href="route('panel.profile.edit')"> Profile </BreadcrumbItem>
        </MountedTeleport>

        <Section>
            <div class="space-y-10 divide-y-2 divide-gray-200 divide-dashed">
                <div class="grid grid-cols-1 gap-x-8 gap-y-8 md:grid-cols-3">
                    <div>
                        <Heading :size="5">Personal Information</Heading>
                        <Paragraph color="muted" size="sm" class="mt-1">Information about who the owner of the account is.</Paragraph>
                    </div>

                    <div class="md:col-span-2">
                        <UpdateProfileForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-x-8 gap-y-8 pt-8 md:grid-cols-3">
                    <div>
                        <Heading :size="5">Security Information</Heading>
                        <Paragraph color="muted" size="sm" class="mt-1">Information on how to access your account.</Paragraph>
                    </div>

                    <div class="md:col-span-2">
                        <UpdatePasswordForm />
                    </div>
                </div>
            </div>
        </Section>
    </Container>
</template>
