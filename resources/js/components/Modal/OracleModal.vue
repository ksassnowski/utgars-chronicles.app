<template>
    <button @click="showModal = true" class="px-4 py-2 bg-indigo-700 rounded text-white font-bold">
        <Modal v-if="showModal" title="Oracles" @close="showModal = false" size="lg">
            <div class="mb-8">
                <label for="oracle" class="label">Choose your Oracle</label>
                <select v-model="selected" id="oracle" class="input">
                    <option v-for="(oracle, i) in oracles" :value="i" :key="oracle.name">{{ oracle.name }}</option>
                </select>

                <p class="text-sm text-gray-700 mt-2">{{ selectedOracle.description }}</p>
            </div>

            <div class="mb-6 text-center">
                <p v-if="rolledOracle !== null" class="text-xl font-bold text-gray-900 flex items-center justify-center">
                    {{ rolledOracle.trend }}
                    <span class="py-1 px-3 rounded shadow border border-gray-100 mx-2">{{ rolledOracle.elementA }}</span>
                    {{ rolledOracle.impact }}
                    <span class="py-1 px-3 rounded shadow border border-gray-100 mx-2">{{ rolledOracle.elementB }}</span>
                </p>
                <p v-else class="italic text-lg text-gray-500">The bones tell me … nothing!</p>
            </div>

            <div class="flex justify-center items-center">
                <button
                    class="text-white rounded py-2 px-4 bg-indigo-700 inline-flex items-center"
                    @click="roll"
                >
                    <Icon name="shuffle" class="fill-current w-4 mr-2"/>
                    Cast the bones
                </button>

                <button
                    v-if="rolledOracle !== null"
                    class="text-white rounded py-2 px-4 bg-indigo-700 inline-flex items-center ml-4"
                    @click="swap"
                >Swap Elements</button>
            </div>
        </Modal>

        Oracles
    </button>
</template>

<script>
import Modal from "../Modal";
import oracles from "../../oracles";
import Icon from "../Icon";

export default {
    name: "OracleModal",

    components: {
        Icon,
        Modal,
    },

    computed: {
        selectedOracle() {
            return this.oracles[this.selected];
        },
    },

    data() {
        return {
            showModal: false,
            oracles,
            selected: 0,
            rolledOracle: null,
        };
    },

    methods: {
        roll() {
            const rolls = this._getRolls(6);

            this.rolledOracle = {
                trend: this.selectedOracle.trends[rolls[0]],
                elementA: this.selectedOracle.elements[rolls[1]][rolls[2]],
                impact: this.selectedOracle.impacts[rolls[3]],
                elementB: this.selectedOracle.elements[rolls[4]][rolls[5] + 2],
            };
        },

        swap() {
            if (this.rolledOracle === null) {
                return;
            }

            const elementA = this.rolledOracle.elementA;
            this.rolledOracle.elementA = this.rolledOracle.elementB;
            this.rolledOracle.elementB = elementA;
        },

        _getRolls(n) {
            const rolls = [];

            for (let i = 0; i < n; i++) {
                rolls.push(Math.floor(Math.random() * n));
            }

            return rolls;
        }
    },

    watch:{
        selected() {
            this.rolledOracle = null;
        },
    }
}
</script>
