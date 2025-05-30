<template>
    <Head title="Create an account" />

    <AuthTitle>Sign up</AuthTitle>

    <form @submit.prevent="submit" method="POST">
        <div class="mb-4">
            <label class="label" for="name">Name</label>
            <TextInput
                v-model="form.name"
                type="text"
                name="name"
                v-focus
                autofocus
                required
            />
            <small class="text-xs text-gray-600 mt-2">Your name will show up to other players when playing a game. You can use a nickname if you want.</small>

            <small v-if="form.errors.name" class="mt-1 text-xs text-red-400 block">
                {{ form.errors.name[0] }}
            </small>
        </div>

        <div class="mb-4">
            <label class="label" for="email">Email</label>
            <TextInput
                v-model="form.email"
                type="email"
                name="email"
                required
            />

            <small class="text-xs text-gray-600 mt-2">
                Your email is used to create the account and, if necessary, send you a password reset link. That's it.
            </small>

            <small v-if="form.errors.email" class="mt-1 text-xs text-red-400 block">{{ form.errors.email[0] }}
            </small>
        </div>

        <div class="mb-4">
            <label class="label" for="password">Password</label>
            <TextInput
                v-model="form.password"
                type="password"
                name="password"
                required
            />

            <small v-if="form.errors.password" class="mt-1 text-xs text-red-400">
                {{ form.errors.password[0] }}
            </small>
        </div>

        <div>
            <label class="label" for="password_confirmation">Confirm Password</label>
            <TextInput
                v-model="form.password_confirmation"
                type="password"
                name="password_confirmation"
            />
        </div>

        <LoadingButton :loading="form.processing" class="block w-full mt-8">
            Register
        </LoadingButton>

        <Link :href="route('login')" class="mt-2 text-sm text-indigo-700 text-center block">
            Already have an account? Log in instead
        </Link>
    </form>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Auth.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { Link, Head, useForm } from "@inertiajs/vue3";

import TextInput from "@/components/UI/TextInput.vue";
import LoadingButton from "@/components/LoadingButton.vue"
import AuthTitle from "@/components/UI/AuthTitle.vue";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('register'));
</script>
