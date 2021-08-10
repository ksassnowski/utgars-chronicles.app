<template>
    <div class="max-h-full flex flex-col">

        <div class="game-card">
            <article
                class="p-8 rounded-lg border border-gray-200 mb-6 shadow-sm relative panzoom-exclude group"
                :class="{ 'bg-gray-700 text-white': period.type === 'dark', 'bg-white text-gray-700': period.type === 'light' }"
            >
                <template v-if="!editing">
                    <div
                        class="invisible group-hover:visible absolute left-0 top-0 w-full pl-3 pr-2 pt-2 flex justify-between z-20"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            class="handle w-4 h-4 fill-current text-gray-400 cursor-move"
                            style="margin-top: 2px"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"
                            />
                        </svg>

                        <SettingsPanel
                            v-if="!editing"
                            @delete="remove"
                            @edit="edit"
                        />
                    </div>

                    <h3 class="font-bold tracking-wide text-center py-2">
                        {{ period.name }}
                    </h3>

                    <p
                        class="absolute top-0 text-sm font-bold leading-loose uppercase px-1"
                        :class="{ 'text-gray-700': period.type === 'light', 'text-white': period.type === 'dark' }"
                        style="top: -15px; right: 20px;"
                    >
                        Period
                    </p>

                    <div
                        class="flex justify-end absolute inset-x-0 bottom-0 p-2 invisible group-hover:visible"
                    >
                        <CreateEventModal
                            :period="period"
                            :position="nextEventPosition"
                        >
                            <button
                                class="text-sm"
                                :class="{ 'text-indigo-700': period.type === 'light', 'text-indigo-300': period.type === 'dark' }"
                            >
                                Add Event
                            </button>
                        </CreateEventModal>
                    </div>
                </template>

                <form v-else @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="name" class="label">Name</label>
                        <textarea
                            type="text"
                            id="name"
                            v-model="form.name"
                            class="input"
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <p class="label">Tone</p>

                        <div class="flex justify-between items-center">
                            <div>
                                <label for="dark" class="label">Dark</label>
                                <input
                                    type="radio"
                                    id="dark"
                                    value="dark"
                                    v-model="form.type"
                                />
                            </div>

                            <div>
                                <label for="light" class="label">Light</label>
                                <input
                                    type="radio"
                                    id="light"
                                    value="light"
                                    v-model="form.type"
                                />
                            </div>
                        </div>
                    </div>

                    <LoadingButton :loading="loading">
                        {{ loading ? "Hang on..." : "Save" }}
                    </LoadingButton>

                    <button
                        type="button"
                        class="text-sm text-gray-700 mt-2 text-center w-full"
                        @click="cancel"
                    >
                        Cancel
                    </button>
                </form>
            </article>
        </div>

        <draggable
            :list="period.events"
            @change="eventMoved"
            class="overflow-x-hidden overflow-y-auto space-y-8"
            style="flex: 1 1 auto"
            handle=".handle"
            item-key="id"
        >
            <template #item="{element}">
                <EventCard :event="element" :period="period" />
            </template>
        </draggable>
    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import axios from "axios";
import draggable from "vuedraggable";

import EventCard from "./EventCard.vue";
import SettingsPanel from "./SettingsPanel.vue";
import Modal from "./Modal.vue";
import CreateEventModal from "./Modal/CreateEventModal.vue";
import LoadingButton from "./LoadingButton.vue";
import Icon from "./Icon.vue";

export default defineComponent({
    name: "PeriodCard",

    props: {
        period: Object,
        historyId: Number,
    },

    components: {
        Icon,
        LoadingButton,
        CreateEventModal,
        Modal,
        SettingsPanel,
        draggable,
        EventCard
    },

    computed: {
        nextEventPosition() {
            const last = this.period.events.slice(-1)[0];

            if (!last) {
                return 1;
            }

            return last.position + 1;
        },
    },

    data() {
        return {
            editing: false,
            loading: false,
            form: {
                name: this.period.name,
                type: this.period.type
            }
        };
    },

    methods: {
        eventMoved(e) {
            if (!e.moved) {
                return;
            }

            this.$inertia.post(this.$route("events.move", [this.historyId, e.moved.element]), {
                position: e.moved.newIndex + 1,
            });
        },

        edit() {
            this.editing = true;
            this.form.name = this.period.name;
            this.form.type = this.period.type;
        },

        cancel() {
            this.editing = false;
            this.form.name = this.period.name;
            this.form.type = this.period.type;
        },

        submit() {
            if (
                this.form.name === this.period.name &&
                this.form.type === this.period.type
            ) {
                this.editing = false;
                return;
            }

            this.loading = true;

            axios
                .put(
                    this.$route("periods.update", [
                        this.historyId,
                        this.period
                    ]),
                    this.form
                )
                .then(() => {
                    this.loading = false;
                    this.editing = false;
                })
                .catch(() => {
                    this.loading = false;
                });
        },

        remove() {
            const confirmed = confirm(
                "Really delete this period? This will delete all events and scenes as well!"
            );

            if (confirmed) {
                this.$inertia.delete(this.$route("periods.delete", [this.historyId, this.period]));
            }
        },
    },
});
</script>
