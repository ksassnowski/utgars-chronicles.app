<template>
    <transition name="flash" v-if="$page.props.flash.success && show">
        <div class="fixed pr-4 pb-6 bottom-0 right-0 z-20">
            <div
                class="
                    bg-green-600
                    shadow-lg
                    rounded
                    p-4
                    text-sm
                    flex
                    items-center
                    justify-between
                    text-white
                    font-semibold
                "
            >
                {{ $page.props.flash.success }}

                <button @click="show = false" class="ml-4">
                    <XIcon class="h-4 w-4 text-green-100" />
                </button>
            </div>
        </div>
    </transition>
</template>

<script lang="ts">
import { defineComponent } from "vue";
import { XIcon } from "@heroicons/vue/solid";

export default defineComponent({
    name: "FlashMessage",

    components: {
        XIcon,
    },

    data() {
        return {
            show: false,
        };
    },

    watch: {
        "$page.props.flash": {
            handler() {
                this.show = true;
                setTimeout(() => (this.show = false), 3000);
            },
            deep: true,
        },
    },
});
</script>
