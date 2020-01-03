<template>
    <transition name="modal">
        <div class="fixed inset-x-0 inset-y-0 z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.75)">
            <div class="rounded p-4 pt-2 shadow bg-white relative w-2/3 md:w-1/2 lg:w-1/3">
                <header class="flex justify-between mb-4 items-center">
                    <h5 class="font-bold text-lg text-gray-800">
                        {{ title }}
                    </h5>

                    <button title="Close modal" class="text-2xl text-gray-500" @click="$emit('close')">
                        &times;
                    </button>
                </header>

                <slot />
            </div>
        </div>
    </transition>
</template>

<script>
export default {
    name: 'Modal',

    props: ['title'],
};
</script>

