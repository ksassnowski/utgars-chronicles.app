<template>
    <div>
        <button ref="button" class="cursor-pointer">
            <svg class="w-4 h-4 text-gray-600 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0-6a2 2 0 1 1 0-4 2 2 0 0 1 0 4zm0 12a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>
            <div ref="template">
                <ul>
                    <li class="border-b border-gray-300 pr-4 py-1">
                        <button class="text-sm text-blue-500" @click="$emit('edit')">Edit</button>
                    </li>

                    <li class="pr-4 py-1">
                        <button class="text-red-500 text-sm" @click="$emit('delete')">
                            Delete
                        </button>
                    </li>
                </ul>
            </div>
        </button>
    </div>
</template>

<script>
import tippy from 'tippy.js';

import ConfirmAction from '@/components/ConfirmAction';

export default {
    name: 'SettingsPanel',

    components: {
        ConfirmAction,
    },

    data() {
        return {
            tippyInstance: null,
        };
    },

    mounted() {
        this.tippyInstance = tippy(this.$refs.button, {
            trigger: 'click',
            content: this.$refs.template,
            interactive: true,
            placement: 'right',
            theme: 'light',
        });
    },

    beforeDestroy() {
        this.tippyInstance.destroy();
    },
};
</script>

