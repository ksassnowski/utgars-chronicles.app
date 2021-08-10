<template>
    <div :class="{ 'px-6': !editing }" class="pt-6">
        <div
            class="relative pt-8 px-4 pb-6 relative shadow-sm rounded-lg border bg-white border-gray-200 text-sm w-full min-h-32 group"
            :class="{ 'bg-white text-gray-700': scene.type === 'light', 'bg-gray-700 text-white': scene.type === 'dark' }"
        >
            <div v-if="!editing">
                <div class="invisible group-hover:visible absolute left-0 top-0 w-full pl-3 pr-2 pt-2 flex justify-between z-20">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="handle w-4 h-4 fill-current text-gray-400 cursor-move"
                        style="margin-top: 2px"
                        viewBox="0 0 20 20"
                    ><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>

                    <div class="flex items-center">
                        <button @click="open = !open" class="mr-2" :title="open ? 'Collapse Scene' : 'Expand Scene'" style="margin-top: -2px;">
                            <Icon class="w-4 h-4 fill-current text-gray-600" :name="open ? 'view-hide' : 'view-show'" />
                        </button>

                        <SettingsPanel
                            v-if="!editing"
                            @delete="remove"
                            @edit="startEditing"
                        />
                    </div>
                </div>

                <p class="text-sm whitespace-pre-wrap" :class="{ 'pb-2': open }">{{ scene.question }}</p>

                <template v-if="open">
                    <hr>

                    <p v-if="scene.scene" class="text-sm py-2 whitespace-pre-wrap">{{ scene.scene }}</p>
                    <p v-else class="text-sm py-2 text-gray-600 italic whitespace-normal">This scene has no description.</p>

                    <hr>

                    <p v-if="scene.answer" class="text-sm pt-2 whitespace-pre-wrap">{{ scene.answer }}</p>
                    <p v-else class="text-sm pt-2 text-gray-600 italic">This scene has not been answered yet.</p>
                </template>

                <p
                    class="absolute top-0 text-sm font-bold leading-loose uppercase px-1"
                    :class="{ 'text-white': scene.tone === 'dark', 'text-gray-700': scene.type === 'light' }"
                    style="top: -15px; right: 20px;"
                >
                    Scene
                </p>
            </div>

            <form v-else @submit.prevent="submit">
                <div class="mb-4">
                    <label for="question" class="label">Question</label>
                    <textarea id="question" rows="3" class="input" v-model="form.question" required></textarea>
                    <small v-if="form.errors.question" class="text-red-600 text-xs mt-1">{{ form.errors.question[0] }}</small>
                </div>

                <div class="mb-4">
                    <label for="scene" class="label">Scene</label>
                    <textarea id="scene" rows="3" class="input" v-model="form.scene"></textarea>
                    <small v-if="form.errors.scene" class="text-red-600 text-xs mt-1">{{ form.errors.scene[0] }}</small>
                </div>

                <div class="mb-4">
                    <label for="answer" class="label">Answer</label>
                    <textarea id="answer" rows="3" class="input" v-model="form.answer"></textarea>
                    <small v-if="form.errors.answer" class="text-red-600 text-xs mt-1">{{ form.errors.answer[0] }}</small>
                </div>

                <div class="mb-4">
                    <p class="label">Tone</p>

                    <div class="flex justify-between items-center">
                        <div>
                            <input type="radio" id="dark" value="dark" v-model="form.type">
                            <label for="dark">Dark</label>
                        </div>

                        <div>
                            <input type="radio" id="light" value="light" v-model="form.type">
                            <label for="light">Light</label>
                        </div>
                    </div>

                    <small class="text-red-600 text-xs mt-1" v-if="form.errors.type">{{ form.errors.type[0] }}</small>
                </div>

                <LoadingButton :loading="form.processing">Save</LoadingButton>

                <button
                    type="button"
                    class="w-full text-gray-700 text-sm mt-2"
                    @click="stopEditing"
                >
                    Cancel
                </button>
            </form>
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import SettingsPanel from "./SettingsPanel.vue";
import Icon from "./Icon.vue";
import LoadingButton from "./LoadingButton.vue";

export default defineComponent({
    name: 'SceneCard',

    components: {
        LoadingButton,
        Icon,
        SettingsPanel,
    },

    props: ['scene'],

    inject: ['history'],

    data() {
        return {
            open: true,
        };
    },

    methods: {
        remove() {
            const confirmed = confirm('Are you sure you want to delete this scene?');

            if (!confirmed) {
                return;
            }

            this.$inertia.delete(
                this.$route("scenes.delete", [this.history, this.scene]),
                { only: ["history"] },
            );
        },

        submit() {
            if (
                this.form.question === this.scene.question
                && this.form.scene === this.scene.scene
                && this.form.answer === this.scene.answer
                && this.form.type === this.scene.type
            ) {
                return this.stopEditing();
            }

            this.form.put(this.$route('scenes.update', [this.history, this.scene]), {
                only: ["history"],
                onSuccess: this.stopEditing,
            });
        }
    },

    setup(props) {
        const form = useForm({
            question: props.scene.question,
            scene: props.scene.scene,
            answer: props.scene.answer,
            type: props.scene.type,
        });
        const resetForm = () => {
            form.question = props.scene.question;
            form.scene = props.scene.scene;
            form.answer = props.scene.answer;
            form.type = props.scene.type;
        };
        const editing = ref(false);
        const stopEditing = () => {
            editing.value = false;
            resetForm();
        };
        const startEditing = () => {
            resetForm();
            editing.value = true;
        };

        return {
            form,
            startEditing,
            stopEditing,
            editing,
        };
    },
});
</script>
