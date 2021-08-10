<template>
    <Modal title="Create Event" ref="modal">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot />
            </div>
        </template>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="label">Name</label>
                <textarea type="text" class="input" id="name" ref="input" v-model="form.name" rows="5" required></textarea>
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
            </div>

            <LoadingButton :loading="form.processing">
                Finish and add event
            </LoadingButton>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, toRefs, ref, inject } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";

export default defineComponent({
    name: "CreateEventModal",

    props: {
        period: Object,
        position: Number,
    },

    components: {
        LoadingButton,
        Modal,
    },

    setup(props) {
        const modal = ref(null);
        const { position } = toRefs(props);
        const history = inject("history");
        const form = useForm({
            name: null,
            type: "light",
            position: position,
        });
        const submit = () => {
            form.post(route("periods.events.store", [history, props.period]), {
                only: ["history"],
                onSuccess: () => {
                    form.reset("name", "type");
                    modal.value.toggle();
                }
            });
        }

        return { modal, form, submit };
    }
});
</script>
