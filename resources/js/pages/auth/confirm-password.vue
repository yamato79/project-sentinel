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
    password: "",
});

const submitForm = () => {
    form.post(route("password.confirm"));
};
</script>

<template>
    <Head title="Confirm Password" />

    <Section>
        <SectionGroup>
            <Heading :size="2">Confirm Password</Heading>
            <Paragraph color="muted" size="sm">
                This is a secure area of the application. Please confirm your password before continuing.
            </Paragraph>
        </SectionGroup>

        <Form class="grid grid-cols-1 gap-6" @submit.prevent="submitForm">
            <FormGroup>
                <FormLabel for="password">Password</FormLabel>
                <FormInput type="password" id="password" name="password" v-model="form.password" :disabled="form.processing" :required="true" />
                <FormError v-if="form.errors.password">{{ form.errors.password }}</FormError>
            </FormGroup>

            <FormGroup class="pt-6">
                <Button type="submit" :is-loading="form.processing" :disabled="form.processing">
                    Confirm
                </Button>
            </FormGroup>
        </Form>
    </Section>
</template>
