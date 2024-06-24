<script setup lang="ts">
import { Head, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/components/layouts/auth/index.vue";
import Button from "@/components/button.vue";
import FormError from "@/components/form/form-error.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import FormLabel from "@/components/form/form-label.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGroup from "@/components/section-group.vue";
import Section from "@/components/section.vue";
import Spinner from "@/components/spinner.vue";

defineOptions({ 
    layout: AuthLayout
});

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submitForm = () => {
    form.post(route('password.store'));
};
</script>

<template>
    <Head title="Reset Password" />

    <Section>
        <SectionGroup>
            <Heading :size="2">Reset Password</Heading>
            <Paragraph color="muted" size="sm">
                Complete the form below to change your password and make your chosen password is secure.
            </Paragraph>
        </SectionGroup>

        <Form class="grid grid-cols-1 gap-6" @submit.prevent="submitForm">
            <FormGroup>
                <FormLabel for="email">Email Address</FormLabel>
                <FormInput type="email" id="email" name="email" v-model="form.email" :disabled="form.processing" :required="true" autofocus />
                <FormError v-if="form.errors.email">{{ form.errors.email }}</FormError>
            </FormGroup>

            <FormGroup>
                <FormLabel for="password">Password</FormLabel>
                <FormInput type="password" id="password" name="password" v-model="form.password" :disabled="form.processing" :required="true" />
                <FormError v-if="form.errors.password">{{ form.errors.password }}</FormError>
            </FormGroup>

            <FormGroup>
                <FormLabel for="password_confirmation">Confirm Password</FormLabel>
                <FormInput type="password" id="password_confirmation" name="password_confirmation" v-model="form.password_confirmation" :disabled="form.processing" :required="true" />
                <FormError v-if="form.errors.password_confirmation">{{ form.errors.password_confirmation }}</FormError>
            </FormGroup>

            <FormGroup class="pt-6">
                <Button type="submit" :disabled="form.processing">
                    <template v-if="form.processing">
                        <Spinner color="white" size="sm"></Spinner>
                    </template>

                    Reset Password
                </Button>
            </FormGroup>
        </Form>
    </Section>
</template>
