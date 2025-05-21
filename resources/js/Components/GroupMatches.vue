<template>
    <div class="space-y-4">
        <div class="flex justify-between items-center">
            <h3 class="text-lg font-semibold">Partidos de {{ group.name }}</h3>
            <Button @click="addMatch">Añadir partido</Button>
        </div>

        <div v-for="(match, index) in group.matches" :key="index" class="border rounded p-4 space-y-2">
            <div class="grid grid-cols-2 gap-4">
                <select v-model="match.player1" class="border rounded p-2">
                    <option disabled value="">Jugador 1</option>
                    <option v-for="p in group.players" :key="p.id" :value="p.name">{{ p.name }}</option>
                </select>
                <select v-model="match.player2" class="border rounded p-2">
                    <option disabled value="">Jugador 2</option>
                    <option v-for="p in group.players" :key="p.id" :value="p.name">{{ p.name }}</option>
                </select>
            </div>

            <div v-if="sport === 'tenis'" class="space-y-2">
                <Label>Resultados por set</Label>
                <div v-for="(set, sIndex) in match.sets" :key="sIndex" class="flex gap-2">
                    <input type="number" v-model.number="set[0]" placeholder="P1" class="w-16 border rounded p-1" />
                    <input type="number" v-model.number="set[1]" placeholder="P2" class="w-16 border rounded p-1" />
                </div>
                <Button size="sm" @click="addSet(index)">+ Set</Button>
            </div>

            <div v-else-if="sport === 'futbol'" class="flex gap-2 items-center">
                <Label>Resultado</Label>
                <input type="number" v-model.number="match.score[0]" class="w-16 border rounded p-1" />
                <span>-</span>
                <input type="number" v-model.number="match.score[1]" class="w-16 border rounded p-1" />
            </div>

            <div class="mt-2">
                <Label>Ganador</Label>
                <select v-model="match.winner" class="w-full border rounded p-2">
                    <option disabled value="">Selecciona ganador</option>
                    <option :value="match.player1">{{ match.player1 }}</option>
                    <option :value="match.player2">{{ match.player2 }}</option>
                </select>
            </div>
        </div>
    </div>
</template>

<script setup>
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'
import { reactive, toRefs, watch } from 'vue'

const props = defineProps({
    group: Object,
    sport: String
})

const emit = defineEmits(['result-updated'])

watch(() => props.group.matches, (newVal) => {
    emit('result-updated', newVal)
}, { deep: true })

function addMatch() {
    props.group.matches.push({
        player1: '',
        player2: '',
        sets: props.sport === 'tenis' ? [[0, 0]] : [],
        score: props.sport === 'futbol' ? [0, 0] : [],
        winner: ''
    })
}

function addSet(matchIndex) {
    props.group.matches[matchIndex].sets.push([0, 0])
}
</script>

<style scoped>
select, input {
    background-color: white;
}
</style>
