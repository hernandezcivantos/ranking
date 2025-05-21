<template>
    <div class="space-y-6 text-center">
        <h3 class="text-xl font-bold">🏆 Podio Final</h3>

        <div class="grid grid-cols-3 gap-4 items-end justify-center max-w-xl mx-auto">
            <div class="space-y-2">
                <div class="text-lg font-medium">🥈 2º</div>
                <div class="border rounded p-2">{{ second?.name || '-' }}</div>
            </div>

            <div class="space-y-2">
                <div class="text-lg font-medium">🥇 1º</div>
                <div class="border-2 border-yellow-400 rounded p-2 bg-yellow-50 font-bold text-lg">{{ first?.name || '-' }}</div>
            </div>

            <div class="space-y-2">
                <div class="text-lg font-medium">🥉 3º</div>
                <div class="border rounded p-2">{{ third?.name || '-' }}</div>
            </div>
        </div>

        <div v-if="!first" class="text-gray-500">Aún no se ha definido el ganador final.</div>
    </div>
</template>

<script setup>
const props = defineProps({
    brackets: Array
})

const lastRound = props.brackets[props.brackets.length - 1] || []
const finalMatch = lastRound?.[0] || {}

const first = { name: finalMatch.winner || null }
const second = { name: finalMatch.player1 === finalMatch.winner ? finalMatch.player2 : finalMatch.player1 }

let third = null
if (props.brackets.length >= 2) {
    const semiFinals = props.brackets[props.brackets.length - 2] || []
    const losers = semiFinals.map(m => (m.winner === m.player1 ? m.player2 : m.player1))
    third = { name: losers.find(n => n !== second.name) || null }
}
</script>

<style scoped>
div {
    font-family: sans-serif;
}
</style>
