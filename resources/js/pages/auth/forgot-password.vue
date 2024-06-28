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

defineOptions({ 
    layout: AuthLayout
});

const form = useForm({
    email: "",
});

const submitForm = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <Head title="Forgot Password" />

    <Section>
        <SectionGroup>
            <Heading :size="2">Forgot Password</Heading>
            <Paragraph color="muted" size="sm">
                Forgot your password? No problem. Just let us know your email address and we will email you instructions.
            </Paragraph>
        </SectionGroup>

        <Form class="grid grid-cols-1 gap-6" @submit.prevent="submitForm">
            <FormGroup>
                <FormLabel for="email">Email Address</FormLabel>
                <FormInput type="email" id="email" name="email" v-model="form.email" :disabled="form.processing" :required="true" autofocus />
                <FormError v-if="form.errors.email">{{ form.errors.email }}</FormError>
            </FormGroup>

            <FormGroup class="pt-6">
                <Button type="submit" :is-loading="form.processing" :disabled="form.processing">
                    Send Instructions
                </Button>
            </FormGroup>
        </Form>
    </Section>
</template>
