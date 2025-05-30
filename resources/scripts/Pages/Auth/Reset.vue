<template>
    <Head title="Reset password" />

    <AuthTitle>Reset password</AuthTitle>

    <form @submit.prevent="submit" method="POST">
        <div class="mb-4">
            <label class="label" for="email">Email</label>

            <TextInput
                v-model="form.email"
                type="email"
                name="email"
                required
                disabled
            />

            <small v-if="form.errors.email" class="mt-1 text-xs text-red-400">
                {{ form.errors.email[0] }}
            </small>
        </div>

        <div class="mb-4">
            <label class="label" for="password">New Password</label>

            <TextInput
                v-model="form.password"
                v-focus
                type="password"
                name="password"
                autofocus
                required
            />

            <small v-if="form.errors.password" class="mt-1 text-xs text-red-400">{{ form.errors.password[0] }}</small>
        </div>

        <div class="mb-4">
            <label class="label" for="password_confirmation">Confirm Password</label>

            <TextInput
                v-model="form.password_confirmation"
                type="password"
                name="password_confirmation"
                required
            />
        </div>

        <footer class="flex justify-end">
            <LoadingButton :loading="form.processing">Change Password</LoadingButton>
        </footer>
    </form>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Auth.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { useForm, Head } from "@inertiajs/vue3";

import TextInput from "@/components/UI/TextInput.vue";
import LoadingButton from "@/components/LoadingButton.vue";
import AuthTitle from "@/components/UI/AuthTitle.vue";

const props = defineProps<{ email: string, token: string }>();

const form = useForm({
    email: props.email,
    token: props.token,
    password: '',
    password_confirmation: '',
});

const submit = () => form.post(route('password.update'));
</script>
