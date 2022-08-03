<template>
    <form @submit.prevent="onSubmit" class="max-w-3xl mx-auto px-4 pt-8">
        <h1 class="text-xl font-bold">Look for players</h1>

        <div class="rounded shadow-lg py-6 border border-gray-100 mt-6 px-6 space-y-6">
            <InputGroup label="Title" name="title">
                <TextInput
                    v-model="form.title"
                    name="title"
                    placeholder="Saturday Night Microscope"
                    required
                />
            </InputGroup>

            <InputGroup label="Date and Time" name="date">
                <div class="flex space-x-4 items-center">
                    <!--DatePicker
                        v-model="form.start_date"
                        type="datetime"
                        class="w-full block flex-1"
                        :input-attr="{ name: 'date', id: 'date' }"
                        input-class="w-full bg-gray-100 px-4 py-2 border-2 border-transparent rounded focus:bg-white focus:border-indigo-700 placeholder-gray-400"
                        format="YYYY-MM-DD, HH:mm"
                        :show-second="false"
                    /-->

                    <span>
                        {{ gameStartsIn }}
                    </span>
                </div>

                <HelpText>
                    Your current timezone is set to <strong>{{ timezone }}</strong>. If this is not correct, please go to your profile to
                    change it. Otherwise other players might see an incorrect time.
                </HelpText>
            </InputGroup>

            <InputGroup label="Maximum Players" name="slots">
                <NumberInput
                    v-model="form.slots"
                    name="slots"
                    :min="2"
                    :step="1"
                    placeholder="4"
                    required
                />
            </InputGroup>
        </div>

        <div class="flex justify-end mt-6">
            <button
                type="submit"
                class="bg-indigo-700 text-white px-6 py-2 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                :disabled="!formValid"
            >
                Start looking for players
            </button>
        </div>
    </form>
</template>

<script lang="ts">
import layout from "@/Pages/Layouts/Layout.vue";

export default { layout };
</script>

<script lang="ts" setup>
import { computed } from "vue";
import dayjs from "dayjs";

import InputGroup from "@/components/UI/InputGroup.vue";
import TextInput from "@/components/UI/TextInput.vue";
import HelpText from "@/components/UI/HelpText.vue";
import NumberInput from "@/components/UI/NumberInput.vue";
import {useForm} from "@inertiajs/inertia-vue3";

const timezone = dayjs.tz.guess();

const form = useForm({
    title: "",
    start_date: null,
    slots: null,
});

const gameStartsIn = computed(() => {
    if (form.start_date === null) {
        return 'Please select a date';
    }

    return dayjs(form.start_date).fromNow();
});

const formValid = computed(() =>
    form.title
        && dayjs(form.start_date).isValid()
        && form.slots !== null
        && form.slots >= 2
);

const onSubmit = () => form.post(route('lfg.store'));
</script>
