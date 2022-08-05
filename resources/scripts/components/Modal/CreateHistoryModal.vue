<template>
    <Modal title="Create History" @close="$emit('close')">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot />
            </div>
        </template>

        <form @submit.prevent="submit" class="space-y-6">
            <div class="mb-2">
                <label for="name" class="label">Name</label>

                <TextInput
                    type="text"
                    name="name"
                    v-model="form.name"
                    v-focus
                    required
                />

                <div class="text-xs text-gray-600 mt-2"
                    >This is the seed of your history. For now you can simply
                    put in a placeholder and change it in-game.</div
                >
                <small
                    v-if="$page.props.errors.name"
                    class="text-red-600 text-xs"
                    >{{ $page.props.errors.name[0] }}</small
                >
            </div>

            <div>
                <div class="space-x-2">
                    <input type="checkbox" id="public" v-model="form.public" />
                    <label
                        for="public"
                        class="font-semibold tracking-wide text-xs text-gray-700"
                    >Allow guests to join?</label
                    >
                </div>

                <div class="text-xs text-gray-600 mt-1">
                    This will allow you to invite players to your game that
                    don't have a user account on Utgar's Chronicles.
                </div>
            </div>

            <GameModeSelection v-model="form.game_mode" />

            <footer class="flex justify-end">
                <LoadingButton :loading="form.processing">
                    Create History
                </LoadingButton>
            </footer>
        </form>
    </Modal>
</template>

<script lang="ts" setup>
import { useForm } from "@inertiajs/vue3";

import { MicroscopeGameMode } from "@/types";
import Modal from "@/components/Modal.vue";
import LoadingButton from "@/components//LoadingButton.vue";
import TextInput from "@/components/UI/TextInput.vue";
import GameModeSelection from "@/components/GameModeSelection.vue";

const emit = defineEmits(['close']);

const submit = () =>
    form.post(route('history.store'), {
        onSuccess: () => emit('close'),
    });

const form = useForm({
    name: '',
    public: false,
    game_mode: MicroscopeGameMode.BaseGame,
});
</script>
