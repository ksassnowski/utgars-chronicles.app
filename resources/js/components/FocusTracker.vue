<template>
    <div>
        <Modal v-if="showModal" :title="form.id === null ? 'Define Focus' : 'Edit Focus'" @close="close">
            <form @submit.prevent="submit">
                <div :class="{ error: errors.name }" class="mb-4">
                    <label for="name" class="label">Title</label>
                    <input type="text" class="input" id="name" v-model="form.name" ref="input" required>
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

            <form v-if="form.id !== null" @submit.prevent="deleteFocus" class="text-center mt-1">
                <button class="text-red-700 text-sm">Delete Focus</button>
            </form>
        </Modal>

        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-sm text-gray-700">Focus</h3>

            <button class="text-indigo-700 text-sm" @click="create">Define Focus</button>
        </div>

        <div class="flex flex-col">
            <div
                v-if="currentFocus"
                class="bg-indigo-700 rounded h-40 px-12 flex items-center justify-center text-white text-lg font-bold cursor-pointer text-center shadow-lg"
                @click="edit(currentFocus)"
            >
                {{ currentFocus.name }}
            </div>

            <div
                v-else
                class="flex items-center justify-center text-center h-40 px-12 border-4 border-dotted border-gray-500 text-lg font-bold text-gray-500"
            >
                No focus defined
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import Modal from './Modal';

export default {
    name: 'FocusTracker',

    props: ['channel', 'foci', 'historyId'],

    components: {
        Modal,
    },

    computed: {
        currentFocus() {
            if (this.internalFoci.length === 0) {
                return null;
            }

            return this.internalFoci.slice(-1)[0];
        },
    },

    data() {
        return {
            showModal: false,
            loading: false,
            errors: {},
            form: {
                id: null,
                name: null,
            },
            internalFoci: this.foci,
        };
    },

    methods: {
        create() {
            this.reset();
            this.showModal = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        edit(focus) {
            this.form = Object.assign({}, focus);
            this.showModal = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        submit() {
            this.loading = true;

            const promise = this.form.id === null
                ? axios.post(this.$route('history.focus.define', this.historyId), this.form)
                : axios.put(this.$route('focus.update', [this.historyId, this.form.id]), this.form);

            promise.then(() => {
                this.loading = false;
                this.close();
            })
            .catch((err) => {
                this.errors = err.response.data.errors;
                this.loading = false;
            });
        },

        deleteFocus() {
            const confirmed = confirm('Do you really want to delete this focus?');

            if (!confirmed) {
                return;
            }

            axios.delete(this.$route('focus.delete', [this.historyId, this.form.id]))
                .then(this.close);
        },

        reset() {
            this.form.id = null;
            this.form.name = null;
        },

        close() {
            this.reset();
            this.errors = {};
            this.showModal = false;
        }
    },

    created() {
        Echo.join(this.channel)
            .listen('FocusDefined', (e) => {
                this.internalFoci.push(e);
            })
            .listen('FocusUpdated', ({ focus }) => {
                const matchingFocus = this.internalFoci.find(f => f.id === focus.id);

                if (!matchingFocus) {
                    return;
                }

                Object.assign(matchingFocus, focus);
            })
            .listen('FocusDeleted', ({ id }) => {
                this.internalFoci = this.internalFoci.filter(f => f.id !== id);
            });
    },
};
</script>
