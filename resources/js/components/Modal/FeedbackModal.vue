<template>
    <button @click="showModal = true" class="text-indigo-100 py-4 sm:py-6 flex items-center">
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

        <Icon name="announcement" class="fill-current h-4 w-4 text-indigo-300 mr-2" />
        Send Feedback
    </button>
</template>

<script>
import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";
import Icon from "../Icon.vue";

export default {
    name: 'FeedbackModal',

    components: {
        Icon,
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
