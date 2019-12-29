<template>
    <div>
        <article class="relative p-8 relative rounded-sm border-2 bg-white border-gray-600 text-sm w-full mb-4 min-h-32 group">
            <template v-if="!editing">
                <h4 class="text-center">{{ event.name }}</h4>

                <div class="absolute invisible group-hover:visible flex justify-between items-center inset-x-0 bottom-0 px-2 pb-2">
                    <button
                        class="text-sm text-red-500"
                        @click="remove"
                    >
                        Delete
                    </button>

                    <button
                        class="text-sm text-indigo-700"
                        @click="editing = true"
                    >Edit</button>
                </div>

                <div
                    class="rounded-full border-2 border-gray-800 h-6 w-6 absolute"
                    style="top: -12px"
                    :class="{ 'bg-white': event.type === 'light', 'bg-gray-800': event.type === 'dark' }"
                ></div>
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

                <div class="flex justify-between items-center">
                    <button type="button" class="text-sm text-gray-500" @click="cancel">Cancel</button>
                    <button type="submit" class="text-sm text-indigo-600">Save</button>
                </div>
            </form>
        </article>

        <draggable :list="orderedScenes" @change="sceneMoved" class="px-6">
            <SceneCard v-for="scene in orderedScenes" :scene="scene" :key="scene.id" />
        </draggable>
    </div>
</template>

<script>
import sortBy from 'lodash/sortBy';
import draggable from 'vuedraggable';

import SceneCard from './SceneCard';

export default {
    name: 'EventCard',

    props: ['event', 'period'],

    components: {
        draggable,
        SceneCard,
    },

    computed: {
        orderedScenes() {
            return sortBy(this.event.scenes, ['position']);
        },
    },

    data() {
        return {
            editing: false,
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

            Bus.$emit('scene.moved', {
                period: this.period,
                event: this.event,
                scene: e.moved.element,
                position: e.moved.newIndex + 1,
            });
        },

        submit()  {
            if (
                this.form.type === this.event.type
                && this.form.name === this.event.name
            ) {
                this.editing = false;
                return;
            }

            Bus.$emit('event.saved', {
                period: this.period.id,
                event: this.event.id,
                payload: this.form,
            });

            this.editing = false;
        },

        remove() {
            Bus.$emit('event.removed', {
                period: this.period.id,
                event: this.event.id,
            });
        },

        cancel() {
            this.form.type = this.event.type;
            this.form.name= this.event.name;
            this.editing = false;
        },
    },
};
</script>
