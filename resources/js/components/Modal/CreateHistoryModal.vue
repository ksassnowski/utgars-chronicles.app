<template>
    <Modal title="Create History" @close="$emit('close')">
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="label">Name</label>
                <input type="text" class="input" id="name" v-model="form.name" ref="input" required>
                <small class="text-xs text-gray-600">This is the seed of your history. For now you can simply put in a placeholder and change it in-game.</small>
                <small v-if="$page.errors.name" class="text-red-600 text-xs">{{ $page.errors.name[0] }}</small>
            </div>

            <LoadingButton :loading="loading">
                Create History
            </LoadingButton>
        </form>
    </Modal>
</template>

<script>
import Modal from '../Modal';
import LoadingButton from '../LoadingButton';

export default {
    name: 'CreateHistoryModal',

    components: {
        LoadingButton,
        Modal,
    },

    data() {
        return {
            loading: false,
            form: {
                name: null,
            },
        };
    },

    methods: {
        submit() {
            this.loading = true;

            this.$inertia.post(this.$route('history.store'), this.form)
                .then(() => this.$emit('close'))
                .finally(() => this.loading = false);
        }
    },

    mounted() {
        this.$nextTick(() => this.$refs.input.focus());
    }
};
</script>
