<template>
    <SettingsPopover title="Factions" button-text="Factions">
        <template #icon>
            <OfficeBuildingIcon class="w-4 h-4" />
        </template>

        <template #description>
            In Echo, competing factions try to change the past to make it turn out the way
            they want, whether that’s preventing an apocalyptic war, saving a species
            from extinction or planting the seeds of a more enlightened society.
        </template>

        <form @submit.prevent="submit" class="space-y-6">
            <div>
                <InputLabel for="agent_powers">Faction Power</InputLabel>

                <SelectInput
                    :options="options"
                    :model-value="selectedOption"
                    @update:modelValue="submit($event)"
                    name="agent_powers"
                    class="mt-1"
                />
            </div>

            <FactionCard
                faction="1"
                :name="settings.faction_1_name || 'Faction 1'"
                :description="settings.faction_1_description || 'Faction 1 goals...'"
                :history="history"
            />

            <FactionCard
                faction="2"
                :name="settings.faction_2_name || 'Faction 2'"
                :description="settings.faction_2_description || 'Faction 2 goals...'"
                :history="history"
            />
        </form>
    </SettingsPopover>
</template>

<script lang="ts" setup>
import { computed, inject } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { OfficeBuildingIcon } from "@heroicons/vue/outline";

import { AgentPowers, History } from "@/types";
import { EchoSettingsKey } from "@/symbols";
import SettingsPopover from "@/components/SettingsPopover.vue";
import InputLabel from "@/components/UI/InputLabel.vue";
import SelectInput from "@/components/UI/SelectInput.vue";
import FactionCard from "@/components/FactionCard.vue";

const props = defineProps<{ history: History }>();
const settings = inject(EchoSettingsKey);

const options = [
    { name: AgentPowers.Ordinary },
    { name: AgentPowers.Extraordinary },
    { name: AgentPowers.Omnipotent },
];
const selectedOption = computed(() => {
    if (settings.value.agent_powers === null) {
        return options[0];
    }

    return options.find((option) => option.name === settings.value.agent_powers);
});

const submit = (option: { name: AgentPowers }) =>
    Inertia.patch(
        route('history.echo-settings.update', props.history),
        { agent_powers: option.name },
        { only: ["echoGameSettings"] },
    );
</script>
