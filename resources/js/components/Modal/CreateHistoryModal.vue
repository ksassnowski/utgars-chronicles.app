<template>
    <Modal title="Create History" @close="$emit('close')">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot />
            </div>
        </template>

        <form @submit.prevent="submit">
            <div class="mb-2">
                <label for="name" class="label">Name</label>
                <input
                    type="text"
                    class="input"
                    id="name"
                    v-model="form.name"
                    :ref="input"
                    required
                />
                <small class="text-xs text-gray-600"
                    >This is the seed of your history. For now you can simply
                    put in a placeholder and change it in-game.</small
                >
                <small
                    v-if="$page.props.errors.name"
                    class="text-red-600 text-xs"
                    >{{ $page.props.errors.name[0] }}</small
                >
            </div>

            <div class="mb-4">
                <div class="space-x-1">
                    <input type="checkbox" id="public" v-model="form.public" />
                    <label
                        for="public"
                        class="font-semibold tracking-wide text-xs text-gray-700"
                    >Allow guests to join?</label
                    >
                </div>

                <div class="text-xs text-gray-600">
                    This will allow you to invite players to your game that
                    don't have a user account on Utgar's Chronicles.
                </div>
            </div>

            <LoadingButton :loading="form.processing">
                Create History
            </LoadingButton>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";

import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";
import {useForm} from "@inertiajs/inertia-vue3";

export default defineComponent({
    name: "CreateHistoryModal",

    components: {
        LoadingButton,
        Modal
    },

    methods: {
        submit() {
            this.form.post(this.$route('history.store'), {
                onSuccess: () => this.$emit('close'),
            });
        }
    },

    setup() {
        const input = ref(null);

        const form = useForm({
            name: '',
            public: false,
        });

        return { form, input };
    },
});
</script>
