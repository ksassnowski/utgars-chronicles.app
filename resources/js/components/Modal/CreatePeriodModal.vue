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

            <LoadingButton :loading="loading">Save</LoadingButton>
        </form>
    </Modal>
</template>

<script lang="ts">
import { defineComponent, ref, toRefs } from "vue";
import axios from "axios";

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
        const loading = ref(false);
        const form = ref({
            name: null,
            type: 'light',
            position: position,
        });

        const submit = async () => {
            loading.value = true;

            try {
                await axios.post(route("history.periods.store", props.history).url(), form.value);
                modal.value.toggle();
                form.value.name = null;
                form.value.type = "light";
            } finally {
                loading.value = false;
            }
        };

        return { form, loading, modal, submit };
    },
});
</script>
