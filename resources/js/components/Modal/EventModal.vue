<template>
    <Modal :title="title" ref="modal">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot />
            </div>
        </template>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="label">Name</label>
                <textarea
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

            <LoadingButton :loading="form.processing"> Save </LoadingButton>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, toRefs, ref, inject } from "vue";

import { useCreateEditForm } from "../../composables/useCreateEditForm";
import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";

export default defineComponent({
    name: "EventModal",

    props: {
        period: Object,
        position: {
            type: Number,
            default: null,
        },
        event: {
            type: Object,
            default: () => ({
                name: "",
                type: "light",
            }),
        },
    },

    components: {
        LoadingButton,
        Modal,
    },

    setup(props) {
        const modal = ref(null);
        const { event, position } = toRefs(props);
        const history = inject("history");

        const { form, submit } = useCreateEditForm(
            event,
            ["name", "type"],
            route("periods.events.store", [history, props.period]),
            () => route("events.update", [history, props.event]),
            position
        );

        return {
            modal,
            form,
            submit: submit(() => modal.value.toggle()),
            title: props.event.id ? "Edit Event" : "Create Event",
        };
    },
});
</script>
