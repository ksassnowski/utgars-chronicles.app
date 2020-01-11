<template>
    <Modal @close="$emit('close')" title="Create Scene">
        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="question" class="label">Question</label>
                <textarea
                    id="question"
                    rows="4"
                    class="input"
                    v-model="form.question"
                    ref="question"
                ></textarea>
                <small class="text-red-600 text-xs mt-1" v-if="errors.question">{{ errors.question[0] }}</small>
            </div>

            <div class="mb-4">
                <label for="scene" class="label">Scene</label>
                <textarea id="scene" rows="4" class="input" v-model="form.scene"></textarea>
                <small class="text-red-600 text-xs mt-1" v-if="errors.scene">{{ errors.scene[0] }}</small>
            </div>

            <div class="mb-4">
                <label for="answer" class="label">Answer</label>
                <textarea id="answer" rows="4" class="input" v-model="form.answer"></textarea>
                <small class="text-red-600 text-xs mt-1" v-if="errors.answer">{{ errors.answer[0] }}</small>
            </div>

            <div class="mb-4">
                <p class="label">Tone</p>

                <div class="flex justify-between items-center">
                    <div>
                        <input type="radio" id="light" value="light" v-model="form.type">
                        <label for="light">Light</label>
                    </div>

                    <div>
                        <input type="radio" id="dark" value="dark" v-model="form.type">
                        <label for="dark">Dark</label>
                    </div>
                </div>

                <small class="text-red-600 text-xs mt-1" v-if="errors.type">{{ errors.type[0] }}</small>
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
    </Modal>
</template>

<script>
import axios from 'axios';

import Modal from '../Modal';

export default {
    name: 'SceneModal',

    props: ['event'],

    components: {
        Modal,
    },

    computed: {
        route() {
            return this.$route('events.scenes.store', this.event);
        },
    },

    data() {
        return {
            loading: false,
            form: {
                question: null,
                type: null,
                scene: null,
                answer: null,
            },
            errors: {},
        };
    },

    methods: {
        submit() {
            this.loading = true;

            axios.post(this.route, this.form)
                .then(() => {
                    this.loading = false;
                    this.$emit('close');
                })
                .catch((err) => {
                    this.loading = false;
                    this.errors = err.response.data.errors;
                });
        },
    },

    mounted() {
        this.$nextTick(() => this.$refs.question.focus());
    }
};
</script>
