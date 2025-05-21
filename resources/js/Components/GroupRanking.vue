<template>
    <div class="space-y-4">
        <h3 class="text-lg font-semibold mb-2">Clasificación {{ group.name }}</h3>
        <table class="w-full table-auto border">
            <thead>
            <tr class="bg-gray-100">
                <th class="border px-2 py-1 text-left">Jugador</th>
                <th class="border px-2 py-1 text-center">Ganados</th>
                <th class="border px-2 py-1 text-center">Perdidos</th>
                <th class="border px-2 py-1 text-center">Puntos</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="player in ranking" :key="player.name">
                <td class="border px-2 py-1">{{ player.name }}</td>
                <td class="border px-2 py-1 text-center">{{ player.wins }}</td>
                <td class="border px-2 py-1 text-center">{{ player.losses }}</td>
                <td class="border px-2 py-1 text-center">{{ player.points }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
    group: Object,
    sport: String
})

const ranking = computed(() => {
    const stats = {}

    props.group.players.forEach(p => {
        stats[p.name] = {
            name: p.name,
            wins: 0,
            losses: 0,
            points: 0
        }
    })

    props.group.matches.forEach(match => {
        const { player1, player2, winner } = match
        if (!player1 || !player2 || !winner) return

        if (stats[player1] && stats[player2]) {
            stats[winner].wins++
            const loser = winner === player1 ? player2 : player1
            stats[loser].losses++

            // Asignar puntos (ejemplo: 3 al ganador, 0 al perdedor)
            stats[winner].points += 3
        }
    })

    return Object.values(stats).sort((a, b) => b.points - a.points)
})
</script>

<style scoped>
th, td {
    font-size: 0.9rem;
}
</style>
