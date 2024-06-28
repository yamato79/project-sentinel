<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import PanelLayout from "@/components/layouts/panel/index.vue";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
import Container from "@/components/container.vue";
import ContentBody from "@/components/content-body.vue";
import ContentFoot from "@/components/content-foot.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormLabel from "@/components/form/form-label.vue";
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
    stack: {
        type: Object,
        required: false,
        default: () => {},
    },
});
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

        <Section>
            <SectionGridContainer>
                <SectionGrid>
                    <SectionGridSidebar>
                        <Heading :size="5">Stack Invitation</Heading>
                        <Paragraph color="muted" size="sm">Use the form to either accept or reject the stack invitation.</Paragraph>
                    </SectionGridSidebar>

                    <SectionGridContent>
                        <Card>
                            <ContentBody class="grid grid-cols-1 gap-6">
                                <FormGroup>
                                    <FormLabel>Stack Invitation</FormLabel>
                                    <Paragraph class="italic" size="sm">
                                        You have been invited to the stack, <span class="font-semibold">{{ props.stack.data.name }}</span>, by <span class="font-semibold">{{ props.stack.data.created_by_user.name }}</span>.
                                        You will gain access to websites within the stack based on permissions decided by the owner. You may choose to accept or reject this invite.
                                    </Paragraph>
                                </FormGroup>
                            </ContentBody>

                            <ContentFoot>
                                <Button :href="route('panel.stacks.users.accept-invite', { stack: props.stack.data.stack_id })" method="POST" as="button" color="success">
                                    <template #icon>
                                        <FontAwesomeIcon icon="fa-solid fa-check" />
                                    </template>

                                    Accept
                                </Button>
                                
                                <Button :href="route('panel.stacks.users.reject-invite', { stack: props.stack.data.stack_id })" method="POST" as="button" color="danger">
                                    <template #icon>
                                        <FontAwesomeIcon icon="fa-solid fa-times" />
                                    </template>
                                    
                                    Reject
                                </Button>
                            </ContentFoot>
                        </Card>
                    </SectionGridContent>
                </SectionGrid>
            </SectionGridContainer>
        </Section>
    </Container>
</template>
