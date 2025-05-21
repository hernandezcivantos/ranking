<template>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold">Clasificados a eliminatoria</h3>

        <div>
            <Label>Clasificados por grupo</Label>
            <select v-model.number="qualifiedPerGroup" class="border rounded p-2">
                <option :value="1">1º</option>
                <option :value="2">2 primeros</option>
                <option :value="3">3 primeros</option>
                <option :value="groupMax">Todos</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="group in groups" :key="group.id">
                <h4 class="font-semibold">{{ group.name }}</h4>
                <ul class="list-disc ml-5">
                    <li v-for="player in getQualified(group)" :key="player.name">{{ player.name }}</li>
                </ul>
            </div>
        </div>

        <Button @click="emitQualified">Generar brackets</Button>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { Button } from '@/components/ui/button'
import { Label } from '@/components/ui/label'

const props = defineProps({
    groups: Array
})

const emit = defineEmits(['qualified'])

const qualifiedPerGroup = ref(2)
const groupMax = 99 // se usa para "Todos"

function getQualified(group) {
    const sorted = [...group.players]
        .map(player => {
            const matchStats = group.matches.reduce(
                (acc, m) => {
                    if (m.winner === player.name) acc.points += 3
                    if (m.player1 === player.name || m.player2 === player.name) acc.played += 1
                    return acc
                },
                { name: player.name, points: 0, played: 0 }
            )
            return matchStats
        })
        .sort((a, b) => b.points - a.points)

    return sorted.slice(0, qualifiedPerGroup.value === groupMax ? sorted.length : qualifiedPerGroup.value)
}

function emitQualified() {
    const allQualified = props.groups.flatMap(group => getQualified(group))
    emit('qualified', allQualified)
}
</script>

<style scoped>
select {
    background-color: white;
}
</style>
