<template>
    <div class="flex">
        <div class="space-y-6 w-80">
            <LfgListing
                v-for="game in games"
                :key="game.id"
                :game="game"
            />
        </div>

        <div></div>
    </div>
</template>

<script>
import LfgListing from "@/components/LfgListing.vue";

export default {
    name: "LfgList",

    components: {
        LfgListing,
    },

    props: {
        games: {
            type: Array,
            default: () => [],
        }
    }
}
</script>
