<template>
    <Head title="Reset password" />

    <div class="container mx-auto px-4 h-full flex flex-col space-y-4 items-center justify-center">
        <Link href="/" class="text-2xl font-bold tracking-tight text-gray-700">Utgar's Chronicles</Link>

        <Panel class="w-full md:max-w-md shadow-indigo-100 ring-indigo-50">
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
import LoadingButton from "@/components/LoadingButton.vue";

const form = useForm({ email: '' })
const submit = () => form.post(route('password.email'));
</script>
