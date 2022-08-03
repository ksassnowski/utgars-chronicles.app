<template>
    <Head title="Create an account" />

    <div class="container mx-auto px-4 h-full flex flex-col space-y-4 items-center justify-center">
        <Link href="/" class="text-2xl font-bold tracking-tight text-gray-700">Utgar's Chronicles</Link>

        <Panel class="w-full md:max-w-xl shadow-indigo-100 ring-indigo-50">
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
                        Your email is used to create the account and, if necessary, send you a password reset link. That's it. It will not be shared with anyone or used to send you newsletters or other marketing things.
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

                <div class="mb-4">
                    <label class="label" for="password_confirmation">Confirm Password</label>
                    <TextInput
                        v-model="form.password_confirmation"
                        type="password"
                        name="password_confirmation"
                    />
                </div>

                <LoadingButton :loading="form.processing">Register</LoadingButton>

                <Link :href="route('login')" class="mt-2 text-sm text-indigo-700 text-center block">
                    Already have an account? Log in instead
                </Link>
            </form>
        </Panel>
    </div>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Layout.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { Link, Head, useForm } from "@inertiajs/inertia-vue3";

import Panel from "@/components/UI/Panel.vue";
import TextInput from "@/components/UI/TextInput.vue";
import LoadingButton from "@/components/LoadingButton.vue"

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => this.form.post(route('register'));
</script>
