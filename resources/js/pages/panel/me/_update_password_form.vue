<script setup lang="ts">
import { ref } from "vue";
import { useForm } from "@inertiajs/vue3";
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

const passwordInput = ref<HTMLInputElement | null>(null);
const currentPasswordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    current_password: "",
    password: "",
    password_confirmation: "",
});

const submitForm = () => {
    form.put(route("password.update"), {
        preserveScroll: true,
        onSuccess: () => {
            console.log("TODO: Add success toast notification.");
            form.reset();
        },
        onError: (errors) => {
            Object.keys(errors).forEach((key) => {
                const error = errors[key];
                console.error("TODO: Add error toast notification.", error);
            });

            if (form.errors.password) {
                form.reset("password", "password_confirmation");
                passwordInput.value?.focus();
            }
            
            if (form.errors.current_password) {
                form.reset("current_password");
                currentPasswordInput.value?.focus();
            }
        },
    });
};
</script>

<template>
    <Card>
        <Form @submit.prevent="submitForm">
            <ContentBody class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <FormGroup class="col-span-full">
                    <FormLabel for="current_password">Current Password</FormLabel>
                    <FormInput type="password" id="current_password" name="current_password" placeholder="Leave blank unless to change the password." v-model="form.current_password" :disabled="form.processing" :required="true" />
                    <FormError v-if="form.errors.current_password">{{ form.errors.current_password }}</FormError>
                </FormGroup>

                <FormGroup class="col-span-full lg:col-span-1">
                    <FormLabel for="password">New Password</FormLabel>
                    <FormInput type="password" id="password" name="password" placeholder="Leave blank unless to change the password." v-model="form.password" :disabled="form.processing" :required="true" />
                    <FormError v-if="form.errors.password">{{ form.errors.password }}</FormError>
                </FormGroup>

                <FormGroup class="col-span-full lg:col-span-1">
                    <FormLabel for="password_confirmation">Confirm New Password</FormLabel>
                    <FormInput type="password" id="password_confirmation" name="password_confirmation" placeholder="Leave blank unless to change the password." v-model="form.password_confirmation" :disabled="form.processing" :required="true" />
                    <FormError v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</FormError>
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
