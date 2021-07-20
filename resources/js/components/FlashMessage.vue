<template>
    <transition name="flash" v-if="$page.props.flash.success && show">
        <div class="fixed pr-4 pb-6 bottom-0 right-0">
            <div
                class="bg-green-600 shadow-lg rounded p-4 text-sm flex items-center justify-between text-white font-semibold"
            >
                {{ $page.props.flash.success }}

                <button @click="show = false" class="ml-4">
                    <Icon
                        name="close"
                        class="h-3 w-3 fill-current text-green-100"
                    />
                </button>
            </div>
        </div>
    </transition>
</template>

<script>
import Icon from "./Icon.vue";

export default {
    name: "FlashMessage",
    components: { Icon },
    data() {
        return {
            show: false
        };
    },

    watch: {
        "$page.props.flash": {
            handler() {
                this.show = true;
                setTimeout(() => (this.show = false), 3000);
            },
            deep: true
        }
    }
};
</script>
