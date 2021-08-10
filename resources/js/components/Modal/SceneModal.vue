<template>
    <Modal title="Create Scene" ref="modal">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot />
            </div>
        </template>

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
                <small class="text-red-600 text-xs mt-1" v-if="form.errors.question">{{ form.errors.question[0] }}</small>
            </div>

            <div class="mb-4">
                <label for="scene" class="label">Scene</label>
                <textarea id="scene" rows="4" class="input" v-model="form.scene"></textarea>
                <small class="text-red-600 text-xs mt-1" v-if="form.errors.scene">{{ form.errors.scene[0] }}</small>
            </div>

            <div class="mb-4">
                <label for="answer" class="label">Answer</label>
                <textarea id="answer" rows="4" class="input" v-model="form.answer"></textarea>
                <small class="text-red-600 text-xs mt-1" v-if="form.errors.answer">{{ errors.answer[0] }}</small>
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

                <small class="text-red-600 text-xs mt-1" v-if="form.errors.type">{{ form.errors.type[0] }}</small>
            </div>

            <LoadingButton :loading="form.processing">Save</LoadingButton>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, ref, inject } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";

export default defineComponent({
    name: 'SceneModal',

    props: {
        event: Object,
    },

    components: {
        LoadingButton,
        Modal,
    },

    setup(props) {
        const modal = ref(null);
        const form = useForm({
            question: null,
            scene: null,
            answer: null,
            type: "light",
        });
        const history = inject("history");
        const submit = () => {
            form.post(route("events.scenes.store", [history, props.event]), {
                onSuccess: () => {
                    form.reset();
                    modal.value.toggle();
                }
            });
        };

        return { modal, form, submit };
    },
});
</script>
