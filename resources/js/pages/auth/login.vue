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
import Link from "@/components/link.vue";
import Paragraph from "@/components/paragraph.vue";
import SectionGroup from "@/components/section-group.vue";
import Section from "@/components/section.vue";

defineOptions({ 
    layout: AuthLayout
});

const form = useForm({
    email: "",
    password: "",
});

const submitForm = () => {
    form.post(route("login"));
};
</script>

<template>
    <Head title="Login to your account" />

    <Section>
        <SectionGroup>
            <Heading :size="2">Login to your account</Heading>
            <Paragraph color="muted" size="sm">
                Not a member?
                <Link :href="route('register')" :disabled="form.processing"> Create your own account </Link>
                and start sleeping better at night.
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

            <FormGroup class="pt-6">
                <Button type="submit" :is-loading="form.processing" :disabled="form.processing">
                    Sign In
                </Button>
            </FormGroup>

            <FormGroup class="text-center">
                <Button :href="route('password.request')" color="muted" :disabled="form.processing">
                    Forgot password
                </Button>
            </FormGroup>
        </Form>
    </Section>
</template>
