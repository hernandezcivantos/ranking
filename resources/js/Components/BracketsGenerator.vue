<template>
    <div class="space-y-6">
        <h3 class="text-lg font-semibold">Eliminatoria</h3>

        <div v-if="brackets.length === 0">
            <Button @click="generateBrackets">Generar cruces</Button>
        </div>

        <div v-else class="space-y-4">
            <div v-for="(round, roundIndex) in brackets" :key="roundIndex" class="border p-4 rounded">
                <h4 class="font-semibold mb-2">Ronda {{ roundIndex + 1 }}</h4>
                <div class="space-y-2">
                    <div
                        v-for="(match, matchIndex) in round"
                        :key="matchIndex"
                        class="flex justify-between items-center border rounded p-2">
                        <div class="flex-1">
                            <select v-model="match.player1" class="border rounded p-1 w-full mb-1">
                                <option disabled value="">Jugador 1</option>
                                <option v-for="p in allPlayers" :key="p.name" :value="p.name">{{ p.name }}</option>
                            </select>
                            <select v-model="match.player2" class="border rounded p-1 w-full">
                                <option disabled value="">Jugador 2</option>
                                <option v-for="p in allPlayers" :key="p.name" :value="p.name">{{ p.name }}</option>
                            </select>
                        </div>
                        <div class="ml-4">
                            <Label>Ganador</Label>
                            <select v-model="match.winner" class="border rounded p-1">
                                <option :value="match.player1">{{ match.player1 }}</option>
                                <option :value="match.player2">{{ match.player2 }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <Button @click="advanceRound">Avanzar Ronda</Button>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'

const props = defineProps({
    players: Array
})

const brackets = ref([])
const allPlayers = ref([...props.players])

function generateBrackets() {
    const shuffled = [...props.players].sort(() => 0.5 - Math.random())
    const round = []

    for (let i = 0; i < shuffled.length; i += 2) {
        round.push({
            player1: shuffled[i]?.name || '',
            player2: shuffled[i + 1]?.name || '',
            winner: ''
        })
    }

    brackets.value.push(round)
}

function advanceRound() {
    const lastRound = brackets.value[brackets.value.length - 1]
    const nextPlayers = lastRound.map(m => ({ name: m.winner })).filter(p => p.name)
    if (nextPlayers.length < 2) return
    const nextRound = []
    for (let i = 0; i < nextPlayers.length; i += 2) {
        nextRound.push({
            player1: nextPlayers[i]?.name || '',
            player2: nextPlayers[i + 1]?.name || '',
            winner: ''
        })
    }
    brackets.value.push(nextRound)
}
</script>

<style scoped>
select {
    background-color: white;
}
</style>
