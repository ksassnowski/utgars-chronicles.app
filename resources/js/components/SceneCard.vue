<template>
    <div :class="{ 'px-6': !editing }">
        <div class="border-2 bg-white border-gray-600 mb-5">
            <div v-if="!editing" class="p-8 group relative">
                <div class="invisible group-hover:visible absolute right-0 top-0 pr-2 pt-2 flex justify-end">
                    <button @click="open = !open" class="mr-2" :title="open ? 'Collapse Scene' : 'Expand Scene'" style="margin-top: -2px;">
                        <Icon class="w-4 h-4 fill-current text-gray-600" :name="open ? 'view-hide' : 'view-show'" />
                    </button>

                    <svg xmlns="http://www.w3.org/2000/svg" class="handle w-4 h-4 fill-current text-gray-600 cursor-move" style="margin-top: 2px" viewBox="0 0 20 20"><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>

                    <SettingsPanel
                        v-if="!editing"
                        @delete="remove"
                        @edit="edit"
                    />
                </div>

                <p class="text-sm" :class="{ 'pb-2': open }">{{ scene.question }}</p>

                <template v-if="open">
                    <hr>

                    <p v-if="scene.scene" class="text-sm py-2">{{ scene.scene }}</p>
                    <p v-else class="text-sm py-2 text-gray-600 italic">This scene has no description.</p>

                    <hr>

                    <p v-if="scene.answer" class="text-sm pt-2">{{ scene.answer }}</p>
                    <p v-else class="text-sm pt-2 text-gray-600 italic">This scene has not been answered yet.</p>
                </template>

                <p
                    class="absolute top-0 text-sm bg-white text-gray-700 font-bold leading-loose uppercase px-1"
                    style="top: -15px; right: 20px;"
                >
                    Scene
                </p>

                <div
                    v-if="scene.type"
                    class="rounded-full border-2 border-gray-800 h-6 w-6 absolute"
                    style="top: -12px"
                    :class="{ 'bg-white': scene.type === 'light', 'bg-gray-800': scene.type === 'dark' }"
                ></div>
            </div>

            <form v-else @submit.prevent="submit" class="p-2">
                <div class="mb-4">
                    <label for="question" class="label">Question</label>
                    <textarea id="question" rows="3" class="input" v-model="form.question" required></textarea>
                    <small v-if="errors.question" class="text-red-600 text-xs mt-1">{{ errors.question[0] }}</small>
                </div>

                <div class="mb-4">
                    <label for="scene" class="label">Scene</label>
                    <textarea id="scene" rows="3" class="input" v-model="form.scene"></textarea>
                    <small v-if="errors.scene" class="text-red-600 text-xs mt-1">{{ errors.scene[0] }}</small>
                </div>

                <div class="mb-4">
                    <label for="answer" class="label">Answer</label>
                    <textarea id="answer" rows="3" class="input" v-model="form.answer"></textarea>
                    <small v-if="errors.answer" class="text-red-600 text-xs mt-1">{{ errors.answer[0] }}</small>
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

                <LoadingButton :loading="loading">
                    {{ loading ? 'Hang on...' : 'Save' }}
                </LoadingButton>

                <button type="button" class="w-full text-gray-700 text-sm mt-2" @click="cancel">Cancel</button>
            </form>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

import SettingsPanel from './SettingsPanel';
import Icon from './Icon';
import LoadingButton from './LoadingButton';

export default {
    name: 'SceneCard',

    components: {
        LoadingButton,
        Icon,
        SettingsPanel,
    },

    props: ['scene'],

    data() {
        return {
            editing: false,
            loading: false,
            open: true,
            errors: {},
            form: {
                question: this.scene.question,
                scene: this.scene.scene,
                answer: this.scene.answer,
                type: this.scene.type,
            }
        };
    },

    methods: {
        remove() {
            const confirmed = confirm('Are you sure you want to delete this scene?');

            if (!confirmed) {
                return;
            }

            axios.delete(this.$route('scenes.delete', this.scene))
                .then(() => this.editing = false);
        },

        edit() {
            this.editing = true;
            this.form.question = this.period.question;
            this.form.scene = this.period.scene;
            this.form.answer = this.period.answer;
            this.form.type = this.period.type;
        },

        cancel() {
            this.editing = false;
            this.form.question = this.period.question;
            this.form.scene = this.period.scene;
            this.form.answer = this.period.answer;
            this.form.type = this.period.type;
        },

        submit() {
            if (
                this.form.question === this.scene.question
                && this.form.scene === this.scene.scene
                && this.form.answer === this.scene.answer
                && this.form.type === this.scene.type
            ) {
                this.editing = false;
                return;
            }

            this.loading = true;

            axios.put(this.$route('scenes.update', this.scene), this.form)
                .then(() => {
                    this.editing = false;
                    this.loading = false;
                    this.errors = {};
                })
                .catch((err) => {
                    this.loading = false;
                    this.errors = err.response.data.errors;
                });
        }
    },
};
</script>
