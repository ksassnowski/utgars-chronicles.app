<template>
    <div
        class="px-4 flex items-center justify-center pt-6 from-indigo-100 to-white"
        style="flex: 1 0 auto; background-image: radial-gradient(circle at top, var(--tw-gradient-stops) 50%)"
    >
        <Panel class="w-full md:max-w-md shadow-indigo-100 ring-indigo-50 space-y-4 flex flex-col">
            <Link href="/" class="inline-block self-center" title="Back to homepage">
                <img src="/images/logo_text.svg" class="h-12" alt="Utgar's Chronicles logo without text" />
            </Link>

            <div>
                <slot />
            </div>
        </Panel>
    </div>
</template>

<script lang="ts" setup>
import { Link } from "@inertiajs/vue3";

import PageFooter from "@/components/UI/PageFooter.vue";
import Panel from "@/components/UI/Panel.vue";
</script>
