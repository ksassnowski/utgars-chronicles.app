<template>
    <div>
        <Modal v-if="showModal" @close="close" :title="form.id === null ? 'Add Item to Palette' : 'Edit Item'">
            <form @submit.prevent="submit">
                <div :class="{ error: errors.name }" class="mb-4">
                    <label for="name" class="label">Description</label>
                    <input type="text" class="input" id="name" ref="input" v-model="form.name" required>
                    <small v-if="errors.name" class="text-xs text-red-500 mt-1">{{ errors.name[0] }}</small>
                </div>

                <div class="mb-4">
                    <p class="label">Type</p>

                    <div class="flex justify-between items-center">
                        <div>
                            <input type="radio" id="yes" value="yes" v-model="form.type">
                            <label for="yes">Yes</label>
                        </div>

                        <div>
                            <input type="radio" id="no" value="no" v-model="form.type">
                            <label for="no">No</label>
                        </div>
                    </div>
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

            <form v-if="form.id !== null" @submit.prevent="deleteItem" class="text-center mt-1">
                <button class="text-red-700 text-sm">Delete Item</button>
            </form>
        </Modal>

        <div class="flex items-center justify-between mb-4">
            <h3 class="font-bold text-sm text-gray-700">Palette</h3>

            <button class="text-indigo-700 text-sm" @click="create">Add Item</button>
        </div>

        <div class="flex justify-between -mx-2">
            <div class="px-2">
                <p class="font-bold mb-2">Yes</p>

                <ul>
                    <li
                        v-for="item in yes"
                        :key="item.id"
                        class="text-gray-800 text-sm mb-1 cursor-pointer"
                        @click="edit(item)"
                    >
                        {{ item.name }}
                    </li>
                </ul>
            </div>

            <div class="px-2">
                <p class="font-bold mb-2">No</p>

                <ul>
                    <li
                        v-for="item in no"
                        :key="item.id"
                        class="text-gray-800 text-sm mb-1 cursor-pointer"
                        @click="edit(item)"
                    >
                        {{ item.name }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import Modal from "./Modal.vue";

export default {
    name: 'Palette',

    props: ['channel', 'palette', 'historyId'],

    components: {
        Modal,
    },

    computed: {
        yes() {
            return this.internalPalette.filter(i => i.type === 'yes');
        },

        no() {
            return this.internalPalette.filter(i => i.type === 'no');
        },
    },

    data() {
        return {
            internalPalette: this.palette,
            showModal: false,
            loading: false,
            errors: {},
            form: {
                id: null,
                name: null,
                type: 'yes',
            },
        };
    },

    methods: {
        submit() {
            this.loading = true;

            const promise = this.form.id === null
                ? axios.post(this.$route('history.palette.store', this.historyId), this.form)
                : axios.put(this.$route('palette.update', [this.historyId, this.form.id]), this.form);

            promise.then(() => {
                this.loading = false;
                this.close();
            })
            .catch((err) => {
                this.loading = false;
                this.errors = err.response.data.errors;
            });
        },

        deleteItem() {
            const confirmed = confirm('Do you really want to delete this item from the palette?');

            if (!confirmed) {
                return;
            }

            axios.delete(this.$route('palette.delete', [this.historyId, this.form.id]))
                .then(this.close);
        },

        create() {
            this.reset();
            this.showModal = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        edit(item) {
            this.form = Object.assign({}, item);
            this.showModal = true;
            this.$nextTick(() => this.$refs.input.focus());
        },

        reset() {
            this.form.id = null;
            this.form.name = null;
            this.form.type = 'yes';
        },

        close() {
            this.reset();
            this.errors = {};
            this.showModal = false;
        }
    },

    created() {
        Echo.join(this.channel)
            .listen('ItemAddedToPalette', item => this.internalPalette.push(item))
            .listen('PaletteItemUpdated', (item) => {
                const matchingItem = this.internalPalette.find(i => i.id === item.id);

                if (!matchingItem) {
                    return;
                }

                Object.assign(matchingItem, item);
            })
            .listen('PaletteItemDeleted', ({ id }) => {
                this.internalPalette = this.internalPalette.filter(item => item.id !== id);
            });
    },
};
</script>
