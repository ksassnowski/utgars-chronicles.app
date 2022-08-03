<template>
    <Head title="Profile – Utgar's Chronicles" />

    <div class="lg:max-w-5xl px-4 mx-auto">
        <PageHeader class="text-3xl font-bold text-gray-800 tracking-tight">
            Profile
        </PageHeader>

        <Panel class="mt-4">
            <form @submit.prevent="submit" class="flex">
                <div class="w-1/3 pr-4">
                    <p class="text-lg text-gray-800">Change Password</p>
                </div>

                <div class="w-2/3">
                    <div
                        class="mb-4"
                        :class="{ error: form.errors.password }"
                    >
                        <label for="password" class="label"
                            >New Password</label
                        >
                        <TextInput
                            name="password"
                            type="password"
                            class="input"
                            id="password"
                            v-model="form.password"
                        />
                        <small
                            v-if="form.errors.password"
                            class="text-xs text-red-500 mt-1"
                            >{{ form.errors.password[0] }}</small
                        >
                    </div>

                    <div class="mb-4">
                        <label for="passwordConfirmation" class="label"
                            >Confirm Password</label
                        >
                        <TextInput
                            name="password_confirmation"
                            type="password"
                            class="input"
                            id="passwordConfirmation"
                            v-model="form.password_confirmation"
                        />
                    </div>

                    <div class="flex justify-end">
                        <LoadingButton
                            class="px-8 py-2 bg-indigo-700 rounded text-white"
                            :loading="form.processing"
                        >
                            Change Password
                        </LoadingButton>
                    </div>
                </div>
            </form>
        </Panel>
    </div>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Layout.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { useForm } from "@inertiajs/inertia-vue3";
import { Head } from "@inertiajs/inertia-vue3";

import LoadingButton from "@/components/LoadingButton.vue";
import Panel from "@/components/UI/Panel.vue";
import TextInput from "@/components/UI/TextInput.vue";
import PageHeader from "@/components/UI/PageHeader.vue";

const form = useForm({
    password: null,
    password_confirmation: null,
});

const submit = () => {
    form.post(route("password.change"), {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script>
