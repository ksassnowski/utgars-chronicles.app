<template>
    <button @click="showModal = true" class="text-indigo-100 py-6">
        <Modal v-if="showModal" title="Submit Feedback" @close="showModal = false">
            <form @submit.prevent="submit">
                <p class="text-gray-700 text-sm mb-4">
                    Found a bug or have feedback? Please write your message below and I will get notified. Thanks for helping improve the app!
                </p>

                <div class="mb-4">
                    <label for="message" class="label">Message</label>
                    <textarea id="message" rows="6" class="input" v-model="form.message" required></textarea>
                </div>

                <LoadingButton :loading="loading">
                    Submit your feedback
                </LoadingButton>
            </form>
        </Modal>

        Send Feedback
    </button>
</template>

<script>
import axios from "axios";

import Modal from "../Modal";
import LoadingButton from "../LoadingButton";

export default {
    name: 'FeedbackModal',

    components: {
        LoadingButton,
        Modal
    },

    data() {
        return {
            showModal: false,
            loading: false,
            form: {
                message: null,
            },
        };
    },

    methods: {
        async submit() {
            this.loading = true;

            await this.$inertia.post(this.$route('feedback.submit'), this.form);

            this.loading = false;
            this.showModal = false;
            this.form.message = null;
        },
    },
};
</script>
