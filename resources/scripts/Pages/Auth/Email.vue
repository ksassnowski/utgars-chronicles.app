<template>
    <Head title="Reset password" />

    <AuthTitle>Reset password</AuthTitle>

    <form @submit.prevent="submit" method="POST">
        <div class="mb-4">
            <label class="label" for="email">Email</label>
            <TextInput
                v-model="form.email"
                v-focus
                type="email"
                name="email"
                required
                autofocus
            />

            <small v-if="form.errors.email" class="mt-1 text-xs text-red-400">
                {{ form.errors.email[0] }}
            </small>
        </div>

        <footer class="flex justify-end">
            <LoadingButton :loading="form.processing">
                Send Password Reset Link
            </LoadingButton>
        </footer>
    </form>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Auth.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { Head, useForm } from "@inertiajs/vue3";

import TextInput from "@/components/UI/TextInput.vue";
import LoadingButton from "@/components/LoadingButton.vue";
import AuthTitle from "@/components/UI/AuthTitle.vue";

const form = useForm({ email: '' })
const submit = () => form.post(route('password.email'));
</script>
