<template>
    <div
        class="fixed w-72 z-10 bg-white ring-1 ring-gray-700 shadow-md rounded-sm"
        :style="{ transform: `translateX(${position.x}px) translateY(${position.y}px)` }"
        ref="el"
        @mousedown="onMouseDown"
        @mouseup="onMouseUp"
    >
        <button
            class="px-3 py-2 text-sm font-medium block w-full text-left"
            @dblclick="open = !open"
        >
            {{ title }}
        </button>

        <div
            v-if="open"
            class="border-t py-2 px-3"
        >
            <slot />
        </div>
    </div>
</template>

<script lang="ts" setup>
import { ref } from "vue";

type Position = { x: number, y: number };

const props = defineProps<{ title: string }>();
const open = ref(false);
const position = ref<Position>({ x: 30, y: 30 });
const offset = ref<Position>({ x: 0, y: 0 });
const el = ref<HTMLElement|null>(null);
const clicked = ref(false);

const snapElementToMouse = (e: PointerEvent) => {
    position.value.x = e.clientX - offset.value.x;
    position.value.y = e.clientY - offset.value.y;
};

const onMouseDown = (e: PointerEvent) => {
    const boundingBox = el.value.getBoundingClientRect();
    offset.value = {
        x: e.clientX - boundingBox.left,
        y: e.clientY - boundingBox.top,
    }

    clicked.value = true;
    window.addEventListener("mousemove", snapElementToMouse);
};

const onMouseUp = (e: PointerEvent) => {
    clicked.value = false;
    window.removeEventListener("mousemove", snapElementToMouse);
};
</script>
