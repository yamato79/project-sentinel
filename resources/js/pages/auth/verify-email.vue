<script setup lang="ts">
import { computed } from "vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/components/layouts/auth/index.vue";
import Button from "@/components/button.vue";
import FormError from "@/components/form/form-error.vue";
import FormGroup from "@/components/form/form-group.vue";
import FormInput from "@/components/form/form-input.vue";
import FormLabel from "@/components/form/form-label.vue";
import Form from "@/components/form/form.vue";
import Heading from "@/components/heading.vue";
import Link from "@/components/link.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGroup from "@/components/section-group.vue";
import Section from "@/components/section.vue";
import Spinner from "@/components/spinner.vue";

defineOptions({ 
    layout: AuthLayout
});

const props = defineProps<{
    status?: string;
}>();

const form = useForm({
    email: '',
    password: '',
});

const verificationLinkSent = computed(() => props.status === 'verification-link-sent');

const submitForm = () => {
    form.post(route('verification.send'));
};
</script>

<template>
    <Head title="Email Verification" />

    <Section>
        <SectionGroup>
            <Heading :size="2">Email Verification</Heading>
            <Paragraph color="muted" size="sm">
                Thanks for signing up! Before getting started, could you verify your email address by clicking on the link
                we just emailed to you? If you didn't receive the email, we will gladly send you another.
            </Paragraph>
        </SectionGroup>

        <Paragraph color="success" size="sm" class="font-medium" v-if="verificationLinkSent">
            A new verification link has been sent to the email address you provided during registration.
        </Paragraph>

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

            <FormGroup class="pt-6">
                <Button type="submit" :disabled="form.processing">
                    <template v-if="form.processing">
                        <Spinner color="white" size="sm"></Spinner>
                    </template>

                    Resend Verification Email
                </Button>
            </FormGroup>

            <FormGroup class="text-center">
                <Button :href="route('logout')" method="post" as="button" color="muted" :disabled="form.processing">
                    Logout
                </Button>
            </FormGroup>
        </Form>
    </Section>
</template>
