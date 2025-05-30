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
                <LoadingButton :loading="form.processing" class="block w-full">
                    Save
                </LoadingButton>

                <Link
                    v-if="event.id"
                    as="button"
                    method="DELETE"
                    :href="route('events.delete', [event.history_id, event])"
                    class="text-sm text-red-500 py-1 mt-1 px-2 inline-block"
                    :only="['errors', 'history']"
                    @before="confirmDelete"
                >
                    Delete Event
                </Link>
            </div>
        </form>
    </Modal>
</template>

<script lang="ts" setup>
import { inject, ref, toRefs } from "vue";
import { Link } from "@inertiajs/vue3";

import { CardType, Event, Period } from "@/types";
import { HistoryKey } from "@/symbols";
import { useCreateEditForm } from "@/composables/useCreateEditForm";
import Modal from "@/components/Modal.vue";
import LoadingButton from "@/components/LoadingButton.vue";

const props = withDefaults(
    defineProps<{
        period: Period,
        position?: number|null,
        event?: Pick<Event, "id"|"name"|"type">|null,
    }>(),
    {
        position: null,
        event: () => ({
            name: "",
            type: CardType.Light,
        }),
    }
);

const modal = ref(null);
const { event, position } = toRefs(props);
const history = inject(HistoryKey);
const title = props.event.id ? "Edit Event" : "Create Event";

const confirmDelete = () =>
    confirm(
        "Really delete this event? All scenes belonging to this event will be deleted too!"
    );

const { form, submit: formSubmit } = useCreateEditForm(
    event,
    ["name", "type"],
    route("periods.events.store", [history, props.period]),
    () => route("events.update", [history, props.event]),
    position
);
const submit = formSubmit(() => modal.value.toggle());
</script>
