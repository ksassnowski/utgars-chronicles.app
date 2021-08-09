<template>
    <Modal title="Add Period" ref="modal">
        <template v-slot="{ toggle }">
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

<script lang="ts">
import { defineComponent, ref } from "vue";
import { useForm } from "@inertiajs/inertia-vue3";

import Modal from "../Modal.vue";

export default defineComponent({
    name: "CreatePeriodModal",

    components: {
        Modal,
    },

    props: {
        position: {
            type: Number,
            required: true,
        },
    },

    setup(props) {
        const modal = ref(null);
        const form = useForm({
            name: null,
            type: 'light',
            position: props.position,
        });

        return { form, modal };
    },
});
</script>
