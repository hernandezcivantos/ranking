<template>
    <div class="space-y-6">
        <h2 class="text-2xl font-bold">Panel de Jornada</h2>

        <div class="flex gap-2 flex-wrap">
            <Button
                v-for="(step, index) in steps"
                :key="step.id"
                @click="currentStep = step.id"
                :variant="currentStep === step.id ? 'default' : 'outline'">
                {{ index + 1 }}. {{ step.label }}
            </Button>
        </div>

        <div v-if="currentStep === 'setup'">
            <JornadaPanel />
        </div>

        <div v-else-if="currentStep === 'groups'">
            <div v-for="group in groups" :key="group.id" class="mb-8">
                <GroupMatches :group="group" sport="tenis" />
                <GroupRanking :group="group" sport="tenis" />
            </div>
        </div>

        <div v-else-if="currentStep === 'qualify'">
            <AdvanceToBrackets :groups="groups" @qualified="handleQualified" />
        </div>

        <div v-else-if="currentStep === 'brackets'">
            <BracketsGenerator :players="qualifiedPlayers" />
        </div>

        <div v-else-if="currentStep === 'podium'">
            <FinalPodium :brackets="bracketsData" />
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import JornadaPanel from './JornadaPanel.vue'
import GroupMatches from './GroupMatches.vue'
import GroupRanking from './GroupRanking.vue'
import AdvanceToBrackets from './AdvanceToBrackets.vue'
import BracketsGenerator from './BracketsGenerator.vue'
import FinalPodium from './FinalPodium.vue'

const steps = [
    { id: 'setup', label: 'Configuración' },
    { id: 'groups', label: 'Partidos de grupos' },
    { id: 'qualify', label: 'Clasificación' },
    { id: 'brackets', label: 'Brackets' },
    { id: 'podium', label: 'Podio' }
]

const currentStep = ref('setup')

const groups = ref([
    {
        id: 'groupA',
        name: 'Grupo A',
        players: [{ id: 1, name: 'Alma' }, { id: 2, name: 'Fran' }, { id: 3, name: 'Lila' }],
        matches: []
    },
    {
        id: 'groupB',
        name: 'Grupo B',
        players: [{ id: 4, name: 'Hugo' }, { id: 5, name: 'Sofía' }, { id: 6, name: 'Leo' }],
        matches: []
    }
])

const qualifiedPlayers = ref([])
const bracketsData = ref([])

function handleQualified(players) {
    qualifiedPlayers.value = players
}
</script>

<style scoped>
button {
    text-transform: none;
}
</style>
