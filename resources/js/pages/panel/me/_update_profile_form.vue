<script setup lang="ts">
import { useForm, usePage } from "@inertiajs/vue3";
import Button from "@/components/button.vue";
import Card from "@/components/card.vue";
import ContentBody from "@/components/content-body.vue";
import ContentFoot from "@/components/content-foot.vue";
import FormError from "@/components/form/form-error.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import FormLabel from "@/components/form/form-label.vue";
import Form from "@/components/form/form.vue";

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const page = usePage();
const user = page.props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
});

const submitForm = () => {
    form.patch(route("panel.profile.update"), {
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
    <Card>
        <Form @submit.prevent="submitForm">
            <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <FormGroup class="sm:col-span-1">
                    <FormLabel for="name">Name</FormLabel>
                    <FormInput type="text" id="name" name="name" v-model="form.name" :disabled="form.processing" :required="true" autofocus />
                    <FormError v-if="form.errors.name">{{ form.errors.name }}</FormError>
                </FormGroup>

                <FormGroup class="sm:col-span-1">
                    <FormLabel for="email">Email Address</FormLabel>
                    <FormInput type="email" id="email" name="email" v-model="form.email" :disabled="form.processing" :required="true" />
                    <FormError v-if="form.errors.email">{{ form.errors.email }}</FormError>
                </FormGroup>
            </ContentBody>

            <ContentFoot>
                <Button type="submit" color="primary" :is-loading="form.processing" :disabled="form.processing">
                    <template #icon>
                        <FontAwesomeIcon icon="fa-solid fa-save" />
                    </template>
                    
                    Save Changes
                </Button>
            </ContentFoot>
        </Form>
    </Card>
</template>
