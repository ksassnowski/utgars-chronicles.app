<template>
    <div>
        <Modal v-if="showModal" @close="close" :title="form.id === null ? 'Add Legacy' : 'Edit Legacy'">
            <form @submit.prevent="submit">
                <div :class="{ error: errors.name }" class="mb-4">
                    <label for="name" class="label">Name</label>
                    <input type="text" class="input" id="name" ref="input" v-model="form.name" required>
                    <small v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name[0] }}</small>
                </div>

                <button
                    type="submit"
                    class="text-white w-full rounded py-2 px-4"
                    :class="{ 'bg-indigo-400 cursor-not-allowed': loading, 'bg-indigo-700 ': !loading }"
                    :disabled="loading"
                >
                    {{ loading ? 'Hang on...' : 'Save' }}
                </button>
            </form>

            <form v-if="form.id !== null" @submit.prevent="deleteLegacy" class="text-center mt-1">
                <button class="text-red-700 text-sm">Delete Legacy</button>
            </form>
        </Modal>

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-gray-700">Legacies</h3>

            <button class="text-indigo-700 text-sm" @click="create">Add Legacy</button>
        </div>

        <div v-for="legacy in internalLegacies" :key="legacy.id" class="mb-2 text-sm px-2 py-2 bg-white cursor-pointer shadow rounded" @click="edit(legacy)">
            {{ legacy.name }}
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import Modal from './Modal';

export default {
    name: 'LegacyTracker',

    props: ['legacies', 'channel', 'history-id'],

    components: {
        Modal,
    },

    data() {
        return {
            loading: false,
            showModal: false,
            internalLegacies: this.legacies,
            errors: {},
            form: {
                id: null,
                name: null,
            },
        };
    },

    methods: {
        submit() {
            this.loading = true;

            const promise = this.form.id === null
                ? axios.post(this.$route('history.legacies.store', this.historyId), this.form)
                : axios.put(this.$route('legacies.update', this.form.id), this.form);

            promise.then(() => {
                this.loading = false;
                this.close();
            })
            .catch((err) => {
                this.loading = false;
                this.errors = err.response.data.errors;
            });
        },

        reset() {
            this.form.id = null;
            this.form.name = null;
        },

        create() {
            this.reset();
            this.showModal = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        edit(legacy) {
            this.form = Object.assign({}, legacy);
            this.showModal = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        deleteLegacy() {
            const confirmed = confirm('Do you really want to delete this legacy?');

            if (!confirmed) {
                return;
            }

            axios.delete(this.$route('legacies.delete', this.form.id))
                .then(() => this.showModal = false);
        },

        close() {
            this.reset();
            this.errors = {};
            this.showModal = false;
        }
    },

    created() {
        Echo.join(this.channel)
            .listen('LegacyCreated', legacy => this.internalLegacies.push(legacy))
            .listen('LegacyUpdated', (legacy) => {
                const matchingLegacy = this.internalLegacies.find(l => l.id === legacy.id);

                if (!matchingLegacy) {
                    return;
                }

                Object.assign(matchingLegacy, legacy);
            })
            .listen('LegacyDeleted', ({ id }) => {
                this.internalLegacies = this.internalLegacies.filter(l => l.id !== id);
            });
    },
};
</script>
