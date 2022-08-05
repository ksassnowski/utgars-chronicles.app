<template>
    <SettingsPopover title="Echo" button-text="Echo">
        <template #icon>
            <RssIcon class="w-4 h-4" />
        </template>

        <template #description>
            Keep track of all Echo-related information for your game. This is purely informational and does not
            affect the behavior of the game.
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
import {computed, inject} from "vue";
import { RssIcon } from "@heroicons/vue/outline";

import { AgentPowers, History } from "@/types";
import { EchoSettingsKey } from "@/symbols";
import SettingsPopover from "@/components/SettingsPopover.vue";
import InputLabel from "@/components/UI/InputLabel.vue";
import SelectInput from "@/components/UI/SelectInput.vue";
import FactionCard from "@/components/FactionCard.vue";
import {Inertia} from "@inertiajs/inertia";

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
