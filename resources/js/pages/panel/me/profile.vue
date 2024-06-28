<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import Container from "@/components/container.vue";
import Heading from "@/components/heading.vue";
import MountedTeleport from "@/components/mounted-teleport.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGridContainer from "@/components/section-grid-container.vue";
import SectionGridContent from "@/components/section-grid-content.vue";
import SectionGridSidebar from "@/components/section-grid-sidebar.vue";
import SectionGrid from "@/components/section-grid.vue";
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

        <Section>
            <SectionGridContainer>
                <SectionGrid>
                    <SectionGridSidebar>
                        <Heading :size="5">Personal Information</Heading>
                        <Paragraph color="muted" size="sm" class="mt-1">Information about who the owner of the account is.</Paragraph>
                    </SectionGridSidebar>

                    <SectionGridContent>
                        <UpdateProfileForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                        />
                    </SectionGridContent>
                </SectionGrid>

                <SectionGrid>
                    <SectionGridSidebar>
                        <Heading :size="5">Security Information</Heading>
                        <Paragraph color="muted" size="sm" class="mt-1">Information on how to access your account.</Paragraph>
                    </SectionGridSidebar>

                    <SectionGridContent>
                        <UpdatePasswordForm />
                    </SectionGridContent>
                </SectionGrid>
            </SectionGridContainer>
        </Section>
    </Container>
</template>
