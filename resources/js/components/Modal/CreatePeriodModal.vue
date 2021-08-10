<template>
    <Modal title="Add Period" ref="modal">
        <template v-slot:button="{ toggle }">
            <div @click="toggle">
                <slot/>
            </div>
        </template>

        <form @submit.prevent="submit">
            <div class="mb-4">
                <label for="name" class="label" ref="input">Name</label>
                <textarea type="text" class="input" id="name" v-model="form.name" rows="5" required></textarea>
            </div>

            <div class="mb-4">
                <p class="label">Tone</p>

                <div class="flex justify-between items-center">
                    <div class="space-x-1">
                        <input type="radio" id="light" value="light" v-model="form.type">
                        <label for="light">Light</label>
                    </div>

                    <div class="space-x-1">
                        <input type="radio" id="dark" value="dark" v-model="form.type">
                        <label for="dark">Dark</label>
                    </div>
                </div>
            </div>

            <LoadingButton :loading="form.processing">Save</LoadingButton>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, ref, toRefs } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import Modal from "../Modal.vue";
import LoadingButton from "../LoadingButton.vue";

export default defineComponent({
    name: "CreatePeriodModal",

    components: {
        LoadingButton,
        Modal,
    },

    props: {
        history: {
            type: Object,
            required: true,
        },
        position: {
            type: Number,
            required: true,
        },
    },

    setup(props) {
        const { position } = toRefs(props);
        const modal = ref(null);
        const form = useForm({
            name: null,
            type: 'light',
            position: position,
        });

        const submit = async () => {
            form.post(route("history.periods.store", props.history).url(), {
                only: ["history"],
                onSuccess: () => {
                    form.reset("name", "type");
                    modal.value.toggle();
                },
            });
        };

        return { form, modal, submit };
    },
});
</script>
