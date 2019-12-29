<template>
    <div>
        <h3 class="font-bold text-sm text-gray-700 mb-4">Focus</h3>

        <div class="flex -mx-2">
            <div
                v-for="focus in internalFoci"
                :key="focus.id"
                class="border-4 border-dotted border-gray-400 h-40 px-12 flex items-center justify-center text-gray-600 mx-2"
            >
                {{ focus.name }}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'FocusTracker',

    props: ['channel', 'foci'],

    data() {
        return {
            internalFoci: this.foci,
        };
    },

    created() {
        Echo.join(this.channel)
            .listen('FocusDefined', (e) => {
                this.internalFoci.push(e);
            })
            .listen('FocusUpdated', ({ focus }) => {
                const matchingFocus = this.internalFoci.find(f => f.id === focus.id);

                if (!matchingFocus) {
                    return;
                }

                Object.assign(matchingFocus, focus);
            })
            .listen('FocusDeleted', ({ id }) => {
                this.internalFoci = this.internalFoci.filter(f => f.id !== id);
            });
    },

    beforeDestroy() {
        Echo.leave(this.channel);
    },
};
</script>
