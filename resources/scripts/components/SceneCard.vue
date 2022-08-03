<template>
    <div class="mt-4 px-6">
        <GameCard :type="scene.type" label="Scene">
            <template #menu>
                <SceneModal :scene="scene" :event="event">
                    <CardButton :type="scene.type" />
                </SceneModal>

                <CardButton
                    @click="toggle"
                    :title="open ? 'Collapse Scene' : 'Expand Scene'"
                    :type="scene.type"
                >
                    <component
                        :is="open ? EyeOffIcon : EyeIcon"
                        class="w-4 h-4"
                    />
                </CardButton>
            </template>

            <p class="text-sm whitespace-pre-wrap" :class="{ 'pb-2': open }">
                {{ scene.question }}
            </p>

            <template v-if="open">
                <hr />

                <p v-if="scene.scene" class="text-sm py-2 whitespace-pre-wrap">
                    {{ scene.scene }}
                </p>
                <p
                    v-else
                    class="text-sm py-2 text-gray-600 italic whitespace-normal"
                >
                    This scene has no description.
                </p>

                <hr />

                <p v-if="scene.answer" class="text-sm pt-2 whitespace-pre-wrap">
                    {{ scene.answer }}
                </p>
                <p v-else class="text-sm pt-2 text-gray-600 italic">
                    This scene has not been answered yet.
                </p>
            </template>
        </GameCard>
    </div>
</template>

<script lang="ts" setup>
import { ref, inject, onUnmounted } from "vue";
import { EyeIcon, EyeOffIcon } from "@heroicons/vue/solid";

import { HistoryKey } from "@/symbols";
import { Scene, Event } from "@/types";
import { useEmitter } from "@/composables/useEmitter";
import GameCard from "@/components/GameCard.vue";
import SceneModal from "@/components/Modal/SceneModal.vue";
import CardButton from "@/components/CardButton.vue";

const props = defineProps<{ scene: Scene, event: Event }>();

const emitter = useEmitter();

const open = ref(false);
const toggle = () => (open.value = !open.value);
const history = inject(HistoryKey);

onUnmounted(emitter.on("scenes:toggle", toggle));
</script>
