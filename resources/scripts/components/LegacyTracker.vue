<template>
    <SettingsPopover title="Legacies" button-text="Legacies">
        <template #description>
            Legacies are common threads that may stretch through time and
            influence history. A Legacy can take many forms–an object, a person,
            a place, a blood line, an organization, or even a philosophical
            ideal.
        </template>

        <LegacyForm :history="history" />

        <div
            v-if="legacies.length === 0"
            class="
                border-4 border-dashed border-gray-300
                rounded-xl
                px-6
                py-12
                text-center
                mt-2
            "
        >
            <span class="font-semibold text-gray-400"
                >No legacies defined yet</span
            >
        </div>

        <div v-else class="mt-2 space-y-2">
            <EditableCard
                v-for="legacy in legacies"
                :key="legacy.id"
                :item="legacy"
                :update-route="route('legacies.update', [history, legacy])"
                :delete-route="route('legacies.delete', [history, legacy])"
                :reload-props="['errors', 'legacies']"
                class="bg-white text-gray-800 border border-gray-200"
                button-classes="bg-gray-200 hover:bg-gray-300 text-gray-700 hover:text-gray-900"
            />
        </div>
    </SettingsPopover>
</template>

<script lang="ts" setup>
import { History, Legacy } from "@/types";

import SettingsPopover from "@/components/SettingsPopover.vue";
import LegacyForm from "@/components/LegacyForm.vue";
import EditableCard from "@/components/EditableCard.vue";

defineProps<{ legacies: Array<Legacy>, history: History }>();
</script>
