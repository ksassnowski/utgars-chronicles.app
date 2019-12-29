<template>
    <div class="p-4 border-2 bg-white border-gray-600 relative">
        <p class="text-sm pb-2">{{ scene.question }}</p>

        <hr>

        <p class="text-sm py-2">{{ scene.scene }}</p>

        <hr>

        <p class="text-sm pt-2">{{ scene.answer }}</p>

        <div
            class="rounded-full border-2 border-gray-800 h-6 w-6 absolute"
            style="top: -12px"
            :class="{ 'bg-white': scene.type === 'light', 'bg-gray-800': scene.type === 'dark' }"
        ></div>
    </div>
</template>

<script>
export default {
    name: 'SceneCard',

    props: ['scene'],
};
</script>
