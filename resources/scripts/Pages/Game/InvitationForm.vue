<template>
    <Head title="Join game" />

    <div class="container mx-auto px-4 h-full flex flex-col space-y-4 items-center justify-center">
        <Link href="/" class="text-2xl font-bold tracking-tight text-gray-700">Utgar's Chronicles</Link>

        <Panel class="max-w-md">
            <form @submit.prevent="submit">
                <p class="text-2xl mb-4 text-gray-800 text-center">
                    <strong>{{ inviteeName }}</strong> has invited you to join their
                    game of Microscope.
                </p>

                <div class="mb-4">
                    <label for="name" class="label">Name</label>
                    <input
                        type="text"
                        class="input"
                        id="name"
                        v-model="form.name"
                        autofocus
                        required
                    />
                    <small class="text-xs text-gray-600"
                        >This is the name that will show up to the other players
                        in-game.</small
                    >
                </div>

                <div class="flex justify-end">
                    <LoadingButton :loading="form.processing">
                        Join Game
                    </LoadingButton>
                </div>
            </form>
        </Panel>
    </div>
</template>

<script lang="ts" setup>
import { computed } from "vue";
import { Link, Head, useForm } from "@inertiajs/vue3";

import Panel from "@/components/UI/Panel.vue";
import LoadingButton from "../../components/LoadingButton.vue";

const props = defineProps<{ acceptUrl: string, inviteeName: string }>();

const form = useForm({
    name: null
});
const formValid = computed(() => form.name !== null);

const submit = () => {
    if (formValid.value) {
        form.post(props.acceptUrl, {
            onSuccess: () => {
                if (window.fathom) {
                    window.fathom.trackGoal("UKDSEW8G", 0);
                }
            }
        });
    }
};
</script>
