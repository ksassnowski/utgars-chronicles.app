<template>
    <div
        class="fixed bottom-0 border-gray-300 px-4 bg-gray-100 pt-2"
        :class="{ 'w-20 h-10 right-0 border': !open, 'border-t inset-x-0 pb-2': open }"
    >
        <button class="absolute right-0 top-0 pr-4 pt-2 text-sm text-gray-600" @click="open = !open">
            {{ open ? 'Collapse' : 'Open' }}
        </button>

        <div v-show="open">
            <slot/>
        </div>
    </div>
</template>

<script>
export default {
    name: 'GamePanel',

    data() {
        return {
            open: true,
        };
    },
};
</script>
