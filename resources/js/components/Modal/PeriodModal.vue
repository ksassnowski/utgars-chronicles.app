<template>
    <Modal :title="title" ref="modal">
        <template v-slot:button="{ toggle }">
            <div @click="toggle" v-bind="$attrs">
                <slot />
            </div>
        </template>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="label" ref="input">Name</label>
                <textarea
                    v-focus
                    type="text"
                    class="input"
                    id="name"
                    v-model="form.name"
                    rows="5"
                    required
                ></textarea>
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
            </div>

            <div class="text-center">
                <LoadingButton :loading="form.processing">Save</LoadingButton>

                <Link
                    v-if="period.id"
                    as="button"
                    method="DELETE"
                    :href="$route('periods.delete', [history, period])"
                    class="text-sm text-red-500 py-1 mt-1 px-2 inline-block"
                    :only="['errors', 'history']"
                    @before="confirmDelete"
                >
                    Delete Period
                </Link>
            </div>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, ref, toRefs } from "vue";
import { Link } from "@inertiajs/inertia-vue3";

import { useCreateEditForm } from "../../composables/useCreateEditForm";
import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";

export default defineComponent({
    name: "PeriodModal",

    components: {
        LoadingButton,
        Modal,
        Link,
    },

    props: {
        history: {
            type: Object,
            required: true,
        },
        position: {
            type: Number,
            default: null,
        },
        period: {
            type: Object,
            default: () => ({
                name: "",
                type: "light",
            }),
        },
    },

    methods: {
        confirmDelete() {
            return confirm(
                "Really delete this period? All events and scenes inside of this period will be deleted as well."
            );
        },
    },

    setup(props) {
        const { period, position } = toRefs(props);
        const modal = ref(null);

        const { form, submit } = useCreateEditForm(
            period,
            ["name", "type"],
            route("history.periods.store", [props.history]),
            () => route("periods.update", [props.history, props.period]),
            position
        );

        return {
            form,
            modal,
            submit: submit(() => modal.value.toggle()),
            title: period.value.id ? "Edit Period" : "Create Period",
        };
    },
});
</script>
