<template>
    <div class="flex flex-col" style="flex: 1 0 auto;">
        <Navigation v-if="$page.props.auth.user !== null" />

        <main class="flex-1">
            <FlashMessage />

            <slot />
        </main>

        <PageFooter />
    </div>
</template>

<script lang="ts" setup>
import FlashMessage from "@/components/FlashMessage.vue";
import Navigation from "@/components/Navigation.vue";
import PageFooter from "@/components/UI/PageFooter.vue";
</script>
