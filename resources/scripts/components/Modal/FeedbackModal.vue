<template>
    <Modal title="Submit Feedback" ref="modal">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot />
            </div>
        </template>

        <form @submit.prevent="submit">
            <p class="text-gray-700 text-sm mb-4">
                Found a bug or have feedback? Please write your message below and I will get notified. Thanks for helping improve the app!
            </p>

            <div class="mb-4">
                <label for="message" class="label">Message</label>
                <textarea id="message" rows="6" class="input" v-model="form.message" required></textarea>
            </div>

            <LoadingButton :loading="form.processing">
                Submit your feedback
            </LoadingButton>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";

export default defineComponent({
    name: 'FeedbackModal',

    components: {
        LoadingButton,
        Modal,
    },

    methods: {
        async submit() {
            this.form.post(this.route('feedback.submit'), {
                onSuccess: () => {
                    this.form.message = null;
                    this.modal.toggle();
                },
            });
        },
    },

    setup() {
        const form = useForm({
            message: "",
        });
        const modal = ref(null);

        return { form, modal };
    }
});
</script>
