<template>
    <Modal title="Create History" @close="$emit('close')">
        <form @submit.prevent="submit">
            <div class="mb-2">
                <label for="name" class="label">Name</label>
                <input
                    type="text"
                    class="input"
                    id="name"
                    v-model="form.name"
                    ref="input"
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
                <input type="checkbox" id="public" v-model="form.public" />
                <label
                    for="public"
                    class="font-semibold tracking-wide text-xs text-gray-700"
                    >Allow guests to join?</label
                >
                <div class="text-xs text-gray-600">
                    This will allow you to invite players to your game that
                    don't have a user account on Utgar's Chronicles.
                </div>
            </div>

            <LoadingButton :loading="loading">
                Create History
            </LoadingButton>
        </form>
    </Modal>
</template>

<script>
import Modal from "../Modal";
import LoadingButton from "../LoadingButton";

export default {
    name: "CreateHistoryModal",

    components: {
        LoadingButton,
        Modal
    },

    data() {
        return {
            loading: false,
            form: {
                name: null,
                public: false
            }
        };
    },

    methods: {
        submit() {
            this.loading = true;

            this.$inertia
                .post(this.$route("history.store"), this.form)
                .then(() => this.$emit("close"))
                .finally(() => (this.loading = false));
        }
    },

    mounted() {
        this.$nextTick(() => this.$refs.input.focus());
    }
};
</script>
