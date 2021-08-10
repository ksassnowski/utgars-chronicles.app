<template>
    <div class="relative game-card">
        <article
            class="relative p-8 relative shadow-sm rounded-lg border border-gray-200 text-sm w-full min-h-32 group"
            :class="{ 'bg-gray-700 text-white': event.type === 'dark', 'bg-white text-gray-700': event.type === 'light' }"
        >
            <template v-if="!editing">
                <div class="invisible group-hover:visible absolute left-0 top-0 w-full pl-3 pr-2 pt-2 flex justify-between z-20">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="handle w-4 h-4 fill-current text-gray-400 cursor-move mt-[2px]"
                        viewBox="0 0 20 20"
                    ><path d="M0 3h20v2H0V3zm0 4h20v2H0V7zm0 4h20v2H0v-2zm0 4h20v2H0v-2z"/></svg>

                    <SettingsPanel
                        v-if="!editing"
                        @delete="remove"
                        @edit="edit"
                    />
                </div>

                <h4 class="text-center">{{ event.name }}</h4>

                <div class="absolute invisible group-hover:visible flex justify-end items-center inset-x-0 bottom-0 px-2 pb-2">
                    <SceneModal :event="event">
                        <button
                            class="text-sm"
                            :class="{ 'text-indigo-700': event.type === 'light', 'text-indigo-300': event.type === 'dark' }"
                        >Add Scene</button>
                    </SceneModal>
                </div>

                <p
                    class="absolute top-0 text-sm font-bold leading-loose uppercase px-1"
                    :class="{ 'text-white bg-gray-700': event.type === 'dark', 'text-gray-700 bg-white': event.type === 'light' }"
                    style="top: -15px; right: 20px;"
                >
                    Event
                </p>
            </template>

            <form v-else @submit.prevent="submit">
                <div class="mb-4">
                    <label for="name" class="label">Name</label>
                    <textarea id="name" rows="4" class="input" v-model="form.name"></textarea>
                </div>

                <div class="mb-4">
                    <p class="label">Tone</p>

                    <div class="flex justify-between items-center">
                        <div>
                            <input type="radio" id="dark" value="dark" v-model="form.type">
                            <label for="dark">Dark</label>
                        </div>

                        <div>
                            <input type="radio" id="light" value="light" v-model="form.type">
                            <label for="light">Light</label>
                        </div>
                    </div>
                </div>


                <LoadingButton :loading="loading">
                    {{ loading ? 'Hang on...' : 'Save' }}
                </LoadingButton>

                <button type="button" class="w-full text-gray-700 mt-2" @click="cancel">Cancel</button>
            </form>
        </article>

        <draggable
            :list="event.scenes"
            @change="sceneMoved"
            handle=".handle"
            item-key="id"
        >
            <template #item="{element}">
                <SceneCard :scene="element" />
            </template>
        </draggable>

    </div>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import axios from 'axios';
import draggable from 'vuedraggable';

import LoadingButton from "./LoadingButton.vue";
import SettingsPanel from "./SettingsPanel.vue";
import SceneCard from "./SceneCard.vue";
import SceneModal from "./Modal/SceneModal.vue";
import Icon from "./Icon.vue";

export default defineComponent({
    name: 'EventCard',

    props: {
        event: Object,
        period: Object,
    },

    inject: ['history'],

    components: {
        Icon,
        SceneModal,
        draggable,
        SceneCard,
        SettingsPanel,
        LoadingButton,
    },

    data() {
        return {
            editing: false,
            loading: false,
            form: {
                type: this.event.type,
                name: this.event.name,
            },
        };
    },

    methods: {
        sceneMoved(e) {
            if (!e.moved) {
                return;
            }

            this.$inertia.post(this.$route("scenes.move", [this.history, e.moved.element]), {
                position: e.moved.newIndex + 1,
            })
        },

        submit()  {
            if (
                this.form.type === this.event.type
                && this.form.name === this.event.name
            ) {
                this.editing = false;
                return;
            }

            this.loading = true;

            axios.put(this.$route('events.update', [this.history, this.event]), this.form)
                .then(() => {
                    this.loading = false;
                    this.editing = false
                })
                .catch((err) => {
                    this.loading = false;
                    console.error(err);
                });
        },

        remove() {
            const confirmed = confirm('Really delete this event? All scenes belonging to this event will be deleted too!');

            if (!confirmed) {
                return;
            }

            axios.delete(this.$route('events.delete', [this.history, this.event]))
                .catch(console.error);
        },

        edit() {
            this.form.type = this.event.type;
            this.form.name = this.event.name;
            this.editing = true;
        },

        cancel() {
            this.form.type = this.event.type;
            this.form.name= this.event.name;
            this.editing = false;
        },
    },
});
</script>
