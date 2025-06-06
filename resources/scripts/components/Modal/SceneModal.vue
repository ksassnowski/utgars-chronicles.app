<template>
    <Modal :title="title" ref="modal">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot />
            </div>
        </template>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="question" class="label">Question</label>
                <textarea
                    v-focus
                    id="question"
                    rows="4"
                    class="input"
                    v-model="form.question"
                ></textarea>
                <small
                    class="text-red-600 text-xs mt-1"
                    v-if="form.errors.question"
                    >{{ form.errors.question[0] }}</small
                >
            </div>

            <div class="mb-4">
                <label for="scene" class="label">Scene</label>
                <textarea
                    id="scene"
                    rows="4"
                    class="input"
                    v-model="form.scene"
                ></textarea>
                <small
                    class="text-red-600 text-xs mt-1"
                    v-if="form.errors.scene"
                    >{{ form.errors.scene[0] }}</small
                >
            </div>

            <div class="mb-4">
                <label for="answer" class="label">Answer</label>
                <textarea
                    id="answer"
                    rows="4"
                    class="input"
                    v-model="form.answer"
                ></textarea>
                <small
                    class="text-red-600 text-xs mt-1"
                    v-if="form.errors.answer"
                    >{{ errors.answer[0] }}</small
                >
            </div>

            <div class="mb-4">
                <p class="label">Tone</p>

                <div class="flex justify-between items-center">
                    <div class="space-x-1">
                        <input
                            type="radio"
                            id="light"
                            value="light"
                            v-model="form.type"
                        />
                        <label for="light">Light</label>
                    </div>

                    <div class="space-x-1">
                        <input
                            type="radio"
                            id="dark"
                            value="dark"
                            v-model="form.type"
                        />
                        <label for="dark">Dark</label>
                    </div>
                </div>

                <small
                    class="text-red-600 text-xs mt-1"
                    v-if="form.errors.type"
                    >{{ form.errors.type[0] }}</small
                >
            </div>

            <div class="text-center">
                <LoadingButton :loading="form.processing" class="block w-full">Save</LoadingButton>

                <Link
                    v-if="scene.id"
                    as="button"
                    method="DELETE"
                    :href="route('scenes.delete', [scene.history_id, scene])"
                    class="text-sm text-red-500 py-1 mt-1 px-2 inline-block"
                    :only="['errors', 'history']"
                    @before="confirmDelete"
                >
                    Delete Scene
                </Link>
            </div>
        </form>
    </Modal>
</template>

<script lang="ts" setup>
import { inject, ref, toRefs } from "vue";
import { Link } from "@inertiajs/vue3";

import {CardType, Event, Scene} from "@/types";
import {HistoryKey} from "@/symbols";
import {useCreateEditForm} from "@/composables/useCreateEditForm";

import Modal from "@/components/Modal.vue";
import LoadingButton from "@/components/LoadingButton.vue";

const props = withDefaults(
    defineProps<{
        event: Event,
        scene?: Omit<Scene, "position">|null
    }>(),
    {
        scene: () => ({
            question: "",
            scene: "",
            answer: "",
            type: CardType.Light,
        })
    }
)

const modal = ref(null);
const { scene } = toRefs(props);
const history = inject(HistoryKey);
const title = scene.value.id ? "Edit Scene" : "Create Scene";

const confirmDelete = () =>
    confirm("Are you sure you want to delete this scene?");

const { form, submit: formSubmit } = useCreateEditForm(
    scene,
    ["question", "scene", "answer", "type"],
    route("events.scenes.store", [history, props.event]),
    () => route("scenes.update", [history, scene.value])
);

const submit = formSubmit(() => modal.value.toggle());
</script>
