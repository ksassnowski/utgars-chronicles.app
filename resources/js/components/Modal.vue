<template>
    <portal to="modal">
        <div class="fixed inset-x-0 inset-y-0 z-50 flex items-center justify-center" style="background-color: rgba(0, 0, 0, 0.75)">
            <transition name="modal" appear>
                <div
                    class="rounded p-4 pt-2 shadow bg-white relative w-2/3 md:w-1/2"
                    :class="{ 'lg:w-3/5': size === 'lg', 'lg:w-1/3': size === 'sm' }"
                >
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
            </transition>
        </div>
    </portal>
</template>

<script>
export default {
    name: 'Modal',

    props: {
        title: {
            type: String,
            required: true,
        },
        size: {
            type: String,
            default: 'sm',
        },
    },
};
</script>

