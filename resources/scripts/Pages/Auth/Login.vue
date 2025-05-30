<template>
    <Head title="Login" />

    <AuthTitle>Login</AuthTitle>

    <form @submit.prevent="form.post(route('login'))">
        <div class="mb-4">
            <label class="label" for="email">Email</label>
            <TextInput class="input" type="email" id="email" name="email" v-model="form.email" required autofocus />
            <small v-if="form.errors.email" class="mt-1 text-xs text-red-400">{{ form.errors.email[0] }}</small>
        </div>

        <div class="mb-4">
            <label class="label" for="password">Password</label>
            <TextInput class="input" type="password" id="password" name="password" v-model="form.password" required />
            <small v-if="form.errors.password" class="mt-1 text-xs text-red-400">
                {{ form.errors.password[0] }}
            </small>

            <Link :href="route('password.request')" class="mt-1 text-sm text-gray-600 block">
                Forgot password?
            </Link>
        </div>

        <div class="space-x-1">
            <input v-model="form.remember" type="checkbox" name="remember" id="remember">
            <label for="remember" class="text-sm text-gray-700">Stay logged in</label>
        </div>

        <LoadingButton class="w-full mt-8" :loading="form.processing">Login</LoadingButton>

        <Link :href="route('register')" class="mt-2 text-sm text-indigo-700 text-center block">
            Or create a new account
        </Link>
    </form>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Auth.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

import TextInput from "@/components/UI/TextInput.vue";
import LoadingButton from "@/components/LoadingButton.vue";
import AuthTitle from "@/components/UI/AuthTitle.vue";

const form = useForm({
    email: '',
    password: '',
    remember: false,
});
</script>
